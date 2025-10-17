<?php

namespace App\Jobs;

use App\UseCases\Telegram\NotifyAboutNewReviewsTwoGisUseCase;
use App\UseCases\Telegram\NotifyAboutNewReviewsYandexVendorUseCase;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GetReviewsFromYandexVendor implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(NotifyAboutNewReviewsYandexVendorUseCase $useCase): void
    {
        $useCase->use();
    }
}
