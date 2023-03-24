<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public static function store(Request $request)
    {
        $request->validate([
            'videoId' => ['required', 'integer', 'numeric'],
            'type' => ['required', 'boolean']
        ]);

        $video = Video::find($request->videoId);

        $currentRating = $video->ratings
                        ->where('user_id', $request->user()->id)
                        ->first();

        if($currentRating) {
            if($currentRating->type == true) {
                $video->likes = $video->likes - 1;
            } else {
                $video->dislikes = $video->dislikes - 1;
            }

            $video->save();

            Rating::where([
                ['user_id', '=', $request->user()->id],
                ['video_id', '=', $video->id],
            ])->delete();

            $video->refresh();

            if($request->type == $currentRating->type) {
                return response()->json([
                    'currentRating' => $video->ratings->where('user_id', $request->user()->id)->first(),
                    'oldRating' => $currentRating
                ]);
            }
        }

        Rating::create([
            'user_id' => $request->user()->id,
            'video_id' => $request->videoId,
            'type' => $request->type
        ]);

        if($request->type) {
            $video->likes = $video->likes + 1;
        } else {
            $video->dislikes = $video->dislikes + 1;
        }

        $video->save();
        $video->refresh();

        return response()->json([
            'currentRating' => $video->ratings->where('user_id', $request->user()->id)->first(),
            'oldRating' => $currentRating
        ]);
    }
}
