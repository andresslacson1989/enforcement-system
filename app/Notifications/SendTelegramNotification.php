<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class SendTelegramNotification extends Notification
{
    use Queueable;

    protected string $title;

    protected string $message;

    protected string $link;

    public function __construct(string $title, string $message, string $link)
    {
        $this->title = $title;
        $this->message = $message;
        $this->link = $link;
    }

    public function via(object $notifiable): array
    {
        return ['telegram']; // We'll define this custom channel
    }

    public function toTelegram(object $notifiable): void
    {
        $botToken = env('TELEGRAM_BOT_TOKEN');

        // THE CHANGE: Get the chat ID from the user being notified.
        $chatId = $notifiable->telegram_chat_id;

        // IMPORTANT: Only send if the user has provided a chat ID.
        if (! $chatId) {
            return;
        }

        $formattedMessage = "*{$this->title}*\n\n"
          ."{$this->message}\n\n"
          ."[Click here to view]({$this->link})";

        Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $formattedMessage,
            'parse_mode' => 'Markdown',
        ]);
    }
}
