<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use App\Models\Video;

class ChannelController extends Controller
{
    public static function index() {
        return Inertia::render('');
    }

    public static function view(int $id) {
        $videos = Video::where([['visibility', 0], ['user_id', $id]])
                ->latest()
                ->take(12)
                ->get();

        $videos->load('user');

        return Inertia::render('Channel/Channel', [
            'videos' => $videos,
            'channel' => User::find($id)
        ]);
    }
}
