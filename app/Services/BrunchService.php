<?php

namespace App\Services;

use App\Dto\Admin\Entity\BrunchCreateDto;
use App\Models\Brunch;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

class BrunchService
{
    public function getPaginator(): LengthAwarePaginator
    {
        return Brunch::dataForIndex()
            ->paginate(20);
    }

    /**
     * @throws Exception если не удалость создать пользователя
     */
    public function save(BrunchCreateDTO $dto): void
    {
        $brunch = Brunch::create((array)$dto);

        if (!$brunch) {
            throw new Exception('Пользователь не создан!');
        }
    }

    public function edit(BrunchCreateDTO $dto, Brunch $brunch): void
    {
        $status = $brunch->update((array)$dto);

        if (!$status) {
            throw new Exception('Пользователь не обновлен!');
        }
    }

    public function delete(Brunch $brunch): void
    {
        $status = $brunch->delete();

        if (!$status) {
            throw new Exception('Произошла ошибка!');
        }
    }
}
