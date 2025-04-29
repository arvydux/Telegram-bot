<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use SergiX44\Nutgram\Nutgram;
use Telegram\Bot\Laravel\Facades\Telegram;

Schedule::call(function () {
    Telegram::sendMessage([
        'chat_id' => '2091649713',
        'text' => 'Hello Worldjuuuu4444444okmokmokmojkm'
    ]);
})->everyFiveSeconds();
