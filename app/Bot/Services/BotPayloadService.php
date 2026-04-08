<?php

namespace App\Bot\Services;

class BotPayloadService
{
    public function parse(string $payload): array
    {
        $result = [];

        foreach (explode('|', $payload) as $part) {
            [$key, $value] = explode(':', $part, 2);
            $result[$key] = $value;
        }

        return $result;
    }

    public function make(string $action, array $payload = []): string
    {
        $segments = ["action:{$action}"];

        foreach ($payload as $key => $value) {
            $segments[] = "{$key}:{$value}";
        }

        return implode('|', $segments);
    }
}
