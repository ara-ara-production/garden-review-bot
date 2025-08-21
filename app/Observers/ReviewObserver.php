<?php

namespace App\Observers;

use App\Dto\Telegram\Factory\ReviewInfoDtoFactory;
use App\Models\Review;
use App\Services\MessageService;
use App\Services\TelegramService;

use Illuminate\Support\Facades\Log;

use function Psy\debug;

class ReviewObserver
{
    public function __construct(
        protected ReviewInfoDtoFactory $reviewInfoDtoFactory,
        protected TelegramService $telegramService,
        protected MessageService $messageService,
    ) {}

    /**
     * Handle the Review "created" event.
     */
    public function created(Review $review): void
    {
        $reviewInfoDto = $this->reviewInfoDtoFactory->fromEntity($review);

        $messagesToStore = $this->telegramService->firstNotify(collect([$reviewInfoDto]));
        $messagesToStore = $messagesToStore->filter();
        $this->messageService->store($messagesToStore);
    }

    /**
     * Handle the Review "updated" event.
     */
    public function updated(Review $review): void
    {
        $changedFields = array_keys($review->getChanges()); // только изменённые атрибуты (новые значения)
        if (array_intersect($changedFields, ['is_edited', 'is_on_check', 'comment'])) {
            $reviewInfoDto = $this->reviewInfoDtoFactory->fromEntity($review);

            $messagesToStore = $this->telegramService->firstNotify(collect([$reviewInfoDto]));
            $messagesToStore = $messagesToStore->filter();
            $this->messageService->store($messagesToStore);
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
