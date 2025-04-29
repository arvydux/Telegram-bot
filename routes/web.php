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

Route::get('/data', function() {


    $data = \Illuminate\Support\Facades\Cache::get('data');
    $updates = \Illuminate\Support\Facades\Cache::get('update');

    dd($data, $updates);
})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);


Route::get('/', function() {
   // $response = Telegram::bot('Arvi_bot')->getMe();

    $response = Telegram::sendMessage([
        'chat_id' => '2091649713',
        'text' => 'Hello Worldjkjjkmokmokmokmokmojkm'
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

    $testArray = [];
    foreach ($emotions as $key => $value) {
        $testArray[] = Keyboard::button("$key => $value");

    }
    $reply_markup = Keyboard::make()
        ->setResizeKeyboard(true)
        ->setOneTimeKeyboard(true)
        ->row($testArray);

    $response = Telegram::sendMessage([
        'chat_id' => '2091649713',
        'text' => 'Hello World',
        'reply_markup' => $reply_markup
    ]);

    $messageId = $response->getMessageId();


    return view('welcome');
})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);


Route::get('/a', function() {

/*   $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?
        key=AIzaSyD-gUrvRnqCqHX3s9ymZoUg__YQI4x7E9Y', [
            'contents' => [
                [
                    'parts' => [
                        ['text' => 'Explain how AI works']
                    ]
                ]
            ]
        ]);*/

    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer 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',
    ])->post('https://api.openai.com/v1/chat/completions', [
        'model' => 'gpt-4o-mini',
        'store' => true,
        'messages' => [
            ['role' => 'user', 'content' => 'give php array with 20 elements of emotions with according telegram emojis'],
        ],
    ]);

    $chatResponse = $response->json()['choices'][0]['message']['content'] ?? 'Sorry, I could not understand that.';


    dd($chatResponse, $response->json());
    return $response->json();

    dd($response->json()['choices'],  $response->json()['choices'][0]['message']['content']);

        if ($response->successful()) {
            return $response->json();
        } else {
            return $response->body();
        }


})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);



Route::get('/set-webhook', function () {
    $bot = new Nutgram($_ENV['TELEGRAM_TOKEN']);
    $bot->setWebhook('https://telegram-bot-master-pyrd6s.laravel.cloud/webhook');
    return 'ooWebhook set successfully!';
})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

