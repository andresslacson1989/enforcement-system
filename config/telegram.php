<?php

/**
 * This configuration file is for the Laravel Notification Channels/Telegram package.
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Telegram Bot Token
    |--------------------------------------------------------------------------
    |
    | This is the token you received from BotFather. It's required to send
    | any notifications via the Telegram API.
    |
    */
    'token' => env('TELEGRAM_BOT_TOKEN'),
    'username' => env('TELEGRAM_BOT_USERNAME'),
];
