<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\PlaylistController;

class ChannelController extends Controller
{

    public static function index() 
    {
        return Inertia::render('');
    }

    public static function view(int $id) 
    {
        $channel = User::find($id);

        $videos = $channel->videos
            ->where('visibility', 0);
        $videos->load('user');

        $playlists = $channel->playlists
            ->where('visibility', 0);

        foreach($playlists as $playlist) {
            $playlistLength = $playlist->playlistVideos->count();

            $playlist->length = $playlistLength;

            $playlist->thumbnail = PlaylistController::getPlaylistThumbnail($playlist->id);
        }

        return Inertia::render('Channel/Channel', [
            'videos' => $videos,
            'playlists' => $playlists,
            'channel' => User::find($id),
        ]);
    }
}
