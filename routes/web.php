<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

    $emotion = 'Love';

    $openApiKey = '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';
    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . env('OPENAI_API_KEY') ?: $openApiKey,
    ])->post('https://api.openai.com/v1/chat/completions', [
        'model' => 'gpt-4o-mini',
        'store' => true,
        'messages' => [
            ['role' => 'user', 'content' => 'show a random quote about ' . $emotion . ' emotion that can help the me feel better. Try show which you haven \'t show before.'],
        ],
    ]);

    $chatResponse = $response->json()['choices'][0]['message']['content'] ?? 'Sorry, I could not understand that.';

})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);


