<?php

namespace App\UseCases\Telegram;

use App\Dto\Telegram\Entity\UpdateDto;
use App\Services\MessageHandleService;
use Illuminate\Support\Facades\Log;

class SaveReportFromStuffUseCase
{
    public function __construct(
        protected MessageHandleService $messageHandleService,
    ){}
    public function use(UpdateDto $dto)
    {
        try {
            $this->messageHandleService->getReport($dto);
        } catch (\Throwable $exception) {
            Log::error($exception);
        }
    }
}
