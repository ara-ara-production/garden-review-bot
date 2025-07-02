<?php

namespace App\UseCases\Admin\Brunch;

use App\Dto\Admin\Factory\BrunchCreateDtoFactory;
use App\Http\Requests\BrunchRequest;
use App\Models\Brunch;
use App\Services\BrunchService;
use Illuminate\Http\RedirectResponse;
use PHPUnit\Framework\Attributes\CodeCoverageIgnore;

class EditBrunchUseCase
{
    #[CodeCoverageIgnore]
    public function __construct(
        protected BrunchService $brunchService,
        protected BrunchCreateDtoFactory $brunchCreateDtoFactory,
    ) {
    }

    public function use(BrunchRequest $request, Brunch $user): string|RedirectResponse
    {
        try {
            $data = $request->validated();
            $dto = $this->brunchCreateDtoFactory->fromArray($data);
            $this->brunchService->edit($dto, $user);

            return redirect()
                ->route(config('resourseroutes.brunch') . '.index')
                ->with('message', ['status' => 'success', 'text' => 'Пользователь успешно создан']);

        } catch (\Throwable $exception) {
            return redirect()->back()->with('message', ['status' => 'danger', 'text' => $exception->getMessage()]);
        }
    }
}
