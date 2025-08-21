<?php

namespace App\UseCases\Telegram;

use App\Dto\Telegram\Entity\ReviewDto;
use App\Dto\Telegram\Entity\ReviewInfoDto;
use App\Dto\Telegram\Factory\ReviewInfoDtoFactory;
use App\Services\MessageService;
use App\Services\ReviewService;
use App\Services\TelegramService;
use App\Services\TwoGisApiService;
use Illuminate\Support\Facades\Log;

class NotifyAboutNewReviewsApiUseCase
{
    public function __construct(
        protected TwoGisApiService $twoGisApiService,
        protected ReviewInfoDtoFactory $reviewDtoFactory,
        protected ReviewService $reviewService,
        protected MessageService $messageService,
        protected TelegramService $telegramService,
    ) {
    }

    public function use(array $data)
    {
//        try {

        $reviewDtoInfo = $this->reviewDtoFactory->fromApi($data);

        $reviewDtos = collect([$reviewDtoInfo]);

        $this->reviewService->storeReviews($reviewDtos);

//        } catch (\Throwable $exception) {
//            Log::error($exception);
//        }
    }
}
