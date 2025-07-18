<?php

namespace App\Dto\Telegram\Entity;

use Telegram\Bot\Keyboard\Keyboard;

class NotifyMediaGroupDto implements TelegramMessage
{
    public function __construct(
        public string $chat_id,
        public string $media,
        public string $text,
        public ?Keyboard $reply_markup = null,
        public string $parse_mode = 'HTML',
        public bool $disable_web_page_preview = true,
    )
    {
    }
}
