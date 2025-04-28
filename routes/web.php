<?php

use Illuminate\Support\Facades\Route;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Message\Message;

Route::post('/webhook', function() {
    $bot = new Nutgram($_ENV['TELEGRAM_TOKEN']);

// called on text "I want 6 portions of cake" (regex)
    $bot->onText('I want ([0-9]+) (pizza|cake)', function (Nutgram $bot, string $amount, string $dish) {
        $bot->sendMessage("You will get {$amount} portions of {$dish}!");
    });

    $bot->run();

    return response('OK');
})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);


Route::get('/webhook', function() {
    $bot = new Nutgram($_ENV['TELEGRAM_TOKEN']);


        $bot->sendMessage(
            text: 'Choose an option:',
            reply_markup: InlineKeyboardMarkup::make()->addRow(
                InlineKeyboardButton::make('One', callback_data: 'number 1'),
                InlineKeyboardButton::make('Two', callback_data: 'number 2'),
                InlineKeyboardButton::make('Cancel', callback_data: 'cancel'),
            )
        );


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


        $bot->sendMessage(
            text: 'Choose an option:',
            chat_id: 2091649713,
            reply_markup: InlineKeyboardMarkup::make()->addRow(
                InlineKeyboardButton::make('One', callback_data: 'number 1'),
                InlineKeyboardButton::make('Two', callback_data: 'number 2'),
                InlineKeyboardButton::make('Cancel', callback_data: 'cancel'),
            )
        );


    $bot->onCallbackQueryData('number {param}', function (Nutgram $bot, $param) {
        $bot->sendMessage(
            text: 'Choose an h7777777option:',
            chat_id: 2091649713);
        $bot->answerCallbackQuery();
    });

    $bot->onCallbackQueryData('cancel', function (Nutgram $bot) {
        $bot->sendMessage(
            text: 'Choose an optihhhhhhhhon:',
            chat_id: 2091649713);
        $bot->answerCallbackQuery();
    });

   // $bot->run();

    return view('welcome');
})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);


Route::get('/set-webhook', function () {
    $bot = new Nutgram($_ENV['TELEGRAM_TOKEN']);
    $bot->setWebhook('https://telegram-bot-master-pyrd6s.laravel.cloud/webhook');
    return 'Webhook set successfully!';
})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

