<?php

namespace App\Bot\Contracts;

use App\Bot\Data\BotDefinition;
use App\Bot\Data\IncomingBotUpdate;
use App\Bot\Data\OutgoingBotMessage;
use App\Bot\Data\SentBotMessage;
use Illuminate\Http\Request;

interface BotDriverContract
{
    public function parseWebhook(BotDefinition $bot, Request $request): ?IncomingBotUpdate;

    public function sendMessage(BotDefinition $bot, OutgoingBotMessage $message): ?SentBotMessage;

    public function editMessage(BotDefinition $bot, OutgoingBotMessage $message): void;

    public function deleteMessage(BotDefinition $bot, string $recipientId, string $messageId): void;

    public function answerCallback(BotDefinition $bot, IncomingBotUpdate $update, ?string $text = null): void;

    public function webhookResponse(BotDefinition $bot, Request $request): mixed;
}
