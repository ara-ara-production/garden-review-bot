<?php

namespace App\UseCases\Admin\User;

use App\Dto\Admin\Factory\UserCreateDtoFactory;
use App\Http\Requests\UserRequest;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use PHPUnit\Framework\Attributes\CodeCoverageIgnore;

class SaveUserUseCase
{
    #[CodeCoverageIgnore]
    public function __construct(
        protected UserSErvice $userService,
        protected UserCreateDtoFactory $userCreateDtoFactory,
    ) {
    }

    public function use(UserRequest $request): string|RedirectResponse
    {

        try {
            $data = $request->validated();
            $dto = $this->userCreateDtoFactory->fromArray($data);
            $this->userService->saveUser($dto);

            $redirect = $request->query('redirectOnCreation')
                ? redirect()->route(config('resourseroutes.user') . '.create')
                : redirect()->route(config('resourseroutes.user') . '.index');

            return $redirect->with('message', ['status' => 'success', 'text' => 'Пользователь успешно создан']);

        } catch (\Throwable $exception) {
            return redirect()->back()->with('message', ['status' => 'danger', 'text' => $exception->getMessage()]);
        }
    }
}
