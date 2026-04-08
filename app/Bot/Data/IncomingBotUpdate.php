<?php

namespace App\Bot\Data;

class IncomingBotUpdate
{
    public function __construct(
        public string $bot,
        public string $driver,
        public string $recipientId,
        public ?string $senderId = null,
        public ?string $senderUsername = null,
        public ?string $text = null,
        public ?string $callbackPayload = null,
        public ?string $callbackId = null,
        public ?string $messageId = null,
        public ?string $replyToMessageId = null,
        public ?string $inviteToken = null,
        public array $meta = [],
    ) {}
}
