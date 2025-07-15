<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\BrunchRequest;
use App\Models\Brunch;
use App\Models\User;
use App\UseCases\Admin\Brunch\DeleteBrunchUseCase;
use App\UseCases\Admin\Brunch\EditBrunchUseCase;
use App\UseCases\Admin\Brunch\GetBrunchListUseCase;
use App\UseCases\Admin\Brunch\GetBrunchUpdatePageUseCase;
use App\UseCases\Admin\Brunch\SaveBrunchUseCase;
use Inertia\Inertia;

class BrunchController extends Controller
{
    public function __construct(
        protected GetBrunchListUseCase $getUserListUseCase,
        protected SaveBrunchUseCase $saveUserUseCase,
        protected DeleteBrunchUseCase $deleteUserUserCase,
        protected GetBrunchUpdatePageUseCase $getUpdatePageUseCase,
        protected EditBrunchUseCase $editUserUseCase,
    ) {
    }

    public function index()
    {
        return $this->getUserListUseCase->use();
    }

    public function create()
    {
        return Inertia::render('Brunch/Create', [
            'users' => User::select('id as name', 'name as value')
                ->whereIn('role', [UserRoleEnum::Control->name])
                ->get()
                ->toArray()]);
    }

    public function store(BrunchRequest $request)
    {
        return $this->saveUserUseCase->use($request);
    }

    public function show(Brunch $brunch)
    {
        abort(404);
    }

    public function edit(Brunch $brunch)
    {
        return $this->getUpdatePageUseCase->use($brunch);
    }

    public function update(BrunchRequest $request, Brunch $brunch)
    {
        return $this->editUserUseCase->use($request, $brunch);
    }

    public function destroy(Brunch $brunch)
    {
        return $this->deleteUserUserCase->use($brunch);
    }
}
