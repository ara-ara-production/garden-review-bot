<?php

namespace App\Dto\Telegram\Entity;

class FillReportPayloadDto implements CallbackQueryPayloadDto
{
    public function __construct(
        public int $reviewId,
        public string $fill,
    ) {
    }
}
