<?php

namespace App\Dto\Admin\Entity;

use App\Enums\UserRoleEnum;

class UserCreateDto
{
    public function __construct(
        public string $name,
        public ?string $email,
        public ?string $password,
        public ?string $telegram_username,
        public ?string $vk_user_id,
        public ?UserRoleEnum $role
    ) {}
}
