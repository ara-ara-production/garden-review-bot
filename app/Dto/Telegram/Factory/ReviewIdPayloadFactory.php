<?php

namespace App\Dto\Telegram\Factory;

use App\Dto\Telegram\Entity\CallbackQueryPayloadDto;
use App\Dto\Telegram\Entity\ReviewIdPayloadDto;

class ReviewIdPayloadFactory implements CallbackQueryPayloadDtoFactory
{
    public function make(array $payload): CallbackQueryPayloadDto
    {
        return new ReviewIdPayloadDto(
            $payload['review_id'],
        );
    }
}
