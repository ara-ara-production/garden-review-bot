<?php

namespace App\Services;

use App\Dto\Admin\Entity\UserCreateDto;
use App\Models\User;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    public function getPaginator(): LengthAwarePaginator
    {
        return User::dataForIndex()
            ->paginate(20);
    }

    /**
     * @throws Exception если не удалость создать пользователя
     */
    public function saveUser(UserCreateDTO $dto): void
    {
        $user = User::create((array)$dto);

        if (!$user) {
            throw new Exception('Пользователь не создан!');
        }
    }

    public function editUser(UserCreateDTO $dto, User $user): void
    {
        $dtoValues = (array)$dto;
        if (!$dto->password) {
            unset($dtoValues['password']);
        }

        $status = $user->update($dtoValues);

        if (!$status) {
            throw new Exception('Пользователь не обновлен!');
        }
    }

    public function deleteUser(User $user): void
    {
        $status = $user->delete();

        if (!$status) {
            throw new Exception('Произошла ошибка!');
        }
    }
}
