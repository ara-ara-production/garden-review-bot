<?php

namespace App\UseCases\Telegram;

use App\Dto\Telegram\Factory\ReviewInfoDtoFactory;
use App\Services\MessageService;
use App\Services\ReviewService;
use App\Services\TelegramService;
use App\Services\TwoGisApiService;
use Illuminate\Support\Facades\Log;

class NotifyAboutNewReviewsTwoGisUseCase
{
    public function __construct(
        protected TwoGisApiService $twoGisApiService,
        protected ReviewInfoDtoFactory $reviewDtoFactory,
        protected ReviewService $reviewService,
        protected MessageService $messageService,
        protected TelegramService $telegramService,
    ) {
    }

    public function use()
    {
//        try {
        $rawData = $this->twoGisApiService->foreachBrunches();

        $reviewDtos = $this->reviewDtoFactory->withMeta($rawData);

        $reviewDtos = $this->reviewService->removeExistedReviews($reviewDtos);
        $reviewDtos = $this->reviewService->storeReviews($reviewDtos);

        $messagesToStore = $this->telegramService->firstNotify($reviewDtos);
        $messagesToStore = $messagesToStore->filter();
        $this->messageService->store($messagesToStore);
//        } catch (\Throwable $exception) {
//            Log::error($exception);
//        }
    }
}
