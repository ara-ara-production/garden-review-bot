<?php

namespace App\TelegramCommands;

use App\UseCases\Telegram\SayAllaudUseCase;
use Telegram\Bot\Commands\Command;

class SayAllaudCommand extends Command
{
    protected string $name = 'say_allaud';
    protected string $description = 'Массовая рассылка управляющим';

    public function handle()
    {
        app(SayAllaudUseCase::class)->use($this->getUpdate());
    }
}
