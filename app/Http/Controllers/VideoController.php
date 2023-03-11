<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Log::debug(Video::all(['title', 'id', 'visibility']));
        return Inertia::render("Video/Videos", [
            "videos" => Video::where("visibility", 0)
                        ->latest()
                        ->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Log::debug(Video::all());
        return Inertia::render("Video/Create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        Log::debug("$user");
        Log::debug("$request");

        $request->validate([
            'title' => 'required|string|max:255',
            'filename' => 'required|string|max:255',
            'description' => 'string',
            'visibility' => 'required|string'
        ]);

        $visibility = 0;

        switch($request->visibility){
            case "public":
                break;
            case "unlisted":
                $visibility = 1;
                break;
            case "private":
                $visibility = 2;
                break;
        }

        $token = Str::random();

        while(!Video::where('url_token', $token)->get()->isEmpty()) {
            $token = Str::random();
            Log::debug("token '$token' is being regenerated");
        }

        Log::debug("generated token is '$token'");
        
        $video = Video::create([
            'user_id' => $user["id"],
            'title' => $request->title,
            'filename' => $request->filename,
            'description' => $request->description,
            'thumbnail' => $request->title,
            'visibility' => $visibility,
            'url_token' => $token,
        ]);

        return redirect("videos/$token");
    }

    /**
     * Display the specified resource.
     */
    public static function view(string $token)
    {
        $video = Video::where('url_token', $token)->get();

        Log::debug($video);

        if($video->isEmpty()) {
            return redirect(RouteServiceProvider::HOME);
        } else {
            return Inertia::render("Video/Video", [
                "video" => $video[0]
            ]);
        }
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
