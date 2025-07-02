<?php

namespace App\Dto\Telegram\Entity;

class CallbackQueryDto
{
    public function __construct(
        public string $id,
        public string $action,
        public CallbackQueryPayloadDto $data,
    )
    {
    }
}
