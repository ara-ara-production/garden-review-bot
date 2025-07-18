<?php

namespace App\Dto\Telegram\Factory;

use App\Dto\Telegram\Entity\NotifyMediaGroupDto;
use App\Dto\Telegram\Entity\ReviewInfoDto;
use App\Dto\User\Entity\ForNotifyDto;

class NotifyMediaGroupDtoFactory
{
    public function __construct(
        protected TelegramKeyboardFactory $keyboardFactory,
    ) {
    }

    public function fromDtos(ReviewInfoDto $dto, ForNotifyDto $user): NotifyMediaGroupDto
    {
        if (mb_strlen($dto->getTelegramFormat()) < 1024) {
            $dto->photos[0]['caption'] = $dto->getTelegramFormat();
            $dto->photos[0]['parse_mode'] = 'HTML';
        }

        return new NotifyMediaGroupDto(
            $user->chat_id,
            json_encode($dto->photos),
            $user->isKeyborded() ? $this->keyboardFactory->forControlFirstNotify($dto->dbId) : null
        );
    }
}
