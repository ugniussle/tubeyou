<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public static function subscribe(int $channelId)
    {
        $currentUser = Auth::user();

        $isAlreadySubscribed = Subscription::where([
            ['user_id', $currentUser->id],
            ['channel_id', $channelId]
        ])->count();

        if($isAlreadySubscribed === 1) {
            Subscription::where([
                ['user_id', $currentUser->id],
                ['channel_id', $channelId]
            ])->delete();

            return response()->json([
                'action' => 'deleted'
            ]);
        }

        $subscription = Subscription::create([
            'user_id' => $currentUser->id,
            'channel_id' => $channelId,
        ]);
        
        return response()->json([
            'action' => 'created'
        ]);
    }

    public static function isSubscribed(int $channelId) {
        $currentUser = Auth::user();
        
        $isAlreadySubscribed = Subscription::where([
            ['user_id', $currentUser->id],
            ['channel_id', $channelId]
        ])->count();

        if($isAlreadySubscribed === 1) {
            return response()->json([
                'status' => 'subscribed'
            ]);
        }

        return response()->json([
            'status' => 'notSubscribed'
        ]);
    }

    public static function getSubscriptions() {
        $currentUser = Auth::user();

        $subscriptions = Subscription::where([
            ['user_id', $currentUser->id],
        ])->get();

        $subscriptions->load('channel');
        
        return $subscriptions;
    }
}
