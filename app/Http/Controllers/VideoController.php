<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Video;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;
use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Media\Video as FFMpegVideo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
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
    public function index()
    {
        $videos = Video::where('visibility', 0)
                    ->latest()
                    ->take(12)
                    ->get();

        $videos->load('user');

        return Inertia::render('Video/Videos', [
            'videos' => $videos
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
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
    public static function upload(Request $request) {
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

    private static function moveVideo(UploadedFile $file) {
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
    public function store(VideoStoreRequest $request)
    {
        $user = $request->user();

        $validated = $request->validated();

        $filepath = 'storage/videos/'.basename($validated['filename']);
        // validate file
        if(str_starts_with(mime_content_type($filepath), 'video')) {
            Log::debug('file is valid');
        } else {
            Log::debug('file is invalid');
            return;
        }

        $visibility = $this->getVisibility($validated['visibility']);

        $token = Str::random();

        while(!Video::where('url_token', $token)->get()->isEmpty()) {
            $token = Str::random();
            Log::debug("token '$token' is being regenerated");
        }

        Log::debug("generated token is '$token'");

        $proccessedFileInfo = $this->processVideo(public_path($filepath));
        
        $video = Video::create([
            'user_id' => $user['id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'visibility' => $visibility,
            'url_token' => $token,
            'filename' => $filepath,
            'thumbnail' => $proccessedFileInfo['thumbnailPath'],
            'video_asset' => asset($filepath),
            'thumbnail_asset' => asset($proccessedFileInfo['thumbnailPath']),
        ]);

        return redirect("videos/$token");
    }

    /**
     * Store a newly created resource in storage.
     * @param string $filepath
     * 
     * @return array
     */
    private function processVideo(string $filepath) {
        $info = array();
        $info['filepath'] = $filepath;

        $info['filename'] = basename($filepath);

        $thumbnailStorage = public_path("storage/thumbnails");

        if(!is_dir($thumbnailStorage)) {
            mkdir($thumbnailStorage);
        }

        $thumbnailFilename = explode('.', $info['filename'])[0];

        $ffmpeg = FFMpeg::create();
        $video = $ffmpeg->open($info['filepath']);

        $thumbnailPath = "$thumbnailStorage/$thumbnailFilename.jpg";
        $this->saveThumbnail($video, $thumbnailPath); 
        $info['thumbnailPath'] = 'storage/thumbnails/'.$thumbnailFilename.'.jpg';

        return $info;
    }

    private function saveThumbnail(FFMpegVideo $video, string $filepath) {
        $format = $video->getFormat();
        $duration = $format->get('duration');

        $thumbnail = $video->frame(TimeCode::fromSeconds($duration/2));

        $thumbnail->save($filepath);
    }

    private static function getVisibility(string $str) {
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
    public static function view(string $token)
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

        $comments = $video->comments;
        $comments->load('user');

        return Inertia::render('Video/Video', [
            'video' => $video,
            'userRating' => $userRating,
            'comments' => $comments
        ]);
        
    }

    public static function edit(string $token)
    {
        $user = Auth::user();

        $video = Video::where('url_token', $token)->get()->first();

        if(!$video) {
            return redirect('/');
        }

        if($user->id != $video->user->id) {
            Log::debug("Unauthorized video edit");

            return;
        }

        return Inertia::render('Video/Edit', [
            'video' => $video,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public static function update(VideoUpdateRequest $request, Video $video)
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
    public static function updateThumbnail(Request $request)
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
    public static function destroy(Video $video)
    {
        // delete files here

        $video->delete();
    }
}
