<?php

namespace App\Jobs;

use App\UseCases\Telegram\NotifyAboutNewReviewsTwoGisUseCase;
use App\UseCases\Telegram\NotifyAboutNewReviewsYandexMapUseCase;
use App\UseCases\Telegram\NotifyAboutNewReviewsYandexVendorUseCase;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GetReviewsFromYandexMap implements ShouldQueue
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
    public function handle(NotifyAboutNewReviewsYandexMapUseCase $useCase): void
    {
        $useCase->use();
    }
}
