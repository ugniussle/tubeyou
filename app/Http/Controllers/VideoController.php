<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\User;
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
use Pion\Laravel\ChunkUpload\Exceptions\UploadFailedException;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

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

        foreach($videos as $video) {
            $video['username'] = $video->user->username;
            $video['profilePicture'] = $video->user->profile_picture;
        }

        // Log::debug(Video::all(['title', 'id', 'visibility', 'thumbnail_asset']));
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

        // Log::debug("Uploading file, progress: $percentageDone");
//
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
    public function store(Request $request)
    {
        $user = $request->user();

        // Log::debug("$user");
        // Log::debug("$request");

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['string', 'nullable'],
            'visibility' => ['required', 'string'],
            'filename' => ['required', 'string', 'unique:App\Models\Video,filename']
        ]);

        $filepath = 'storage/videos/'.basename($request->filename);

        // validate file
        if(str_starts_with(mime_content_type($filepath), 'video')) {
            Log::debug('file is valid');
        } else {
            Log::debug('file is invalid');
            return;
        }

        $proccessedFileInfo = $this->processVideo(public_path($filepath));
        
        Log::debug($proccessedFileInfo);

        $visibility = $this->getVisibility($request->visibility);

        $token = Str::random();

        while(!Video::where('url_token', $token)->get()->isEmpty()) {
            $token = Str::random();
            Log::debug("token '$token' is being regenerated");
        }

        Log::debug("generated token is '$token'");
        
        $video = Video::create([
            'user_id' => $user['id'],
            'title' => $request->title,
            'description' => $request->description,
            'visibility' => $visibility,
            'url_token' => $token,
            'filename' => $filepath,
            'thumbnail' => $proccessedFileInfo['thumbnailPath'],
            'video_asset' => asset($filepath),
            'thumbnail_asset' => asset($proccessedFileInfo['thumbnailPath']),
        ]);

        // Log::debug("video is $video");

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
        $videos = Video::where('url_token', $token)->get();

        if($videos->isEmpty()) {
            return redirect(RouteServiceProvider::HOME);
        } else {
            $video = $videos[0];

            $uploader = VideoController::getVideoUploader($video->user_id);
            
            /* Log::debug("Video is: $video");
            Log::debug("Channel is: $uploader"); */

            return Inertia::render('Video/Video', [
                'video' => $video,
                'channel' => $uploader
            ]);
        }
    }

    public static function getVideoUploader($videoId) {
        $video = Video::where('id', $videoId)->get()->first();
        return User::where('id', $video->user_id)->get()->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Video $video)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Video $video)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Video $video)
    {
        //
    }
}
