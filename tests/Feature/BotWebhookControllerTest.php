<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BotWebhookControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function vk_confirmation_returns_confirmation_token(): void
    {
        Config::set('bots.instances', [
            'vk-main' => [
                'driver' => 'vk',
                'enabled' => true,
                'token' => 'vk-token',
                'group_id' => '237499640',
                'confirmation_token' => '5c1dd0d7',
                'api_version' => '5.199',
            ],
        ]);

        $response = $this->postJson('/bots/vk-main/webhook', [
            'type' => 'confirmation',
            'group_id' => 237499640,
        ]);

        $response->assertOk();
        $response->assertSeeText('5c1dd0d7');
    }
}
