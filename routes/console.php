<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use SergiX44\Nutgram\Nutgram;

Schedule::call(function () {
    $bot = new Nutgram($_ENV['TELEGRAM_TOKEN']);


    // Handle the /start comman
    $bot->sendMessage('Welcome to the bot! Use /help to see available commands.',
        chat_id: 2091649713);
})->everySecond();
