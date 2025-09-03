<?php

namespace App\Http\Controllers\pages;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
    /**
     * Handle incoming updates from the Telegram Bot webhook.
     *
     * @throws TelegramSDKException
     */
    public function webhook(Request $request): JsonResponse
    {
        /*     // Set Webhook
                $response = Telegram::setWebhook(['url' => 'https://example.com/<token>/webhook']);

                // Or if you are supplying a self-signed-certificate
                $response = Telegram::setWebhook([
                    'url' => 'https://example.com/<token>/webhook',
                    'certificate' => '/path/to/public_key_certificate.pub',
                ]);*/

        // Log the entire incoming request for debugging purposes.
        Log::info('Telegram Webhook Received:', $request->all());

        $message = $request->input('message');
        $chat_id = $message['chat']['id'] ?? null;
        $text = $message['text'] ?? null;

        // Check if the message is the /start command with a token.
        if ($chat_id && $text && str_starts_with($text, '/start ')) {
            // Extract the token from the message: "/start <token>"
            $token = substr($text, 7);

            // Ensure a token was actually provided
            if (empty($token)) {
                Log::warning('Telegram webhook: /start command received without a token.');

                return response()->json(['status' => 'error', 'message' => 'Token not provided.'], 400);
            }
            Log::info("Telegram webhook: Processing /start command with token: {$token}");

            // Retrieve the user ID from the cache using the token.
            $user_id = Cache::get('telegram_token:'.$token);

            if ($user_id) {
                Log::info("Telegram webhook: Found user_id: {$user_id} in cache for token: {$token}.");
                $user = User::find($user_id);

                if ($user) {
                    if ($user->update(['telegram_chat_id' => $chat_id])) {
                        Cache::forget('telegram_token:'.$token);
                        Log::info("Successfully linked Telegram chat_id: {$chat_id} to user_id: {$user_id}.");

                        // Send confirmation using the new SDK
                        $message_text = "*Telegram Linked*\n\nYour Telegram account has been linked to your ".config('app.name')." Account.\nYou will now receive notifications in this Telegram Account.";
                        Telegram::sendMessage([
                            'chat_id' => $chat_id,
                            'text' => $message_text,
                            'parse_mode' => 'Markdown',
                        ]);
                    }

                } else {
                    Log::error("Telegram webhook: User not found in database for user_id: {$user_id}.");
                }
            } else {
                Log::warning("Telegram webhook: Token not found or expired in cache: {$token}.");
            }
        }

        // Always return a 200 OK response to Telegram to acknowledge receipt.
        return response()->json(['status' => 'ok']);
    }
}
