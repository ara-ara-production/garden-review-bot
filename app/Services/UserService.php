<?php

namespace App\Services;

use App\Dto\Admin\Entity\UserCreateDto;
use App\Models\User;
use App\Models\UserMessengerAccount;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

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
    public function saveUser(UserCreateDto $dto): void
    {
        DB::transaction(function () use ($dto): void {
            $user = User::create([
                'name' => $dto->name,
                'email' => $dto->email,
                'password' => $dto->password,
                'role' => $dto->role,
            ]);

            if (! $user instanceof User) {
                throw new Exception('Пользователь не создан!');
            }

            $this->syncMessengerAccounts($user, $dto);
        });
    }

    public function editUser(UserCreateDto $dto, User $user): void
    {
        DB::transaction(function () use ($dto, $user): void {
            $dtoValues = [
                'name' => $dto->name,
                'email' => $dto->email,
                'role' => $dto->role,
            ];

            if ($dto->password) {
                $dtoValues['password'] = $dto->password;
            }

            $status = $user->update($dtoValues);

            if (! $status) {
                throw new Exception('Пользователь не обновлен!');
            }

            $this->syncMessengerAccounts($user, $dto);
        });
    }

    public function deleteUser(User $user): void
    {
        $status = $user->delete();

        if (! $status) {
            throw new Exception('Произошла ошибка!');
        }
    }

    protected function syncMessengerAccounts(User $user, UserCreateDto $dto): void
    {
        $this->syncMessengerAccount($user, 'telegram', $dto->telegram_username, null);
        $this->syncMessengerAccount($user, 'vk', null, $dto->vk_user_id);
    }

    protected function syncMessengerAccount(User $user, string $driver, ?string $username, ?string $externalId): void
    {
        if (blank($username) && blank($externalId)) {
            $user->messengerAccounts()->where('driver', $driver)->delete();
            $user->botSubscriptions()->where('driver', $driver)->delete();

            return;
        }

        UserMessengerAccount::updateOrCreate(
            [
                'user_id' => $user->id,
                'driver' => $driver,
            ],
            [
                'username' => $username,
                'external_id' => $externalId,
            ],
        );
    }
}
