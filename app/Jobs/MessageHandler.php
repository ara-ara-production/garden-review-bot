<?php

namespace App\Jobs;

use App\UseCases\Telegram\AcceptTelegramWebhookUseCase;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Telegram\Bot\Objects\Update;

class MessageHandler implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected Update $updates
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(AcceptTelegramWebhookUseCase $useCase): void
    {
        $useCase->use($this->updates);
    }
}
