<?php

namespace App\Http\Controllers;

use App\Models\Playlist_Video;
use App\Models\Playlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PlaylistVideoRequest;

class PlaylistVideoController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(PlaylistVideoRequest $request)
    {
        $validated = $request->validated();

        $playlistVideo = Playlist_Video::create([
            'video_id' => $validated['videoId'],
            'playlist_id' => $validated['playlistId'],
            'position' => Playlist_Video::where('playlist_id', $validated['playlistId'])->count()+1
        ]);

        $playlist = $playlistVideo->playlist()->get()->first();

        Log::debug("playlistVideo is $playlistVideo");
        Log::debug("playlist is $playlist");

        return;
    }

    /**
     * Remove the specified resource from storage.
     */
    public static function destroy(Playlist_Video $playlistVideo)
    {

        // $playlistVideo->delete();

        return;
    }
}
