<?php

namespace App\Dto\Telegram\Factory;

use App\Dto\Telegram\Entity\UpdateDto;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Objects\Update;

class UpdateDtoFactory
{
    public function __construct(
        protected CallbackQueryDtoFactory $callbackQueryDtoFactory,
    ) {
    }

    public function fromUpdate(Update $update): UpdateDto
    {
        $message = $update->getMessage();
        return new UpdateDto(
            $message->get('message_id'),
            $message->get('chat')->get('id'),
            $message->get('text'),
            $update->get('callback_query')
                ? $this->callbackQueryDtoFactory->fromData($update->get('callback_query'))
                : null,
        );
    }
}
