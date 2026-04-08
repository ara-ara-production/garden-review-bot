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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VkBotDriver implements BotDriverContract
{
    public function parseWebhook(BotDefinition $bot, Request $request): ?IncomingBotUpdate
    {
        $payload = $request->all();
        $type = $payload['type'] ?? null;

        if ($type === 'confirmation') {
            return null;
        }

        if ($type === 'message_new') {
            $message = data_get($payload, 'object.message', []);

            return new IncomingBotUpdate(
                bot: $bot->name,
                driver: $bot->driver,
                recipientId: (string) ($message['peer_id'] ?? $message['from_id']),
                senderId: isset($message['from_id']) ? (string) $message['from_id'] : null,
                text: $message['text'] ?? null,
                messageId: isset($message['conversation_message_id']) ? (string) $message['conversation_message_id'] : null,
                inviteToken: $message['ref'] ?? $this->extractInviteToken($message['text'] ?? null),
                meta: $payload,
            );
        }

        if ($type === 'message_event') {
            $callbackPayload = $this->parseCallbackPayload(data_get($payload, 'object.payload'));

            return new IncomingBotUpdate(
                bot: $bot->name,
                driver: $bot->driver,
                recipientId: (string) data_get($payload, 'object.peer_id'),
                senderId: (string) data_get($payload, 'object.user_id'),
                callbackPayload: $callbackPayload['command'] ?? null,
                callbackId: (string) data_get($payload, 'object.event_id'),
                messageId: data_get($payload, 'object.conversation_message_id') !== null
                    ? (string) data_get($payload, 'object.conversation_message_id')
                    : null,
                meta: $payload,
            );
        }

        return null;
    }

    public function sendMessage(BotDefinition $bot, OutgoingBotMessage $message): ?SentBotMessage
    {
        $httpResponse = $this->client($bot)->post('messages.send', [
            'peer_id' => $message->recipientId,
            'message' => strip_tags($message->text),
            'random_id' => random_int(1, PHP_INT_MAX),
            'keyboard' => $this->buildKeyboard($message->buttons),
            'reply_to' => $message->replyToMessageId,
        ]);

        if ($httpResponse->failed()) {
            Log::error('VK sendMessage failed', [
                'bot' => $bot->name,
                'recipient_id' => $message->recipientId,
                'status' => $httpResponse->status(),
                'body' => $httpResponse->body(),
            ]);
        }

        $response = $httpResponse->throw()->json('response');

        Log::info('VK sendMessage response', [
            'bot' => $bot->name,
            'recipient_id' => $message->recipientId,
            'response' => $response,
        ]);

        if ($response === null) {
            return null;
        }

        return new SentBotMessage(
            recipientId: $message->recipientId,
            messageId: (string) $response,
        );
    }

    public function editMessage(BotDefinition $bot, OutgoingBotMessage $message): void
    {
        if ($message->messageId === null) {
            return;
        }

        $this->client($bot)->post('messages.edit', [
            'peer_id' => $message->recipientId,
            'conversation_message_id' => $message->messageId,
            'message' => strip_tags($message->text),
            'keyboard' => $this->buildKeyboard($message->buttons),
        ])->throw();
    }

    public function deleteMessage(BotDefinition $bot, string $recipientId, string $messageId): void
    {
        $this->client($bot)->post('messages.delete', [
            'peer_id' => $recipientId,
            'cmids' => $messageId,
            'delete_for_all' => 1,
        ])->throw();
    }

    public function answerCallback(BotDefinition $bot, IncomingBotUpdate $update, ?string $text = null): void
    {
        if ($update->callbackId === null || $update->senderId === null) {
            return;
        }

        $this->client($bot)->post('messages.sendMessageEventAnswer', [
            'event_id' => $update->callbackId,
            'user_id' => $update->senderId,
            'peer_id' => $update->recipientId,
            'event_data' => json_encode([
                'type' => 'show_snackbar',
                'text' => $text ?: 'Принято',
            ], JSON_UNESCAPED_UNICODE),
        ])->throw();
    }

    public function webhookResponse(BotDefinition $bot, Request $request): mixed
    {
        if (($request->input('type')) === 'confirmation') {
            return response($bot->config['confirmation_token'] ?? '', 200);
        }

        return response('ok', 200);
    }

    protected function client(BotDefinition $bot): PendingRequest
    {
        return Http::baseUrl('https://api.vk.com/method/')
            ->acceptJson()
            ->asForm()
            ->withQueryParameters([
                'access_token' => $bot->config['token'],
                'v' => $bot->config['api_version'] ?? '5.199',
            ]);
    }

    /**
     * @param  array<int, BotButton>  $buttons
     */
    protected function buildKeyboard(array $buttons): ?string
    {
        if ($buttons === []) {
            return null;
        }

        return json_encode([
            'inline' => true,
            'buttons' => [
                array_map(fn (BotButton $button): array => [
                    'action' => [
                        'type' => 'callback',
                        'label' => Str::limit($button->label, 40, ''),
                        'payload' => [
                            'command' => $button->payload,
                        ],
                    ],
                ], $buttons),
            ],
        ], JSON_UNESCAPED_UNICODE);
    }

    protected function extractInviteToken(?string $text): ?string
    {
        $text = trim((string) $text);

        if ($text === '') {
            return null;
        }

        if (preg_match('/^start\s+([A-Za-z0-9_-]+)$/i', $text, $matches) === 1) {
            return $matches[1];
        }

        if (preg_match('/^\/start\s+([A-Za-z0-9_-]+)$/i', $text, $matches) === 1) {
            return $matches[1];
        }

        return null;
    }

    protected function parseCallbackPayload(mixed $payload): array
    {
        if (is_array($payload)) {
            return $payload;
        }

        if (is_string($payload) && $payload !== '') {
            $decoded = json_decode($payload, true);

            if (is_array($decoded)) {
                return $decoded;
            }
        }

        return [];
    }
}
