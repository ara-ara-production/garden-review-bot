<?php

namespace App\UseCases\Telegram;

use App\Dto\Telegram\Entity\CallbackQueryDto;
use App\Dto\Telegram\Entity\UpdateDto;
use App\Services\MessageHandleService;
use Illuminate\Support\Facades\Log;

class InsertReportUseCase
{
    public function __construct(
        protected MessageHandleService $messageHandleService,
    ){}
    public function use(UpdateDto $dto)
    {
        try {
            $this->messageHandleService->makeInputDialog($dto);
        } catch (\Throwable $exception) {
            Log::error($exception);
        }
    }
}
