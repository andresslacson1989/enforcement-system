<?php

namespace App\Helpers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationHelper
{
    public static function getLatestNotifications()
    {
        if (Auth::check()) {
            return Notification::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        }

        return collect();
    }

    public static function getUnreadNotificationCount()
    {
        if (Auth::check()) {
            return Notification::where('user_id', Auth::id())
                ->whereNull('read_at')
                ->count();
        }

        return 0;
    }
}
