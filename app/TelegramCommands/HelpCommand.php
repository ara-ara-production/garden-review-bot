<?php

namespace App\TelegramCommands;

use Telegram\Bot\Commands\Command;

class HelpCommand extends Command
{
    protected string $name = 'help';
    protected string $description = 'Показать справку по доступным командам';

    public function handle()
    {
        $helpText = <<<EOT
❗ Данный бот является частью системы по работы с отзывами гостей на кофейни Garden

Для получения информации по отзывам /reviews

🔧 При технических неполадках писать @Tamanit
EOT;

        $this->replyWithMessage(['text' => $helpText]);
    }
}
