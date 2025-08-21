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

    public Notification $notification;

    // The constructor now accepts a single, complete Notification object
    public function __construct(int $notification_id)
    {
        $this->notification = Notification::find($notification_id);
    }

    // Broadcast only to the user who owns this specific notification
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('users.'.$this->notification->user_id);
    }

    // The payload now has access to the notification's ID
    public function broadcastWith(): array
    {
        return [
            'notification_id' => $this->notification->id, // <-- THE ID YOU NEED
            'title' => $this->notification->title,
            'message' => $this->notification->body,
            'link' => $this->notification->link,
            'formatted_date' => $this->notification->created_at->diffForHumans(),
        ];
    }
}
