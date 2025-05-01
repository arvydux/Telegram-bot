<?php

use App\Models\Chat;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    Log::info('Test 1: ');
    $chats = Chat::all();
    Log::info('Chats: ' . $chats);
    foreach ($chats as $chat) {
        Log::info('Test 2: ');
        $text = '(This message you will receive every morning.)';
        (new App\Http\Controllers\TelegramBotController)->sendRecurringMessage($chat->chat_id, $text);
        Log::info('Message sent to chat: ' . $chat->chat_id);
    }
    Log::info('Test 3: ');

})->everyFiveMinutes();
