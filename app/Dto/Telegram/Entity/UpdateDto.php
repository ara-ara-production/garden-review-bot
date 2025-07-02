<?php

namespace App\Dto\Telegram\Entity;

class UpdateDto
{
    public function __construct(
        public string $message_id,
        public string $chat_id,
        public ?string $message = null,
        public ?CallbackQueryDto $callback_query = null,
    )
    {
    }
}
