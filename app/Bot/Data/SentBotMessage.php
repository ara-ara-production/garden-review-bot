<?php

namespace App\Bot\Data;

class SentBotMessage
{
    public function __construct(
        public string $recipientId,
        public string $messageId,
    ) {}
}
