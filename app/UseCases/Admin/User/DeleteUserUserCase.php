<?php

namespace App\UseCases\Admin\User;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use PHPUnit\Framework\Attributes\CodeCoverageIgnore;

class DeleteUserUserCase
{
    #[CodeCoverageIgnore]
    public function __construct(
        protected UserSErvice $userService,
    ) {
    }

    public function use(User $user): string|RedirectResponse
    {
        try {
            $this->userService->deleteUser($user);

            return redirect()
                ->route(config('resourseroutes.user') . '.index')
                ->with('message', ['status' => 'success', 'text' => 'Пользователь успешно удален!']);
        } catch (\Throwable $exception) {
            return redirect()->back()->with('message', ['status' => 'danger', 'text' => $exception->getMessage()]);
        }
    }
}
