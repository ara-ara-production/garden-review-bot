<?php

namespace App\UseCases\Admin\User;

use App\Dto\Admin\Factory\UserCreateDtoFactory;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use PHPUnit\Framework\Attributes\CodeCoverageIgnore;

class EditUserUseCase
{
    #[CodeCoverageIgnore]
    public function __construct(
        protected UserSErvice $userService,
        protected UserCreateDtoFactory $userCreateDtoFactory,
    ) {
    }

    public function use(UserRequest $request, User $user): string|RedirectResponse
    {
        try {
            $data = $request->validated();
            $dto = $this->userCreateDtoFactory->fromArray($data);
            $this->userService->editUser($dto, $user);

            return redirect()
                ->route(config('resourseroutes.user') . '.index')
                ->with('message', ['status' => 'success', 'text' => 'Пользователь успешно создан']);

        } catch (\Throwable $exception) {
            return redirect()->back()->with('message', ['status' => 'danger', 'text' => $exception->getMessage()]);
        }
    }
}
