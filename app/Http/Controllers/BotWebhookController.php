<?php

namespace App\Http\Controllers;

use App\Bot\Services\BotCommandService;
use App\Bot\Services\BotRegistry;
use Illuminate\Http\Request;

class BotWebhookController extends Controller
{
    public function __construct(
        protected BotRegistry $botRegistry,
        protected BotCommandService $botCommandService,
    ) {}

    public function handle(Request $request, string $bot): mixed
    {
        $botDefinition = $this->botRegistry->find($bot);
        $driver = $this->botRegistry->driver($botDefinition);
        $update = $driver->parseWebhook($botDefinition, $request);

        if ($update !== null) {
            $this->botCommandService->handle($botDefinition, $update);
        }

        return $driver->webhookResponse($botDefinition, $request);
    }
}
