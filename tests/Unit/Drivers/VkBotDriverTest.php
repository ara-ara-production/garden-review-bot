<?php

namespace Tests\Unit\Drivers;

use App\Bot\Data\BotDefinition;
use App\Bot\Drivers\VkBotDriver;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class VkBotDriverTest extends TestCase
{
    #[Test]
    public function it_parses_message_event_payload_from_json_string(): void
    {
        $driver = new VkBotDriver;
        $bot = new BotDefinition(
            name: 'vk-main',
            driver: 'vk',
            config: [],
        );

        $request = Request::create('/bots/vk-main/webhook', 'POST', [
            'type' => 'message_event',
            'object' => [
                'peer_id' => 11,
                'user_id' => 22,
                'event_id' => 'evt-1',
                'conversation_message_id' => 33,
                'payload' => json_encode([
                    'command' => 'action:handle_work_start|review_id:15',
                ], JSON_UNESCAPED_UNICODE),
            ],
        ]);

        $update = $driver->parseWebhook($bot, $request);

        $this->assertNotNull($update);
        $this->assertSame('action:handle_work_start|review_id:15', $update->callbackPayload);
        $this->assertSame('11', $update->recipientId);
        $this->assertSame('22', $update->senderId);
    }
}
