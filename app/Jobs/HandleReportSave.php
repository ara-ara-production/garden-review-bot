<?php

namespace App\Jobs;

use App\Dto\Telegram\Entity\UpdateDto;
use App\UseCases\Telegram\InsertReportUseCase;
use App\UseCases\Telegram\SaveReportFromStuffUseCase;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class HandleReportSave implements ShouldQueue
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
    public function handle(SaveReportFromStuffUseCase $useCase): void
    {
        $useCase->use($this->dto);
    }
}
