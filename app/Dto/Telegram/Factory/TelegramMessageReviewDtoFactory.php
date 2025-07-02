<?php

namespace App\Dto\Telegram\Factory;

use App\Dto\Telegram\Entity\ReviewInfoDto;
use App\Dto\Telegram\Entity\TelegramMessageReviewDto;
use App\Dto\User\Entity\ForNotifyDto;
use Telegram\Bot\Objects\Message;

class TelegramMessageReviewDtoFactory
{
    public function fromData(
        ReviewInfoDto $reviewInfoDto,
        Message $message,
        ForNotifyDto $user
    ): TelegramMessageReviewDto {
        return new TelegramMessageReviewDto(
            $reviewInfoDto->dbId,
            $message->get('message_id'),
            $user->id,
        );
    }
}
