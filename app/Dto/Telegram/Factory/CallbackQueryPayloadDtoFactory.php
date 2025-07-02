<?php

namespace App\Dto\Telegram\Factory;

use App\Dto\Telegram\Entity\CallbackQueryPayloadDto;

interface CallbackQueryPayloadDtoFactory
{
    public function make(array $payload): CallbackQueryPayloadDto;
}
