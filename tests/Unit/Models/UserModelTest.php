<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversMethod(User::class, 'scopeDataForIndex')]
class UserModelTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function scope_data_for_index(): void
    {
        // Запускаем сидер, чтобы были данные
        $this->seed(\Database\Seeders\UserIndexSeeder::class);

        // Ищем пользователя с telegram_username = 'test'
        $user = User::dataForIndex()->get()->firstWhere('telegram_username', 'test');

        $this->assertNotNull($user, 'User should not be null');

        $this->assertEquals(
            [
                'id' => $user->id,
                'name' => 'Test name',
                'role' => $user->role,
                'email' => 'test@test.com',
                'telegram_username' => 'test',
                'vk_user_id' => null,
                'telegram_is_subscribed' => 0,
                'vk_is_subscribed' => 0,
            ],
            $user->toArray()
        );
    }
}
