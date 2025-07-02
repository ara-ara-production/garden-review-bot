<?php

namespace App\Dto\Telegram\Entity;

class BranchDto
{
    public function __construct(
        public string $id,
        public ?string $name = null,
        public ?string $upr = null,
    ) {}
}
