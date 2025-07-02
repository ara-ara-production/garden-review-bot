<?php

namespace App\Dto\Telegram\Entity;

class SimpleMessageDto implements TelegramMessage
{
    public function __construct(
        public string $chat_id,
        public string $text,
    ) {
    }
}
