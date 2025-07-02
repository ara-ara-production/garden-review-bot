<?php

namespace App\Dto\Telegram\Entity;

class SubscribeMessageDto
{
    public function __construct(
        public string $username,
        public string $chatId
    )
    {
    }
}
