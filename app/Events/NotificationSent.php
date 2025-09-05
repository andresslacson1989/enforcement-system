<?php

namespace App\Events;

use App\Models\Notification;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $notifications;

    // The constructor now accepts a single, complete Notification object
    public function __construct($notification_ids)
    {
        // Use an if/else block to handle the two possible types correctly.
        if (is_int($notification_ids)) {
            // If a single ID is passed, find it and wrap it in an array.
            $notification = Notification::find($notification_ids);
            $this->notifications = $notification ? [$notification] : [];
        } else {
            $this->notifications = Notification::whereIn('id', $notification_ids)->with('user')->get()->all();
        }
    }

    // Broadcast only to the user who owns this specific notification
    public function broadcastOn(): array
    {
        // We need to broadcast on a channel for each unique user.
        $channels = [];
        foreach ($this->notifications as $notification) {
            // Add a check to ensure user_id exists before creating a channel.
            if ($notification->user_id) {
                $channels[] = new PrivateChannel('users.'.$notification->user_id);
            }
        }

        return array_unique($channels);
    }

    // The payload now has access to the notification's ID
    public function broadcastWith(): array
    {
        // We map the notifications to the desired broadcast format.
        return collect($this->notifications)->map(function ($notification) {
            return [
                'notification_id' => $notification->id,
                'title' => $notification->title,
                'message' => $notification->body,
                'link' => $notification->link,
                'formatted_date' => $notification->created_at->diffForHumans(),
            ];
        })->toArray();
    }
}
