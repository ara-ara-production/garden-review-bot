<?php

namespace Tests\Unit\Services;

use App\Services\UserService;
use Database\Seeders\UserIndexSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\Attributes\CoversMethod;
use Tests\TestCase;

#[CoversMethod(UserService::class, 'getPaginator')]
class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = UserIndexSeeder::class;

    public function test_getPaginator_returnsLengthAwarePaginator_withExpectedFields()
    {
        $service = $this->app->make(UserService::class);

        $paginator = $service->getPaginator();

        $this->assertInstanceOf(LengthAwarePaginator::class, $paginator);

        foreach ($paginator->items() as $user) {
            $this->assertArrayHasKey('id', $user);
            $this->assertArrayHasKey('name', $user);
            $this->assertArrayHasKey('telegram_username', $user);
            $this->assertArrayHasKey('is_subscribed', $user);
            $this->assertIsBool($user['is_subscribed']);
        }
    }
}
