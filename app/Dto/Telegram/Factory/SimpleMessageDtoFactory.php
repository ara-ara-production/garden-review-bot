<?php

namespace App\Dto\Telegram\Factory;

use App\Dto\Telegram\Entity\SimpleMessageDto;
use Telegram\Bot\Objects\Update;

class SimpleMessageDtoFactory
{
    public function fromUpdateAndText(Update $update, string $text): SimpleMessageDto
    {
        return new SimpleMessageDto(
            $update->getMessage()->get('chat')->get('id'),
            $text
        );
    }

    public function fromRawParameters(string $chat_id, string $text): SimpleMessageDto
    {
        return new SimpleMessageDto(
            $chat_id,
            $text
        );
    }
}
