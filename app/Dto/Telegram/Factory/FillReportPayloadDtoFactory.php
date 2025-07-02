<?php

namespace App\Dto\Telegram\Factory;

use App\Dto\Telegram\Entity\CallbackQueryPayloadDto;
use App\Dto\Telegram\Entity\FillReportPayloadDto;
use App\Dto\Telegram\Entity\ReviewIdPayloadDto;

class FillReportPayloadDtoFactory implements CallbackQueryPayloadDtoFactory
{
    public function make(array $payload): CallbackQueryPayloadDto
    {
        return new FillReportPayloadDto(
            $payload['review_id'],
            $payload['fill']
        );
    }
}
