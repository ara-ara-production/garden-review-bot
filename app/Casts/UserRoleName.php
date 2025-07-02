<?php

namespace App\Casts;

use App\Enums\UserRoleEnum;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class UserRoleName implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        return UserRoleEnum::tryFromName($value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return $value instanceof UserRoleEnum ? $value->name : $value;
    }
}
