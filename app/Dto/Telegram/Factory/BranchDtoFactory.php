<?php

namespace App\Dto\Telegram\Factory;

use App\Dto\Telegram\Entity\BranchDto;
use App\Models\Brunch;

class BranchDtoFactory
{
    public static function create(?Brunch $model = null): BranchDto
    {
        return new BranchDto(
            id: $model->id ?? 0,
            name: $model->name ?? 'Неизвестная кофейня',
            upr: $model->user->name ?? '-',
        );
    }
}
