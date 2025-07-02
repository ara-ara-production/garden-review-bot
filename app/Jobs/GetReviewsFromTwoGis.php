<?php

namespace App\Jobs;

use App\UseCases\Telegram\NotifyAboutNewReviewsTwoGisUseCase;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GetReviewsFromTwoGis implements ShouldQueue
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
    public function handle(NotifyAboutNewReviewsTwoGisUseCase $useCase): void
    {
        $useCase->use();
    }
}
