<?php

return [
    'instances' => [
        'telegram-main' => [
            'driver' => 'telegram',
            'enabled' => (bool) env('TELEGRAM_BOT_ENABLED', true),
            'token' => env('TELEGRAM_BOT_TOKEN'),
            'username' => env('TELEGRAM_BOT_USERNAME'),
        ],
        'vk-main' => [
            'driver' => 'vk',
            'enabled' => (bool) env('VK_BOT_ENABLED', false),
            'group_id' => env('VK_BOT_GROUP_ID'),
            'token' => env('VK_BOT_TOKEN'),
            'screen_name' => env('VK_BOT_SCREEN_NAME'),
            'confirmation_token' => env('VK_BOT_CONFIRMATION_TOKEN'),
            'secret' => env('VK_BOT_SECRET'),
            'api_version' => env('VK_BOT_API_VERSION', '5.199'),
        ],
    ],
];
