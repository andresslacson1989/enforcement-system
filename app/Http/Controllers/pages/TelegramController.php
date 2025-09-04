<?php

namespace App\Http\Controllers\pages;

use App\Jobs\SendAndBroadcastNotification;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                        try {
                            // Send confirmation both to telegram and system notification
                            $title = 'Telegram Linked';
                            $body = 'Your Telegram account has been linked to your '.config('app.name')." Account.\nYou will now receive notifications in this Telegram Account.";
                            $link = route('my-profile', 'my-profile').'#notifications';
                            SendAndBroadcastNotification::dispatch($title, $body, $link, [$user->id]);

                        } catch (TelegramSDKException $e) {
                            Log::error('Telegram confirmation message failed to send.', ['error' => $e->getMessage()]);
                        }
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

    /**
     * Handle the callback from the Telegram Login Widget.
     */
    public function loginCallback(Request $request): RedirectResponse
    {
        try {
            $telegram_data = $request->all();

            // First, validate the hash to ensure the data is from Telegram
            if (! $this->isValidTelegramHash($telegram_data)) {
                Log::error('Telegram Login: Invalid hash received.', $telegram_data);

                return redirect()->route('login')->withErrors(['email' => 'Telegram authentication failed. Please try again.']);
            }

            $telegram_id = $telegram_data['id'];

            // Scenario 1: An already authenticated user is linking their account.
            if (Auth::check()) {
                // Check if this Telegram account is already linked to another user.
                $existing_user = User::where('telegram_chat_id', $telegram_id)->first();
                if ($existing_user && $existing_user->id !== Auth::id()) {
                    Log::warning('User '.Auth::user()->name.' (ID: '.Auth::id().") attempted to link a Telegram account (ID: {$telegram_id}) that is already linked to user ID: {$existing_user->id}.");

                    return redirect()->route('my-profile')
                        ->withErrors(['telegram_link' => 'This Telegram account is already linked to another user in the system.']);
                }

                $user = Auth::user();
                $user->update(['telegram_chat_id' => $telegram_id]);
                Log::info("User {$user->name} (ID: {$user->id}) successfully linked their Telegram account (ID: {$telegram_id}).");

                return redirect()->route('my-profile')->with('status', 'Telegram account linked successfully!');
            }

            // Scenario 2: A logged-out user is logging in with an existing linked account.
            $user = User::where('telegram_chat_id', $telegram_id)->first();

            if ($user) {
                // Log the user in
                Log::info("User {$user->name} (ID: {$user->id}) found with Telegram ID. Attempting login.");
                Auth::login($user);

                return redirect()->intended(route('form-library')); // Redirect to the intended page or a default
            }

            // User not found
            Log::warning('Telegram Login: User not found with Telegram ID: '.$telegram_id);

            return redirect()->route('login')->withErrors(['email' => 'No account is linked to this Telegram user.']);
        } catch (\Exception $e) {
            Log::error('Telegram Login Callback Error: '.$e->getMessage());

            return redirect()->route('login')->withErrors(['email' => 'An unexpected error occurred during Telegram login.']);
        }
    }

    private function isValidTelegramHash(array $data): bool
    {
        $check_hash = $data['hash'];
        $bot_token = config('telegram.token');

        if (empty($check_hash) || empty($bot_token)) {
            return false;
        }

        unset($data['hash']);
        $data_check_arr = [];
        foreach ($data as $key => $value) {
            $data_check_arr[] = $key.'='.$value;
        }
        sort($data_check_arr);
        $data_check_string = implode("\n", $data_check_arr);
        $secret_key = hash('sha256', $bot_token, true);
        $hash = hash_hmac('sha256', $data_check_string, $secret_key);

        return $hash === $check_hash;
    }
}
