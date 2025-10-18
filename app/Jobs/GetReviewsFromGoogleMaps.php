<?php

namespace App\Jobs;

use App\UseCases\Telegram\NotifyAboutNewReviewsGoogleMapsUseCase;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GetReviewsFromGoogleMaps implements ShouldQueue
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
    public function handle(NotifyAboutNewReviewsGoogleMapsUseCase $useCase): void
    {
        $useCase->use();
    }
}
