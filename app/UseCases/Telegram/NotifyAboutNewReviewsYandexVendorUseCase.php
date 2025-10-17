<?php

namespace App\UseCases\Telegram;

use App\Dto\Telegram\Entity\ReviewDto;
use App\Dto\Telegram\Entity\ReviewInfoDto;
use App\Dto\Telegram\Factory\ReviewInfoDtoFactory;
use App\Services\MessageService;
use App\Services\ReviewService;
use App\Services\TelegramService;
use App\Services\TwoGisApiService;
use App\Services\YandexVendorApiService;
use Illuminate\Support\Facades\Log;

class NotifyAboutNewReviewsYandexVendorUseCase
{
    public function __construct(
        protected YandexVendorApiService $yandexVendorApiService,
        protected ReviewInfoDtoFactory $reviewDtoFactory,
        protected ReviewService $reviewService,
        protected MessageService $messageService,
        protected TelegramService $telegramService,
    ) {
    }

    public function use()
    {
//        try {
        $rawData = $this->yandexVendorApiService->foreachBrunches();

        $reviewDtos = $this->reviewDtoFactory->fromYandexVendorArray($rawData);

        $reviewDtos = $this->reviewService->removeExistedReviews($reviewDtos);

        $this->reviewService->storeReviews($reviewDtos);

//        } catch (\Throwable $exception) {
//            Log::error($exception);
//        }
    }
}
