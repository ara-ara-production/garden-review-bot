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
        return new NotifyMediaGroupDto(
            $user->chat_id,
            json_encode($dto->photos),
            $dto->getTelegramFormat(),
            $user->isKeyborded() ? $this->keyboardFactory->forControlFirstNotify($dto->dbId) : null
        );
    }
}
