<?php

namespace App\TelegramCommands;

use App\UseCases\Telegram\SendReiewLinkUserUseCase;
use Telegram\Bot\Commands\Command;

class ReviewsCommand extends Command
{
    protected string $name = 'reviews';
    protected string $description = 'Получение ссылки на информацию об отзывах';

    public function handle()
    {
        app(SendReiewLinkUserUseCase::class)->use($this->getUpdate());
    }
}
