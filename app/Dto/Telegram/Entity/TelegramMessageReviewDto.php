<?php

namespace App\Dto\Telegram\Entity;

class TelegramMessageReviewDto
{
    public function __construct(
        public int $review_id,
        public string $message_id,
        public string $user_id,
    ) {
    }
}
