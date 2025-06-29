<?php

namespace Modules\Marketing\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Jobs\SendPushNotification;

class NotificationController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'message' => 'required|string',
        ]);

        SendPushNotification::dispatch($validated['message'], $validated['user_id']);

        return response()->json(['message' => 'Notification queued successfully.']);
    }
}
