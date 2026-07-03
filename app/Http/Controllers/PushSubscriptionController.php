<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PushSubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'endpoint'        => 'required|url',
            'keys.auth'       => 'required',
            'keys.p256dh'     => 'required',
        ]);

        $request->user()->updatePushSubscription(
            $request->endpoint,
            $request->keys['p256dh'],
            $request->keys['auth'],
            $request->get('contentEncoding', 'aesgcm')
        );

        return response()->json(['success' => true]);
    }

    public function destroy(Request $request)
    {
        $request->user()->deletePushSubscription($request->endpoint);
        return response()->json(['success' => true]);
    }
}