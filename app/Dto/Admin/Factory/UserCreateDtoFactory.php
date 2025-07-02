<?php

namespace App\Dto\Admin\Factory;

use App\Dto\Admin\Entity\UserCreateDto;
use App\Enums\UserRoleEnum;

class UserCreateDtoFactory
{
    public function fromArray(array $data): UserCreateDto
    {
        $dataCollection = collect($data);

//        dd($dataCollection);
        return new UserCreateDto(
            $dataCollection->get('name'),
            $dataCollection->get('email'),
            $dataCollection->get('password'),
            $dataCollection->get('telegram_username'),
            UserRoleEnum::tryFromName($dataCollection->get('role'))
        );
    }
}
