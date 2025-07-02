<?php

namespace App\Dto\Admin\Entity;

class UserUpdateDto
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $email,
        public ?string $telegram_username,
        public ?string $role
    ){}
}
