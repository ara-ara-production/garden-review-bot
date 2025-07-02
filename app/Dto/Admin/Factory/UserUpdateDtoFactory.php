<?php

namespace App\Dto\Admin\Factory;

use App\Dto\Admin\Entity\UserUpdateDto;
use App\Models\User;

class UserUpdateDtoFactory
{
    public function fromModel(User $user): UserUpdateDto
    {
        $dataCollection = collect($user->toArray());

        return new UserUpdateDto(
            $dataCollection->get('id'),
            $dataCollection->get('name'),
            $dataCollection->get('email'),
            $dataCollection->get('telegram_username'),
            $dataCollection->get('role')?->name,
        );
    }
}
