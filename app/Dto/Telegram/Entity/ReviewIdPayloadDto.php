<?php

namespace App\Dto\Telegram\Entity;

class ReviewIdPayloadDto implements CallbackQueryPayloadDto
{
    public function __construct(
        public $reviewId
    ) {
    }
}
