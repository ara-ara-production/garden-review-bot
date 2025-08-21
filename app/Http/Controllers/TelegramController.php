<?php

namespace App\Http\Controllers;

use App\Jobs\MessageHandler;
use App\UseCases\Telegram\AcceptTelegramWebhookUseCase;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
    public function __construct(
        protected AcceptTelegramWebhookUseCase $useCase
    ) {}

    public function getWebhookUpdate()
    {
        $updates = Telegram::commandsHandler(true);

        $this->useCase->use($updates);
//        MessageHandler::dispatch($updates);
    }
}
