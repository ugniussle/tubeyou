<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Playlist;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PlaylistEditAuth
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

        if($user == null) {
            Log::debug('user is null');
            return redirect('/');
        }

        Log::debug("$user");

        $playlistId = $request->playlistId;

        if($playlistId != null) {
            $result = Playlist::where([
                ['id', '=', $playlistId],
                ['user_id', '=', $user['id']]
            ])->get()->count();

            Log::debug("$result");

            if($result === 1)
            {
                Log::debug("passed playlist video authorization");
                return $next($request);
            } else {
                Log::debug('playlist does not belong to user');
            }
        } else {
            Log::debug('playlist id is null');
        }

        return redirect('/'); 
    }
}
