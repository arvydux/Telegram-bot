<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Message\Message;
use Telegram\Bot\Keyboard\Keyboard;

use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;

Route::post('/webhook', function(Request $request) {
    $updates = Telegram::getWebhookUpdate();

    $response = Telegram::sendMessage([
        'chat_id' => '2091649713',
        'text' => 'test' . json_encode($request->all()) . '-' . json_encode($updates->all())    ]);
    return 'ok';

})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

Route::get('/', function(Request $request) {

    $updates = Telegram::getWebhookUpdate();

    $response = Telegram::sendMessage([
        'chat_id' => '2091649713',
        'text' => json_encode($updates)
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

    $messageId = $response->getMessageId();


    return view('welcome');
})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);


Route::get('/a', function() {

    $emotion = 'Love';

    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer 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',
    ])->post('https://api.openai.com/v1/chat/completions', [
        'model' => 'gpt-4o-mini',
        'store' => true,
        'messages' => [
            ['role' => 'user', 'content' => 'show a random quote about ' . $emotion . ' emotion that can help the me feel better. Try show which you haven \'t show before.'],
        ],
    ]);

    $chatResponse = $response->json()['choices'][0]['message']['content'] ?? 'Sorry, I could not understand that.';

    dd($chatResponse);

})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);



Route::get('/set-webhook', function () {
    $bot = new Nutgram($_ENV['TELEGRAM_TOKEN']);
    $bot->setWebhook('https://telegram-bot-master-pyrd6s.laravel.cloud/webhook');
    return 'ooWebhook set successfully!';
})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

