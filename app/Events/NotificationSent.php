<?php

namespace App\Events;

use App\Models\Notification;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel; // Import the PrivateChannel class
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class NotificationSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $title;

    public string $message;

    public string $link;

    public array $users_to_notify;

    public string $form_type;

    public int $form_id;

    public string $formattedDate;

    public function __construct(string $title, string $message, string $link, array $users_to_notify, string $form_type, int $form_id, string $date)
    {

        // Save notification to database

        foreach ($users_to_notify as $user) {
            Notification::create([
                'title' => $title,
                'body' => $message,
                'user_id' => $user,
                'link' => $link,
            ]);
        }

        $this->title = $title;
        $this->message = $message;
        $this->link = $link;
        $this->users_to_notify = $users_to_notify;
        $this->form_type = $form_type;
        $this->form_id = $form_id;
        $this->formattedDate = Carbon::createFromFormat('Y-m-d, H:i:s', $date)->diffForHumans();
    }

    /**
     * Get the channels the event should broadcast on.
     * We're now using a PrivateChannel unique to the user ID.
     */
    public function broadcastOn(): array
    {
        return array_map(fn ($userId) => new PrivateChannel("users.{$userId}"), $this->users_to_notify);
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {

        return [
            'title' => $this->title,
            'message' => $this->message,
            'link' => $this->link,
            'form_type' => $this->form_type,
            'form_id' => $this->form_id,
            'formatted_date' => $this->formattedDate,
        ];
    }
}
