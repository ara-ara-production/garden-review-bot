<?php

namespace App\Dto\Api;

class ReviewApiRequset
{
    public function __construct(
        public int $innerId,
        public string $postedAt,
        public string $brunch,
        public int $score,
        public ?string $text,
        public ?string $sender,
    ) {}
}
