<?php

namespace Tests\Unit\Services;

use App\Bot\Data\IncomingBotUpdate;
use App\Enums\UserRoleEnum;
use App\Models\Brunch;
use App\Models\User;
use App\Services\BotInviteService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(BotInviteService::class)]
class BotInviteServiceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_telegram_link_and_claims_invite(): void
    {
        Config::set('bots.instances', [
            'telegram-main' => [
                'driver' => 'telegram',
                'enabled' => true,
                'token' => 'token',
                'username' => 'GardenReviewBot',
            ],
        ]);

        $service = $this->app->make(BotInviteService::class);
        $brunch = Brunch::query()->create([
            'name' => 'Center',
            'two_gis_id' => null,
        ]);

        $invite = $service->create([
            'driver' => 'telegram',
            'bot' => 'telegram-main',
            'role' => UserRoleEnum::Control->name,
            'brunch_id' => $brunch->id,
            'assignment' => 'user_id',
            'name_hint' => 'Иван',
            'max_uses' => 1,
            'expires_at' => null,
        ]);

        $this->assertSame(
            "https://t.me/GardenReviewBot?start={$invite->token}",
            $service->buildLink($invite)
        );

        $user = $service->claim($invite->token, new IncomingBotUpdate(
            bot: 'telegram-main',
            driver: 'telegram',
            recipientId: '123456',
            senderId: '321',
            senderUsername: 'ivan_manager',
            text: '/start '.$invite->token,
            inviteToken: $invite->token,
        ));

        $this->assertSame('Иван', $user->name);
        $this->assertSame(UserRoleEnum::Control, $user->role);
        $this->assertDatabaseHas('user_messenger_accounts', [
            'user_id' => $user->id,
            'driver' => 'telegram',
            'username' => 'ivan_manager',
            'external_id' => '123456',
        ]);
        $this->assertDatabaseHas('bot_subscriptions', [
            'user_id' => $user->id,
            'driver' => 'telegram',
            'bot' => 'telegram-main',
            'recipient_id' => '123456',
        ]);
        $this->assertDatabaseHas('brunches', [
            'id' => $brunch->id,
            'user_id' => $user->id,
        ]);
    }

    #[Test]
    public function it_claims_invite_for_existing_user(): void
    {
        Config::set('bots.instances', [
            'telegram-main' => [
                'driver' => 'telegram',
                'enabled' => true,
                'token' => 'token',
                'username' => 'GardenReviewBot',
            ],
        ]);

        $service = $this->app->make(BotInviteService::class);
        $user = User::query()->create([
            'name' => 'Existing User',
            'email' => null,
            'password' => null,
            'role' => UserRoleEnum::Founder,
        ]);

        $invite = $service->create([
            'driver' => 'telegram',
            'bot' => 'telegram-main',
            'user_id' => (string) $user->id,
            'role' => UserRoleEnum::Control->name,
            'brunch_id' => null,
            'assignment' => null,
            'name_hint' => null,
            'max_uses' => 1,
            'expires_at' => null,
        ]);

        $claimedUser = $service->claim($invite->token, new IncomingBotUpdate(
            bot: 'telegram-main',
            driver: 'telegram',
            recipientId: '999',
            senderId: '999',
            senderUsername: 'existing_user',
            text: '/start '.$invite->token,
            inviteToken: $invite->token,
        ));

        $this->assertSame($user->id, $claimedUser->id);
        $this->assertSame(UserRoleEnum::Control, $claimedUser->role);
        $this->assertDatabaseHas('user_messenger_accounts', [
            'user_id' => $user->id,
            'driver' => 'telegram',
            'username' => 'existing_user',
            'external_id' => '999',
        ]);
    }
}
