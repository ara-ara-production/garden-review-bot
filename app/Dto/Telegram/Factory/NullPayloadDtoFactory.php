<?php

namespace App\Dto\Telegram\Factory;

use App\Dto\Telegram\Entity\NullPayloadDto;

class NullPayloadDtoFactory implements CallbackQueryPayloadDtoFactory
{
    public function make(array $payload): NullPayloadDto
    {
        return new NullPayloadDto();
    }
}
