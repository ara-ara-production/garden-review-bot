<?php

namespace App\Services;

use App\Dto\Telegram\Entity\TelegramMessageReviewDto;
use App\Models\TelegramMessage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class MessageService
{
    /**
     * @param Collection<TelegramMessageReviewDto> $dto
     * @return void
     */
    public function store(Collection $collection)
    {

        $collection->each(fn($dto) => TelegramMessage::create((array)$dto));
    }
}
