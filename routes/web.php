<?php

use Illuminate\Support\Facades\Route;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Message\Message;



Route::post('/webhook', function () {
    $bot = new Nutgram($_ENV['TELEGRAM_TOKEN']);

    // Handle the /start command
    $bot->onCommand('start', function (Nutgram $bot) {
        $bot->sendMessage('Welcome to the bot! Use /help to see available commands.');
    });

    // Handle the /help command
    $bot->onCommand('help', function (Nutgram $bot) {
        $bot->sendMessage("Here are the available commands:\n/start - Start the bot\n/help - Show this help message\n/about - Learn more about the bot");
    });

    // Handle the /about command
    $bot->onCommand('about', function (Nutgram $bot) {
        $bot->sendMessage('This is a sample Telegram bot built using Nutgram.');
    });

    // Process the incoming update
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

