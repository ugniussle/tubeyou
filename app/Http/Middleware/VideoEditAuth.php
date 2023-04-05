<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Video;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class VideoEditAuth
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
            Log::error('user is null');
            return redirect('/');
        }

        $videoId = $request->id;

        if($videoId != null) {
            $result = Video::find($videoId);

            if($result !== null)
            {
                Log::debug("passed video authorization");
                return $next($request);
            } else {
                Log::debug('video does not belong to user');
            }
        } else {
            Log::debug('video id is null');
        }

        return redirect('/');
    }
}
