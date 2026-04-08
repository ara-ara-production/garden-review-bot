<?php

namespace App\Observers;

use App\Bot\Services\BotNotificationService;
use App\Dto\Telegram\Factory\ReviewInfoDtoFactory;
use App\Models\Review;

class ReviewObserver
{
    public function __construct(
        protected ReviewInfoDtoFactory $reviewInfoDtoFactory,
        protected BotNotificationService $botNotificationService,
    ) {}

    /**
     * Handle the Review "created" event.
     */
    public function created(Review $review): void
    {
        $reviewInfoDto = $this->reviewInfoDtoFactory->fromEntity($review);
        $this->botNotificationService->notifyAboutReview($reviewInfoDto);
    }

    /**
     * Handle the Review "updated" event.
     */
    public function updated(Review $review): void
    {
        $changedFields = array_keys($review->getChanges()); // только изменённые атрибуты (новые значения)
        if (array_intersect($changedFields, ['is_edited', 'is_on_check', 'comment'])) {
            $this->botNotificationService->deleteReviewMessages($review);
            $this->botNotificationService->notifyAboutReview($this->reviewInfoDtoFactory->fromEntity($review));
        }
    }

    /**
     * Handle the Review "deleted" event.
     */
    public function deleted(Review $review): void
    {
        //
    }

    /**
     * Handle the Review "restored" event.
     */
    public function restored(Review $review): void
    {
        //
    }

    /**
     * Handle the Review "force deleted" event.
     */
    public function forceDeleted(Review $review): void
    {
        //
    }
}
