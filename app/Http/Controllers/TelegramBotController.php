<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TelegramBotController extends Controller
{
    public function handleWebhook(Request $request): void
    {
        $updates = $request->all();
        $chatId = $this->getChatIdFromUpdate($updates);
        if (!Chat::where('chat_id', $chatId)->exists()) {
            $this->subscribeChat($chatId);
            $this->sendWelcomeMessage($chatId);
        }
        $callbackData = $this->getCallbackDataFromUpdate($updates);
        if ($callbackData) {
            $answer = $this->sendQuestionToOpenAi(json_encode($callbackData));
            $this->sendMessage($chatId, $answer);
            $this->sendMessage($chatId, 'Let\'s try again!');
        }
        $this->sendMessageAboutEmotions($chatId);
    }

    public function sendMessageAboutEmotions(string $chatId, ?string $additionalText = null): void
    {
        $userName = $this->getFirstNameFromChatId($chatId) ?? 'User';
        $text = "Hi, $userName,  please, let me know how you feel today?! " . $additionalText;
        $keyboard = $this->makeEmotionsKeyboard();
        $this->sendMessage($chatId, $text, $keyboard);
    }

    public function sendMessage(string $chatId, string $text, ?array $keyboard = null)
    {
        $payload = [
            'chat_id' => $chatId,
            'text' => $text,
        ];

        if ($keyboard) {
            $payload = array_merge($payload,
                ['reply_markup' => $keyboard]
            );
        }

        Http::post('https://api.telegram.org/bot' . env('TELEGRAM_TOKEN') . '/sendMessage', $payload
        );
    }

    public function setWebhook(Request $request)
    {
        $url = $request->input('url');

        // Set the webhook URL for the Telegram bot
        // ...

        return response()->json(['status' => 'webhook set']);
    }

    protected function subscribeChat($chatId): string
    {
        return Chat::create([
            'chat_id' => $chatId,
        ]);
    }

    public function sendWelcomeMessage($chatId): void
    {
        $userName = $this->getFirstNameFromChatId($chatId) ?? 'User';
        $text = "Hello, $userName, welcome to the Bot!";
        $this->sendMessage($chatId, $text);
        $text = 'You just subscribed to this bot. Now you will receive messages every morning.';
        $this->sendMessage($chatId, $text);
    }

    public function sendRecurringMessage($chatId, $text): void
    {
        $this->sendMessageAboutEmotions($chatId, $text);
    }

    public function sendQuestionToOpenAi($emotion): string
    {
        $openApiKey = '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';
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . (env('OPENAI_API_KEY') ?? $openApiKey),
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4o-mini',
            'store' => true,
            'messages' => [
                ['role' => 'user', 'content' => 'show a random quote about "' . $emotion . '" emotion that can help the me feel better. Try show which you haven \'t show today.'],
            ],
        ]);

        return $response->json()['choices'][0]['message']['content'] ?? 'Sorry, I could not understand that.';
    }

    protected function getChatIdFromUpdate($updates): ?string
    {
        if (isset($updates['message'])) {
            $chatId = $updates['message']['chat']['id'];
        } elseif (isset($updates['callback_query'])) {
            $chatId = $updates['callback_query']['message']['chat']['id'];
        } else {
            $chatId = null;
        }

        return $chatId;
    }

    public function getFirstNameFromChatId($chatId): ?string
    {
        $response = Http::post('https://api.telegram.org/bot' . env('TELEGRAM_TOKEN') . '/getChat', [
            'chat_id' => $chatId,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['result']['first_name'] ?? null;
        }
    }

    protected function getCallbackDataFromUpdate($updates): ?string
    {
        if (isset($updates['callback_query']['data'])) {
            return $updates['callback_query']['data'];
        }

        return null;
    }

    public function makeEmotionsKeyboard(): array
    {
        $columns = 2;

        // 20 emotions and their corresponding emojis
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

        $emotionsArray = array_chunk($emotionsArray, $columns);

        return [
            'inline_keyboard' =>
                $emotionsArray
        ];
    }
}
