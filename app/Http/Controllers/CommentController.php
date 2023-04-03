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

        $comment = Comment::create([
            'user_id' => Auth::user()->id,
            'video_id' => $validated['videoId'],
            'body' => $validated['body'],
            'parent' => $validated['parent']
        ]);

        return true;
    }

    public static function getComments($videoUrlToken) {
        $video = Video::where('url_token', $videoUrlToken)->get()->first();

        $comments = $video->comments()->where('parent', null)->orderBy('created_at', 'desc')->get();

        $comments->load('replies');

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
