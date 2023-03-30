<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\CommentRequest;
use App\Models\Video;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public static function store(CommentRequest $request)
    {
        $validated = $request->validated();

        $videoId = $validated['videoId'];
        $body = $validated['body'];

        $comment = Comment::create([
            'user_id' => Auth::user()->id,
            'video_id' => $videoId,
            'body' => $body,
        ]);

        Log::debug($comment);

        return true;
    }

    public static function getComments($videoUrlToken) {
        $video = Video::where('url_token', $videoUrlToken)->get()->first();

        $comments = $video->comments()->orderBy('created_at', 'desc')->get();

        $comments->load('user');

        /* foreach($comments as $comment) {
            Log::debug($comment);
        } */

        return $comments;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
    }
}
