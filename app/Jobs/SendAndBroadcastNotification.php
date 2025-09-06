<?php

namespace App\Jobs;

use App\Events\NotificationSent;
use App\Models\Notification as DatabaseNotification;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;

class SendAndBroadcastNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $title;

    protected string $body;

    protected string $link;

    protected array $user_ids;

    public function __construct(string $title, string $body, string $link, array $user_ids)
    {
        $this->title = $title;
        $this->body = $body;
        $this->link = $link;
        $this->user_ids = $user_ids;
    }

    public function handle(): void
    {
        $users = User::whereIn('id', $this->user_ids)->get();
        // We'll collect the IDs of the created notifications to broadcast them all at once.
        $created_notification_ids = [];

        foreach ($users as $user) {
            // 1. Send Telegram Notification if the user is linked
            if ($user->telegram_chat_id) {
                try {
                    $telegram_message = "*{$this->title}*\n\n{$this->body}";

                    $params = [
                        'chat_id' => $user->telegram_chat_id,
                        'text' => $telegram_message,
                        'parse_mode' => 'Markdown',
                    ];

                    // If a link is provided, add an inline button to the message.
                    if ($this->link) {
                        $params['reply_markup'] = json_encode([
                            'inline_keyboard' => [[['text' => 'View Details', 'url' => url($this->link)]]],
                        ]);
                    }

                    Telegram::sendMessage($params);
                } catch (\Exception $e) {
                    Log::error('Failed to send Telegram message in job: '.$e->getMessage(), ['user_id' => $user->id]);
                }
            }

            // 2. Create the in-app database notification
            $notification = DatabaseNotification::create([
                'title' => $this->title,
                'body' => $this->body,
                'user_id' => $user->id,
                'link' => $this->link,
            ]);
            // Add the new notification's ID to our collection.
            $created_notification_ids[] = $notification->id;
        }

        // 3. Broadcast a single event with all the new notification IDs.
        // This is much more efficient than dispatching an event inside the loop.
        if (! empty($created_notification_ids)) {
            NotificationSent::dispatch($created_notification_ids);
        }
    }
}
