<?php

namespace App\Dto\Telegram\Factory;

use App\Dto\Telegram\Entity\NotifyMediaGroupDto;
use App\Dto\Telegram\Entity\NotifyMessageDto;
use App\Dto\Telegram\Entity\ReviewInfoDto;
use App\Dto\User\Entity\ForNotifyDto;
use App\Enums\UserRoleEnum;

class NotifyMessageDtoFactory
{
    public function __construct(
        protected TelegramKeyboardFactory $keyboardFactory,
    )
    {
    }

    public function fromDtos(ReviewInfoDto $dto, ForNotifyDto $user): NotifyMessageDto
    {
        return new NotifyMessageDto(
            $user->chat_id,
            $dto->getTelegramFormat(),
            match ($user->role) {
                UserRoleEnum::Control->name => $this->keyboardFactory->forControlFirstNotify($dto->dbId),
                UserRoleEnum::Ssm->name => $this->keyboardFactory->forSMM($dto->dbId),
                default => null,
            }
        );
    }
}
