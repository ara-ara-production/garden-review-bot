<?php

namespace App\Bot\Data;

class OutgoingBotMessage
{
    /**
     * @param  array<int, BotButton>  $buttons
     */
    public function __construct(
        public string $recipientId,
        public string $text,
        public array $buttons = [],
        public ?string $messageId = null,
        public ?string $replyToMessageId = null,
        public array $attachments = [],
    ) {}
}
