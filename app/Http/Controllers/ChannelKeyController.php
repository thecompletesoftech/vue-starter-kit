<?php

namespace App\Http\Controllers;

use App\Models\ChannelKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ChannelKeyController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'channelName' => 'required|string|max:255',
            'apiKey' => 'required|string',
            'apiSecret' => 'required|string',
        ]);

        ChannelKey::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'channel_name' => $request->channelName,
            ],
            [
                'api_key' => $request->apiKey,
                'api_secret' => $request->apiSecret,
            ]
        );

        return redirect()->back()->with('success', 'Channel settings saved.');
    }
}
