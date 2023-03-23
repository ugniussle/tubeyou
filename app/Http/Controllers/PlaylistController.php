<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Playlist;
use App\Models\Playlist_Video;
use App\Models\Video;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Http\Requests\PlaylistRequest;

class PlaylistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentUserId = Auth::id();

        $playlists = Playlist::where('user_id', $currentUserId)
                            ->latest()
                            ->get();

        foreach($playlists as $playlist) {
            $playlistLength = Playlist_Video::where('playlist_id', $playlist->id)->count();

            $playlist->length = $playlistLength;

            $playlist->thumbnail = $this->getPlaylistThumbnail($playlist->id);
        }

        return Inertia::render('Playlist/Playlists', [
            'playlists' => $playlists,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Playlist/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        // Log::debug("$user");
        Log::debug("$request");

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'visibility' => ['required', 'string'],
            // 'video_id' => ['nullable', 'number'],
        ]);

        $visibility = $this->getVisibility($request->visibility);

        $token = Str::random();

        while(!Playlist::where('url_token', $token)->get()->isEmpty()) {
            $token = Str::random();
            Log::debug("token '$token' is being regenerated");
        }

        Log::debug("generated token is '$token'");

        $playlist = Playlist::create([
            'user_id' => $user['id'],
            'title' => $request->title,
            'visibility' => $visibility,
            'url_token' => $token,
        ]);



        Log::debug("playlist is $playlist");

        return redirect("playlists/$token");
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
     * Show the form for editing the specified resource.
     */
    public function edit(Playlist $playlist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Playlist $playlist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public static function destroy(PlaylistRequest $request)
    {
        $validated = $request->validated();

        $playlist = Playlist::find($validated['playlistId']);
        Log::debug("destroying playlist $playlist");

        $playlist->delete();
    }

    public static function view(string $token)
    {
        $currentUserId = Auth::id();

        $playlist = Playlist::where('url_token', $token)->get()->first();


        $playlist->length = PlaylistController::getPlaylistLength($playlist->id);
        $playlist->thumbnail = PlaylistController::getPlaylistThumbnail($playlist->id);

        $videos = PlaylistController::getPlaylistVideos($playlist);

        return Inertia::render('Playlist/Playlist', [
            'playlist' => $playlist,
            'videos' => $videos
        ]);
    }
    
    private static function getPlaylistLength($playlistId) {
        return Playlist_Video::where('playlist_id', $playlistId)->count();
    }

    private static function getPlaylistThumbnail($playlistId) {
        $firstVideo = Playlist_Video::where([
            ['playlist_id', '=', $playlistId],
            ['position', '=', 1]
        ])->get()->first();

        if($firstVideo != null) {
            return Video::where('id', $firstVideo->video_id)->get()->first()->thumbnail_asset;
        }

        return null;
    }

    private static function getPlaylistVideos($playlist) {
        $videos = [];

        $playlistVideos = $playlist->playlistVideos()->get();

        Log::debug($playlistVideos);

        foreach($playlistVideos as $playlistVideo) {
            array_push($videos, Video::where('id', $playlistVideo->video_id)->get()->first());
        }

        return $videos;
    }

    public static function getPlaylists() {
        $currentUserId = Auth::id();

        $playlists = Playlist::where('user_id', $currentUserId)->get();

        foreach($playlists as $playlist) {
            $playlist->playlistVideos = Playlist_Video::where('playlist_id', $playlist->id)->get();
        }

        return json_encode($playlists);
    }
}
