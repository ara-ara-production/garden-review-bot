<?php

namespace App\UseCases\Admin\Brunch;

use App\Dto\Admin\Factory\BrunchCreateDtoFactory;
use App\Http\Requests\BrunchRequest;
use App\Services\BrunchService;
use Illuminate\Http\RedirectResponse;
use PHPUnit\Framework\Attributes\CodeCoverageIgnore;

class SaveBrunchUseCase
{
    #[CodeCoverageIgnore]
    public function __construct(
        protected BrunchService $brunchService,
        protected BrunchCreateDtoFactory $brunchCreateDtoFactory,
    ) {
    }

    public function use(BrunchRequest $request): string|RedirectResponse
    {

        try {
            $data = $request->validated();
            $dto = $this->brunchCreateDtoFactory->fromArray($data);
            $this->brunchService->save($dto);

            $redirect = $request->query('redirectOnCreation')
                ? redirect()->route(config('resourseroutes.brunch') . '.create')
                : redirect()->route(config('resourseroutes.brunch') . '.index');

            return $redirect->with('message', ['status' => 'success', 'text' => 'Пользователь успешно создан']);

        } catch (\Throwable $exception) {
            return redirect()->back()->with('message', ['status' => 'danger', 'text' => $exception->getMessage()]);
        }
    }
}
