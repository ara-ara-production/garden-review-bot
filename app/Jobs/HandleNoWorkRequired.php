<?php

namespace App\Jobs;

use App\Dto\Telegram\Entity\UpdateDto;
use App\UseCases\Telegram\NotifyAboutNoWorkRequiredUseCase;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class HandleNoWorkRequired implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected UpdateDto $dto
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(NotifyAboutNoWorkRequiredUseCase $useCase): void
    {
        $useCase->use($this->dto);
    }
}
