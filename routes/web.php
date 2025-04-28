<?php

use Illuminate\Support\Facades\Route;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Message\Message;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;

Route::post('/webhook', function() {
    $updates = Telegram::getWebhookUpdate();

    return 'ok';

})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

Route::get('/', function() {

   // $response = Telegram::bot('Arvi_bot')->getMe();

    $response = Telegram::sendMessage([
        'chat_id' => '2091649713',
        'text' => 'Hello Worldjkjjkmokmokmokmokmojkm'
    ]);

    $reply_markup = Keyboard::make()
        ->setResizeKeyboard(true)
        ->setOneTimeKeyboard(true)
        ->row([
            Keyboard::button('1'),
            Keyboard::button('2'),
            Keyboard::button('3'),
        ])
        ->row([
            Keyboard::button('4'),
            Keyboard::button('5'),
            Keyboard::button('6'),
        ])
        ->row([
            Keyboard::button('7'),
            Keyboard::button('8'),
            Keyboard::button('9'),
        ])
        ->row([
            Keyboard::button('0'),
        ]);

    $response = Telegram::sendMessage([
        'chat_id' => '2091649713',
        'text' => 'Hello World',
        'reply_markup' => $reply_markup
    ]);

    $messageId = $response->getMessageId();


    return view('welcome');
})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);


Route::get('/set-webhook', function () {
    $bot = new Nutgram($_ENV['TELEGRAM_TOKEN']);
    $bot->setWebhook('https://telegram-bot-master-pyrd6s.laravel.cloud/webhook');
    return 'Webhook set successfully!';
})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

