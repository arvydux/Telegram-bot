<?php

use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::post('/webhook', [\App\Http\Controllers\TelegramBotController::class, 'handleWebhook'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

Route::get('/', function(Request $request) {

    $keyboard = (new App\Http\Controllers\TelegramBotController)->makeEmotionsKeyboard();

    (new App\Http\Controllers\TelegramBotController)->sendMessageAboutEmotions('2091649713', 'test');

    dd(1);
    Http::post('https://api.telegram.org/bot' . env('TELEGRAM_TOKEN') . '/sendMessage', [
        'chat_id' => '2091649713',
        'text' => 'AAAHi, how are you feeling today? (I ask this question every hour)?',
        'reply_markup' => null,
    ]);



    $emotions = [
        "Happy" => "😊",
        "Sad" => "😢",
        "Angry" => "😠",
        "Surprised" => "😲",
        "Excited" => "🤩",
        "Confused" => "😕",
        "Bored" => "😐",
        "Anxious" => "😰",
        "Grateful" => "🙏",
        "In Love" => "😍",
        "Embarrassed" => "😳",
        "Proud" => "😌",
        "Hopeful" => "🌈",
        "Nervous" => "😬",
        "Curious" => "🤔",
        "Relaxed" => "🧘",
        "Silly" => "😜",
        "Tired" => "😴",
        "Disappointed" => "😞",
        "Lonely" => "😔"
    ];

    $emotionsArray = [];
    foreach ($emotions as $text => $emoji) {
        $emotionsArray[] = [
            'text' => $text . ' ' . $emoji,
            'callback_data' => $text
        ];

    }

    $columns = 2;
    $emotionsArray = array_chunk($emotionsArray, $columns);

    Http::post('https://api.telegram.org/bot' . env('TELEGRAM_TOKEN') . '/sendMessage', [
        'chat_id' => '2091649713',
        'text' => 'Hello World',
        'reply_markup' => [
            'inline_keyboard' =>
                $emotionsArray
        ]
    ]);



    return view('welcome');
})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);


Route::get('/a', function() {


    $chats = Chat::all();
        Log::info('Chats: ' . $chats);
    $logFile = storage_path('logs/laravel.log');
    $logs = file_get_contents($logFile);
    echo nl2br($logs);
    dd($chats);

    Chat::create([
        'chat_id' => 112233,
    ]);



    Chat::where('chat_id', 112233)->exists();

})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);


