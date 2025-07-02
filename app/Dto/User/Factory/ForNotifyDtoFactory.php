<?php

namespace App\Dto\User\Factory;

use App\Dto\User\Entity\ForNotifyDto;
use App\Models\User;

class ForNotifyDtoFactory
{
    public function fromEntity(User $user): ForNotifyDto
    {
        return new ForNotifyDto(
            $user->id,
            $user->role->name,
            $user->telegram_chat,
        );
    }
}
