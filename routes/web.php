<?php

use Illuminate\Support\Facades\Route;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Message\Message;


Route::get('/', function () {
    $bot = new Nutgram($_ENV['TELEGRAM_TOKEN']);


        $bot->sendMessage(
            text: 'Welcome!',
            chat_id: 2091649713,
            reply_markup: InlineKeyboardMarkup::make()
                ->addRow(
                    InlineKeyboardButton::make('A', callback_data: 'type:a'),
                    InlineKeyboardButton::make('B', callback_data: 'type:b')
                ),
            //chat_id: $_ENV['TELEGRAM_CHAT_ID']
        );


    $bot->onCommand('type:a', function(Nutgram $bot){
        $bot->sendMessage(
            text: 'You selected A'
        );
    });

    $bot->onCommand('type:b', function(Nutgram $bot){
        $bot->sendMessage(
            text: 'You selected B'
        );
    });

    return view('welcome');
})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

Route::post('/webhook', function() {
    $bot = new Nutgram($_ENV['TELEGRAM_TOKEN']);

    $bot->onCommand('start', function (Nutgram $bot) {
        $bot->sendMessage(
            text: 'Choose an option:',
            reply_markup: InlineKeyboardMarkup::make()->addRow(
                InlineKeyboardButton::make('One', callback_data: 'number 1'),
                InlineKeyboardButton::make('Two', callback_data: 'number 2'),
                InlineKeyboardButton::make('Cancel', callback_data: 'cancel'),
            )
        );
    });

    $bot->onCallbackQueryData('number {param}', function (Nutgram $bot, $param) {
        $bot->sendMessage($param); // 1 or 2
        $bot->answerCallbackQuery();
    });

    $bot->onCallbackQueryData('cancel', function (Nutgram $bot) {
        $bot->sendMessage('Canceled!');
        $bot->answerCallbackQuery();
    });

    $bot->run();

    return view('welcome');
})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

Route::get('/', function() {
    $bot = new Nutgram($_ENV['TELEGRAM_TOKEN']);

    $bot->onCommand('start', function (Nutgram $bot) {
        $bot->sendMessage(
            text: 'Choose an option:',
            reply_markup: InlineKeyboardMarkup::make()->addRow(
                InlineKeyboardButton::make('One', callback_data: 'number 1'),
                InlineKeyboardButton::make('Two', callback_data: 'number 2'),
                InlineKeyboardButton::make('Cancel', callback_data: 'cancel'),
            )
        );
    });

    $bot->onCallbackQueryData('number {param}', function (Nutgram $bot, $param) {
        $bot->sendMessage($param); // 1 or 2
        $bot->answerCallbackQuery();
    });

    $bot->onCallbackQueryData('cancel', function (Nutgram $bot) {
        $bot->sendMessage('Canceled!');
        $bot->answerCallbackQuery();
    });

    $bot->run();

    return view('welcome');
})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);


Route::get('/set-webhook', function () {
    $bot = new Nutgram($_ENV['TELEGRAM_TOKEN']);
    $bot->setWebhook('https://telegram-bot-master-pyrd6s.laravel.cloud/webhook');
    return 'Webhook set successfully!';
})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

