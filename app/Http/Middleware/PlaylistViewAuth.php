<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Playlist;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PlaylistViewAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentUserId = Auth::id();

        $user = User::find($currentUserId);

        $token = $request->route('token');
        Log::debug("$token");

        if($user == null) {
            Log::debug('user is null');
            return redirect('/');
        }

        Log::debug("$user");

        $playlist = Playlist::where('url_token', $token)->get()->first();

        if($playlist != null) {
            if($playlist->visibility < 2) {
                Log::debug('playlist is not private');

                return $next($request);
            } else {
                Log::debug('playlist is private');

                if($playlist->user_id == $user->id) {
                    Log::debug('user can view the playlist');

                    return $next($request);
                } else {
                    Log::debug('user can not view the playlist');
                }
            }
        } else {
            Log::debug('playlist does not exist');
        }

        return redirect('/');
    }
}
