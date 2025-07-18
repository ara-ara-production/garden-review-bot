<?php

namespace App\Dto\Telegram\Entity;

use Telegram\Bot\Keyboard\Keyboard;

class NotifyMediaGroupDto implements TelegramMessage
{
    public function __construct(
        public string $chat_id,
        public string $media,
        public ?Keyboard $reply_markup = null,
    )
    {
    }
}
