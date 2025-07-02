<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\UseCases\Admin\User\DeleteUserUserCase;
use App\UseCases\Admin\User\EditUserUseCase;
use App\UseCases\Admin\User\GetUserListUseCase;
use App\UseCases\Admin\User\GetUserUpdatePageUseCase;
use App\UseCases\Admin\User\SaveUserUseCase;
use Inertia\Inertia;

class UserController extends Controller
{
    public function __construct(
        protected GetUserListUseCase $getUserListUseCase,
        protected SaveUserUseCase $saveUserUseCase,
        protected DeleteUserUserCase $deleteUserUserCase,
        protected GetUserUpdatePageUseCase $getUpdatePageUseCase,
        protected EditUserUseCase $editUserUseCase,
    ) {
    }

    public function index()
    {
        return $this->getUserListUseCase->use();
    }

    public function create()
    {
        return Inertia::render('Users/Create', ['roles' => UserRoleEnum::toArray()]);
    }

    public function store(UserRequest $request)
    {
        return $this->saveUserUseCase->use($request);
    }

    public function show(User $user)
    {
        abort(404);
    }

    public function edit(User $user)
    {
        return $this->getUpdatePageUseCase->use($user);
    }

    public function update(UserRequest $request, User $user)
    {
        return $this->editUserUseCase->use($request, $user);
    }

    public function destroy(User $user)
    {
        return $this->deleteUserUserCase->use($user);
    }
}
