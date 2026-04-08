<?php

namespace App\Bot\Data;

class BotButton
{
    public function __construct(
        public string $label,
        public string $payload,
    ) {}
}
