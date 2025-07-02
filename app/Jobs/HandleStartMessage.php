<?php

namespace App\Jobs;

use App\UseCases\Telegram\SubscribeUserUseCase;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Telegram\Bot\Objects\Update;

class HandleStartMessage implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected Update $update
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(SubscribeUserUseCase $useCase): void
    {
        $useCase->use($this->update);
    }
}
