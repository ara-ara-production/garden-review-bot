<?php

namespace App\Bot\Drivers;

use App\Bot\Contracts\BotDriverContract;
use App\Bot\Data\BotButton;
use App\Bot\Data\BotDefinition;
use App\Bot\Data\IncomingBotUpdate;
use App\Bot\Data\OutgoingBotMessage;
use App\Bot\Data\SentBotMessage;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TelegramBotDriver implements BotDriverContract
{
    public function parseWebhook(BotDefinition $bot, Request $request): ?IncomingBotUpdate
    {
        $payload = $request->all();

        if (isset($payload['callback_query'])) {
            $callback = $payload['callback_query'];
            $message = $callback['message'] ?? [];

            return new IncomingBotUpdate(
                bot: $bot->name,
                driver: $bot->driver,
                recipientId: (string) data_get($message, 'chat.id'),
                senderId: isset($callback['from']['id']) ? (string) $callback['from']['id'] : null,
                senderUsername: data_get($callback, 'from.username'),
                callbackPayload: $callback['data'] ?? null,
                callbackId: $callback['id'] ?? null,
                messageId: isset($message['message_id']) ? (string) $message['message_id'] : null,
                meta: $payload,
            );
        }

        $message = $payload['message'] ?? null;

        if (! is_array($message)) {
            return null;
        }

        return new IncomingBotUpdate(
            bot: $bot->name,
            driver: $bot->driver,
            recipientId: (string) data_get($message, 'chat.id'),
            senderId: isset($message['from']['id']) ? (string) $message['from']['id'] : null,
            senderUsername: data_get($message, 'from.username'),
            text: $message['text'] ?? null,
            messageId: isset($message['message_id']) ? (string) $message['message_id'] : null,
            replyToMessageId: isset($message['reply_to_message']['message_id']) ? (string) $message['reply_to_message']['message_id'] : null,
            inviteToken: $this->extractInviteToken($message['text'] ?? null),
            meta: $payload,
        );
    }

    public function sendMessage(BotDefinition $bot, OutgoingBotMessage $message): ?SentBotMessage
    {
        $payload = [
            'chat_id' => $message->recipientId,
            'text' => $message->text,
            'parse_mode' => 'HTML',
            'disable_web_page_preview' => true,
        ];

        if ($message->replyToMessageId !== null) {
            $payload['reply_to_message_id'] = $message->replyToMessageId;
        }

        if ($message->buttons !== []) {
            $payload['reply_markup'] = [
                'inline_keyboard' => [
                    array_map(fn (BotButton $button): array => [
                        'text' => $button->label,
                        'callback_data' => $button->payload,
                    ], $message->buttons),
                ],
            ];
        }

        $response = $this->client($bot)
            ->post('sendMessage', $payload)
            ->throw()
            ->json('result');

        if (! isset($response['message_id'])) {
            return null;
        }

        return new SentBotMessage(
            recipientId: $message->recipientId,
            messageId: (string) $response['message_id'],
        );
    }

    public function editMessage(BotDefinition $bot, OutgoingBotMessage $message): void
    {
        if ($message->messageId === null) {
            return;
        }

        $payload = [
            'chat_id' => $message->recipientId,
            'message_id' => $message->messageId,
            'text' => $message->text,
            'parse_mode' => 'HTML',
            'disable_web_page_preview' => true,
        ];

        if ($message->buttons !== []) {
            $payload['reply_markup'] = [
                'inline_keyboard' => [
                    array_map(fn (BotButton $button): array => [
                        'text' => $button->label,
                        'callback_data' => $button->payload,
                    ], $message->buttons),
                ],
            ];
        } else {
            $payload['reply_markup'] = null;
        }

        $this->client($bot)->post('editMessageText', $payload)->throw();
    }

    public function deleteMessage(BotDefinition $bot, string $recipientId, string $messageId): void
    {
        $this->client($bot)->post('deleteMessage', [
            'chat_id' => $recipientId,
            'message_id' => $messageId,
        ])->throw();
    }

    public function answerCallback(BotDefinition $bot, IncomingBotUpdate $update, ?string $text = null): void
    {
        if ($update->callbackId === null) {
            return;
        }

        $this->client($bot)->post('answerCallbackQuery', [
            'callback_query_id' => $update->callbackId,
            'text' => $text,
        ])->throw();
    }

    public function webhookResponse(BotDefinition $bot, Request $request): mixed
    {
        return response()->json(['ok' => true]);
    }

    protected function client(BotDefinition $bot): PendingRequest
    {
        return Http::baseUrl("https://api.telegram.org/bot{$bot->config['token']}/")
            ->acceptJson();
    }

    protected function extractInviteToken(?string $text): ?string
    {
        $text = trim((string) $text);

        if (! str_starts_with($text, '/start')) {
            return null;
        }

        $parts = preg_split('/\s+/', $text);

        return $parts[1] ?? null;
    }
}
