<?php

namespace App\UseCases\Telegram;

use App\Dto\Telegram\Factory\UpdateDtoFactory;
use App\Jobs\HandleReportSave;
use App\Services\ControlReviewService;
use App\Services\TelegramService;
use Telegram\Bot\Objects\Update;

class AcceptTelegramWebhookUseCase
{
    public function __construct(
        protected ControlReviewService $reviewService,
        protected UpdateDtoFactory $updateDtoFactory,
        protected TelegramService $telegram
    ) {
    }

    public function use(Update $updates): void
    {
        if (!$updates->isEmpty()) {
            $dto = $this->updateDtoFactory->fromUpdate($updates);

            if ($dto->callback_query) {
                app($dto->callback_query->action)->use($dto);
//                $dto->callback_query->action::dispatch(
//                    $dto,
//                );
            } else {
                HandleReportSave::dispatch($dto);
            }
        }
    }
}
