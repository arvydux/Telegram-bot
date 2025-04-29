<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {

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
        'text' => 'Hi, how are you feeling today? (I ask this question every hour)?',
        'reply_markup' => [
            'inline_keyboard' =>
                $emotionsArray
        ]
    ]);


})->everyFiveMinutes();
