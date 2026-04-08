<?php

namespace Tests\Unit\Drivers;

use App\Bot\Data\BotDefinition;
use App\Bot\Data\IncomingBotUpdate;
use App\Bot\Drivers\VkBotDriver;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class VkCallbackAnswerTest extends TestCase
{
    #[Test]
    public function it_sends_message_event_answer(): void
    {
        Http::fake([
            'https://api.vk.com/method/*' => Http::response([
                'response' => 1,
            ]),
        ]);

        $driver = new VkBotDriver;
        $bot = new BotDefinition(
            name: 'vk-main',
            driver: 'vk',
            config: [
                'token' => 'vk-token',
                'api_version' => '5.199',
            ],
        );

        $driver->answerCallback($bot, new IncomingBotUpdate(
            bot: 'vk-main',
            driver: 'vk',
            recipientId: '1001',
            senderId: '1001',
            callbackId: 'event-123',
        ), 'Принято!');

        Http::assertSent(function ($request): bool {
            return str_contains($request->url(), 'messages.sendMessageEventAnswer')
                && $request['event_id'] === 'event-123'
                && $request['user_id'] === '1001'
                && $request['peer_id'] === '1001';
        });
    }
}
