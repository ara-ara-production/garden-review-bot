<?php

namespace App\Http\Controllers;

use App\Jobs\MessageHandler;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
    public function getWebhookUpdate()
    {
        $updates = Telegram::commandsHandler(true);

        MessageHandler::dispatch($updates);
    }
}
