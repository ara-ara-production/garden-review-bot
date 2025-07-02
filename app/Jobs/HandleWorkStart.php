<?php

namespace App\Jobs;

use App\Dto\Telegram\Entity\UpdateDto;
use App\UseCases\Telegram\NotifyAboutNoWorkRequiredUseCase;
use App\UseCases\Telegram\NotifyAboutWorkStart;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class HandleWorkStart implements ShouldQueue
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
    public function handle(NotifyAboutWorkStart $useCase): void
    {
        $useCase->use($this->dto);
    }
}
