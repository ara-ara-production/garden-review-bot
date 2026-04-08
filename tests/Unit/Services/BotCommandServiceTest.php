<?php

namespace Tests\Unit\Services;

use App\Bot\Contracts\BotDriverContract;
use App\Bot\Data\BotDefinition;
use App\Bot\Data\IncomingBotUpdate;
use App\Bot\Data\OutgoingBotMessage;
use App\Bot\Services\BotCommandService;
use App\Bot\Services\BotPayloadService;
use App\Bot\Services\BotRegistry;
use App\Bot\Services\BotReviewFormatter;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BotCommandServiceTest extends TestCase
{
    #[Test]
    public function test_command_replies_with_health_message(): void
    {
        $driver = Mockery::mock(BotDriverContract::class);
        $registry = Mockery::mock(BotRegistry::class);
        $bot = new BotDefinition(
            name: 'vk-main',
            driver: 'vk',
            config: ['token' => 'vk-token'],
        );

        $registry->shouldReceive('driver')
            ->once()
            ->with($bot)
            ->andReturn($driver);

        $driver->shouldReceive('sendMessage')
            ->once()
            ->withArgs(function (BotDefinition $actualBot, OutgoingBotMessage $message): bool {
                return $actualBot->name === 'vk-main'
                    && $message->recipientId === '1001'
                    && $message->text === 'Я работаю!';
            });

        $service = new BotCommandService(
            new BotPayloadService,
            $registry,
            Mockery::mock(BotReviewFormatter::class),
        );

        $service->handle($bot, new IncomingBotUpdate(
            bot: 'vk-main',
            driver: 'vk',
            recipientId: '1001',
            senderId: '1001',
            text: 'test',
        ));
    }
}
