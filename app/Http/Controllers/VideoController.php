<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Video;
use App\Models\VideoAsset;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;
use FFMpeg\FFMpeg;
use FFMpeg\Format;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Media\Video as FFMpegVideo;
use FFMpeg\Coordinate\Dimension as FFMpegDimension;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;
use Pion\Laravel\ChunkUpload\Exceptions\UploadFailedException;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use App\Http\Requests\VideoUpdateRequest;
use App\Http\Requests\VideoStoreRequest;
use App\Rules\ImageFile;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response {
        $videos = Video::where('visibility', 0)
                    ->latest()
                    ->take(12)
                    ->get();

        $videos->load('user');
        $videos->load('asset');

        return Inertia::render('Video/Videos', [
            'videos' => $videos
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response {
        return Inertia::render('Video/Create',[
            'csrfToken' => csrf_token()
        ]);
    }

    /**
     * Receive a large file in chunks.
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws UploadFailedException
     * @throws UploadMissingFileException
     */
    public static function upload(Request $request): JsonResponse {
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

        if($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }

        $save = $receiver->receive();

        if($save->isFinished()) {
            Log::debug('Uploaded file');

            return VideoController::moveVideo($save->getFile());
        }

        $handler = $save->handler();

        $percentageDone = $handler->getPercentageDone();

        return response()->json([
            'done' => $percentageDone,
            'status' => true
        ]);
    }

    private static function moveVideo(UploadedFile $file): JsonResponse {
        $filepath = $file->storePublicly('videos/', 'public');

        return response()->json([
            'path' => $filepath
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(VideoStoreRequest $request): Redirector|RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validated();

        $filepath = 'storage/videos/'.basename($validated['filename']);
        // validate file
        if(str_starts_with(mime_content_type($filepath), 'video')) {
            Log::debug('file is valid');
        } else {
            Log::debug('file is invalid');
            return redirect(to: "/");
        }

        $visibility = $this->getVisibility($validated['visibility']);

        $token = Str::random();

        while(!Video::where('url_token', $token)->get()->isEmpty()) {
            $token = Str::random();
            Log::debug("token '$token' is being regenerated");
        }

        Log::debug("generated token is '$token'");

        $video = Video::create([
            'user_id' => $user['id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'visibility' => $visibility,
            'url_token' => $token,
        ]);

        $asset = $this->createVideoAsset($filepath, $video->id);

        Log::debug("asset is '$asset'");
        return redirect("videos/$token");
    }

    /**
     * Process video file into a VideoAsset.
     * @param string $filepath
     *
     * @return VideoAsset
     */
    private function createVideoAsset(string $filepath, int $videoId): VideoAsset {
        Log::debug($filepath);

        $pathInfo = pathinfo($filepath);
        $outputFolder = "storage/videos/".basename($filepath, '.'.$pathInfo["extension"]);
        mkdir($outputFolder);

        $resolutions = [
            [1080, 1920],
            [720, 1280],
            [480, 854],
            [360, 640],
            [240, 426],
        ];

        $format = new Format\Video\X264();
        $ffmpeg = FFMpeg::create();
        $video = $ffmpeg->open($filepath);
        $asset = [
            'video_id' => $videoId
        ];

        foreach($resolutions as $resolution) {
            $resizedVideo = $this->downscaleVideoResolution($resolution[1], $resolution[0], $video);

            $outputPath = "{$outputFolder}/{$resolution[0]}.mp4";
            Log::debug("output path is :  {$outputPath}");

            $resizedVideo->save(format: $format, outputPathfile: $outputPath);

            switch($resolution[0]) {
                case 1080:
                    $asset["video_1080p"] = $outputPath;
                    $asset["thumbnail_full"] = "{$outputFolder}/thumbnail_full.png";
                    $this->saveThumbnail($ffmpeg->open($outputPath), "{$outputFolder}/thumbnail_full.png");
                    break;
                case 720: $asset["video_720p"] = $outputPath; break;
                case 480:
                    $asset["video_480p"] = $outputPath;
                    $asset["thumbnail_small"] = "{$outputFolder}/thumbnail_small.png";
                    $this->saveThumbnail($ffmpeg->open($outputPath), "{$outputFolder}/thumbnail_small.png");
                    break;
                case 360: $asset["video_360p"] = $outputPath; break;
                case 240: $asset["video_240p"] = $outputPath; break;
            }

            $format->setKiloBitrate($format->getKiloBitrate() * 0.7);
        }

        $asset["video_original"] = $filepath;

        $videoAsset = VideoAsset::create($asset);

        return $videoAsset;
    }

    private function downscaleVideoResolution(int $width, int $height, FFMpegVideo $video): FFMpegVideo {
        Log::debug("res: {$width} {$height}");
        $dimension = new FFMpegDimension($width, $height);
        $video->filters()->resize($dimension);
        return $video;
    }

    private function saveThumbnail(FFMpegVideo $video, string $filepath): void {
        $format = $video->getFormat();
        $duration = $format->get('duration');

        $thumbnail = $video->frame(TimeCode::fromSeconds($duration/2));

        $thumbnail->save($filepath);
    }

    private static function getVisibility(string $str): int {
        $visibility = 0;

        switch(strtolower($str)){
            case 'public':
                break;
            case 'unlisted':
                $visibility = 1;
                break;
            case 'private':
            default:
                $visibility = 2;
                break;
        }

        return $visibility;
    }

    /**
     * Display the specified resource.
     */
    public static function view(string $token): Redirector|RedirectResponse|Response
    {
        $video = Video::where('url_token', $token)->get()->first();


        if(!$video) {
            return redirect(RouteServiceProvider::HOME);
        }

        if($video->visibility > 1) {
            return redirect(RouteServiceProvider::HOME);
        }

        $user = Auth::user();

        $userRating = Rating::where([
            ['user_id', '=', $user->id],
            ['video_id', '=', $video->id],
        ])->first();

        $video->views += 1;
        $video->save();

        $video->load('user');
        $video->load('asset');

        $comments = $video->comments;
        $comments->load('user');

        return Inertia::render('Video/Video', [
            'video' => $video,
            'userRating' => $userRating,
            'comments' => $comments
        ]);

    }

    public static function edit(string $token): Redirector|RedirectResponse|Response
    {
        $user = Auth::user();

        $video = Video::where('url_token', $token)->get()->first();

        if(!$video) {
            return redirect('/');
        }

        if($user->id != $video->user->id) {
            Log::debug("Unauthorized video edit");

            return redirect(to: "/");
        }

        return Inertia::render('Video/Edit', [
            'video' => $video,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public static function update(VideoUpdateRequest $request, Video $video): Redirector|RedirectResponse
    {
        $validated = $request->validated();

        Log::debug($validated);

        $video->title = $validated['title'];
        $video->description = $validated['description'];
        $video->visibility = VideoController::getVisibility($validated['visibility']);

        $video->save();

        return redirect('/videos/'.$video->url_token);
    }

        /**
     * Update the specified resource in storage.
     */
    public static function updateThumbnail(Request $request): void
    {
        $request->validate([
            'id' => ['required', 'numeric', 'integer'],
            'thumbnail' => ['required', 'file', new ImageFile]
        ]);

        $thumbnail = $request->thumbnail;

        $video = Video::find($request->id);

        $url = 'storage/'.$thumbnail->storePublicly('thumbnails/', 'public');

        $oldPicture = 'thumbnails/'.basename($video->thumbnail);

        if(Storage::disk('public')->exists($oldPicture) && Storage::disk('public')->delete($oldPicture) === true) {
            Log::debug('old picture deleted');
        }

        $video->thumbnail = $url;
        $video->thumbnail_asset = asset($url);

        $video->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public static function destroy(Video $video): void
    {
        // delete files here

        $video->delete();
    }
}

