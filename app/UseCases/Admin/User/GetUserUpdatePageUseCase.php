<?php

namespace App\UseCases\Admin\User;

use App\Dto\Admin\Factory\UserUpdateDtoFactory;
use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use PHPUnit\Framework\Attributes\CodeCoverageIgnore;

class GetUserUpdatePageUseCase
{
    #[CodeCoverageIgnore]
    public function __construct(
        protected UserUpdateDtoFactory $userUpdateDtoFactory,
    ) {
    }

    public function use(User $user): \Inertia\Response|RedirectResponse
    {
        try {
            $dto = $this->userUpdateDtoFactory->fromModel($user);

            return Inertia::render(
                'Users/Update',
                ['values' => (array)$dto, 'roles' => UserRoleEnum::toArray()]
            );

        } catch (\Throwable $exception) {
            return redirect()->back()->with('message', ['status' => 'danger', 'text' => $exception->getMessage()]);
        }
    }
}
