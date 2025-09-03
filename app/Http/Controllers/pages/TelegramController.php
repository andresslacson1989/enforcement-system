<?php

namespace App\Http\Controllers\pages;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TelegramController extends Controller
{
    /**
     * Handle the incoming webhook from the Telegram bot to link a user's account.
     */
    public function linkAccount(Request $request)
    {
        $token = $request->input('token');
        $chat_id = $request->input('chat_id');

        if (! $token || ! $chat_id) {
            return response()->json(['status' => 'error', 'message' => 'Missing token or chat_id.'], 400);
        }

        // Retrieve the user ID from the cache using the token
        $user_id = Cache::get('telegram_token:'.$token);

        if (! $user_id) {
            return response()->json(['status' => 'error', 'message' => 'Invalid or expired token.'], 404);
        }

        $user = User::find($user_id);
        if ($user) {
            $user->telegram_chat_id = $chat_id;
            $user->save();

            // Remove the token from the cache now that it's been used
            Cache::forget('telegram_token:'.$token);

            return response()->json(['status' => 'success', 'message' => 'Account linked successfully.']);
        }

        return response()->json(['status' => 'error', 'message' => 'User not found.'], 404);
    }
}
