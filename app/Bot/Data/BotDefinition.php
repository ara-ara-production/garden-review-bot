<?php

namespace App\Bot\Data;

class BotDefinition
{
    public function __construct(
        public string $name,
        public string $driver,
        public array $config,
    ) {}
}
