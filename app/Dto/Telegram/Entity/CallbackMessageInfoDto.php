<?php

namespace App\Dto\Telegram\Entity;

class CallbackMessageInfoDto implements CallbackQueryPayloadDto
{
    public function __construct(
        public int $dbMessageId
    ){}
}
