<?php

namespace App\Dto\Telegram\Factory;

use App\Dto\Telegram\Entity\SubscribeMessageDto;
use App\Exeptions\Telegram\NullUsernameException;
use Telegram\Bot\Objects\Update;

class SubscribeMessageDtoFactory
{
    /**
     * @throws NullUsernameException
     */
    public function fromUpdate(Update $update): SubscribeMessageDto
    {
        $message = $update->getMessage();
        $username = $message->get('from')->get('username');

        if (!$username) {
            throw new NullUsernameException();
        }
        return new SubscribeMessageDto(
            $username,
            $message->get('chat')->get('id'),
        );
    }
}
