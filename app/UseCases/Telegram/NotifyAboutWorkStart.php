<?php

namespace App\UseCases\Telegram;

use App\Dto\Telegram\Entity\UpdateDto;
use App\Services\MessageHandleService;
use Illuminate\Support\Facades\Log;

class NotifyAboutWorkStart
{
    public function __construct(
        protected MessageHandleService $messageHandleService,
    ){}
    public function use(UpdateDto $dto)
    {
        try {
            $this->messageHandleService->markReviewNeedWork($dto->callback_query->data);

            $this->messageHandleService->replyToCallback($dto->callback_query, 'Принято!');

            $this->messageHandleService->setInlineButtonForControlReview($dto->callback_query->data);
        } catch (\Throwable $exception) {
            Log::error($exception);
        }
    }
}
