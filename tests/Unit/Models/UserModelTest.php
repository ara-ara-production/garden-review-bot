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
    public function scopeDataForIndex(): void
    {
        // Запускаем сидер, чтобы были данные
        $this->seed(\Database\Seeders\UserIndexSeeder::class);

        // Ищем пользователя с telegram_username = 'test'
        $user = User::dataForIndex()->where('telegram_username', 'test')->first();

        $this->assertNotNull($user, 'User should not be null');

        $this->assertEquals(
            [
                'id' => $user->id,  // подставляем реальный id
                'name' => 'Test name',
                'telegram_username' => 'test',
                'is_subscribed' => false
            ],
            $user->toArray()
        );
    }

}
