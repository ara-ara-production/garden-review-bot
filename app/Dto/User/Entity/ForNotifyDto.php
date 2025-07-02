<?php

namespace App\Dto\User\Entity;

use App\Enums\UserRoleEnum;

class ForNotifyDto
{
    public function __construct(
        public int $id,
        public string $role,
        public string $chat_id,
    )
    {
    }

    public function isKeyborded()
    {
        return in_array($this->role, [
            UserRoleEnum::Control->name
        ]);
    }
}
