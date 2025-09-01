<?php

namespace App\Http\Controllers;

use App\Http\Controllers\pages\Controller;
use App\Models\Notification; // Make sure you import your Notification model
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Mark the given notification as read.
     */
    public function markAsRead(Notification $notification): JsonResponse
    {
        // SECURITY: Ensure the authenticated user owns this notification.
        if (Auth::id() !== $notification->user_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        // Update the timestamp only if it's currently unread.
        if (! $notification->read_at) {
            $notification->read_at = now();
            $notification->save();

            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'not updated']);

    }
}
