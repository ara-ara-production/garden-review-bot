<?php

namespace App\Dto\Telegram\Entity;

class ReviewDto
{
    public function __construct(
        public string $id,
        public string $text,
        public string $rating,
        public string $sender,
        public \DateTime $time,
        public string $resource,
        public ?string $link = null,
        public ?array $photos = null,
        public bool $isEdited = false,
        public ?BranchDto $branchDto = null,
        public ?int $dbId = null,
        public ?\DateTime $startWorkOn = null,
        public ?\DateTime $endWorkOn = null,
        public ?string $controlReview = null,
        public ?string $finalAnswer = null,
        public array $messageId = [],
    ) {}
}
