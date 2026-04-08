<?php

namespace App\Bot\Services;

use App\Bot\Contracts\BotDriverContract;
use App\Bot\Data\BotDefinition;
use App\Bot\Drivers\TelegramBotDriver;
use App\Bot\Drivers\VkBotDriver;
use RuntimeException;

class BotRegistry
{
    /**
     * @return array<int, BotDefinition>
     */
    public function allEnabled(): array
    {
        return collect(config('bots.instances', []))
            ->filter(fn (array $config): bool => (bool) ($config['enabled'] ?? false))
            ->map(fn (array $config, string $name): BotDefinition => new BotDefinition(
                name: $name,
                driver: $config['driver'],
                config: $config,
            ))
            ->values()
            ->all();
    }

    public function find(string $name): BotDefinition
    {
        $config = config("bots.instances.{$name}");

        if (! is_array($config)) {
            throw new RuntimeException("Bot [{$name}] is not configured.");
        }

        return new BotDefinition(
            name: $name,
            driver: $config['driver'],
            config: $config,
        );
    }

    public function driver(BotDefinition $bot): BotDriverContract
    {
        return match ($bot->driver) {
            'telegram' => app(TelegramBotDriver::class),
            'vk' => app(VkBotDriver::class),
            default => throw new RuntimeException("Unsupported bot driver [{$bot->driver}]."),
        };
    }
}
