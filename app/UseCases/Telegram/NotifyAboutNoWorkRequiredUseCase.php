<?php

namespace App\UseCases\Telegram;

use App\Dto\Telegram\Entity\UpdateDto;
use App\Services\MessageHandleService;
use Illuminate\Support\Facades\Log;

class NotifyAboutNoWorkRequiredUseCase
{
    public function __construct(
        protected MessageHandleService $messageHandleService,
    ){}
    public function use(UpdateDto $dto)
    {
        try {
            $this->messageHandleService->markReviewToNoNeedWork($dto->callback_query->data);

            $this->messageHandleService->replyToCallback($dto->callback_query, 'Принято!');

//            $this->messageHandleService->clearInlineButtons($dto->callback_query->data);
        } catch (\Throwable $exception) {
            Log::error($exception);
        }
    }
}
