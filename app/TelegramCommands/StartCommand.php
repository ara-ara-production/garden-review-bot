<?php

namespace App\TelegramCommands;

use App\Jobs\HandleStartMessage;
use App\UseCases\Telegram\SubscribeUserUseCase;
use Telegram\Bot\Commands\Command;

class StartCommand extends Command
{
    protected string $name = 'start';
    protected string $description = 'Подписывает пользователя на бота';

    /**
     * @inheritDoc
     */
    public function handle(): void
    {
        HandleStartMessage::dispatch($this->getUpdate());
    }
}
