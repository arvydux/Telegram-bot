<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TelegramBotController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $updates = $request->all();
        $chatId = $this->getChatIdFromUpdate($updates);
        $callbackData = $this->getCallbackDataFromUpdate($updates);
        if ($callbackData) {
            $answer = $this->sendQuestionToOpenAi(json_encode($callbackData));
            $this->sendMessage($chatId, $answer);
            $this->sendMessage($chatId, 'Let\'s try again!');
        }
        $this->sendMessageAboutEmotions($chatId);

        return 1;

        // Check if the update contains a message
        if (isset($updates['message'])) {
            $chatId = $updates['message']['chat']['id'];
        } elseif (isset($updates['callback_query'])) {
            // If it's a callback query
            $chatId = $updates['callback_query']['message']['chat']['id'];
        } else {
            $chatId = null; // Handle cases where chat_id is not present
        }

        $this->sendMessage($chatId, $chatId);

        return 'ok';
    }

    public function sendWelcomeText(string $chatId, string $userName)
    {
        $text = "Welcome to the bot, $userName!";
        $this->sendMessage($chatId, $text, $keyboard);
    }

    public function sendMessageAboutEmotions(string $chatId, ?string $additionalText = null): void
    {
        $text = "Hi, how are you feeling today?! " . $additionalText;
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

    protected function subscribeChat($chatId): Chat
    {
        $text = 'You have been subscribed to this bot. Welcome to the bot!';

        return $chat = Chat::firstOrCreate(
            ['email' => $chatId],
        );

        return response()->json(['status' => 'chat subscribed']);
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
        // Check if the update contains a message
        if (isset($updates['message'])) {
            $chatId = $updates['message']['chat']['id'];
        } elseif (isset($updates['callback_query'])) {
            // If it's a callback query
            $chatId = $updates['callback_query']['message']['chat']['id'];
        } else {
            $chatId = null; // Handle cases where chat_id is not present
        }

        return $chatId;
    }

    protected function getCallbackDataFromUpdate($updates): ?string
    {
        if (isset($updates['callback_query']['data'])) {
            return $updates['callback_query']['data'];
        }

        return null; // Return null if no callback_data is present
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
