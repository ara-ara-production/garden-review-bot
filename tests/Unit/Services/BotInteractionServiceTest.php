<?php

namespace Tests\Unit\Services;

use App\Bot\Data\BotDefinition;
use App\Bot\Data\IncomingBotUpdate;
use App\Bot\Services\BotInteractionService;
use App\Bot\Services\BotNotificationService;
use App\Bot\Services\BotPayloadService;
use App\Bot\Services\BotRegistry;
use App\Bot\Services\BotReviewFormatter;
use App\Enums\UserRoleEnum;
use App\Models\BotMessage;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Model;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BotInteractionServiceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function vk_callback_updates_stored_message_id_before_refresh(): void
    {
        Model::unsetEventDispatcher();

        $user = User::query()->create([
            'name' => 'Manager',
            'email' => null,
            'password' => null,
            'role' => UserRoleEnum::Control,
        ]);

        $review = Review::query()->create([
            'key' => 'review-1',
            'resource' => '2ГИС',
            'posted_at' => now(),
            'score' => '5',
            'comment' => 'test',
            'sender' => 'guest',
            'link' => 'https://example.com',
            'total_brunch_rate' => '5.0',
        ]);

        BotMessage::query()->create([
            'review_id' => $review->id,
            'user_id' => $user->id,
            'driver' => 'vk',
            'bot' => 'vk-main',
            'recipient_id' => '1001',
            'message_id' => 'old-message-id',
        ]);

        $driver = Mockery::mock(\App\Bot\Contracts\BotDriverContract::class);
        $registry = Mockery::mock(BotRegistry::class);
        $notificationService = Mockery::mock(BotNotificationService::class);

        $bot = new BotDefinition(
            name: 'vk-main',
            driver: 'vk',
            config: [],
        );

        $registry->shouldReceive('driver')->andReturn($driver);
        $driver->shouldReceive('answerCallback')->once();
        $notificationService->shouldReceive('refreshStoredMessage')->once();

        $service = new BotInteractionService(
            new BotPayloadService,
            $notificationService,
            $registry,
            Mockery::mock(BotReviewFormatter::class),
        );

        $service->markReviewNeedWork($bot, new IncomingBotUpdate(
            bot: 'vk-main',
            driver: 'vk',
            recipientId: '1001',
            senderId: '1001',
            callbackId: 'event-1',
            messageId: '55',
        ), $review->id);

        $this->assertDatabaseHas('bot_messages', [
            'review_id' => $review->id,
            'driver' => 'vk',
            'bot' => 'vk-main',
            'recipient_id' => '1001',
            'message_id' => '55',
        ]);
    }
}
