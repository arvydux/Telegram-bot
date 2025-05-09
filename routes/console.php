<?php

use App\Models\Chat;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    foreach (Chat::all() as $chat) {
        $text = '(This message you will receive every morning.)';
        (new App\Http\Controllers\TelegramBotController)->sendRecurringMessage($chat->chat_id, $text);
        Log::info('Message sent to chat: ' . $chat->chat_id);
    }
})->dailyAt('6:00');

