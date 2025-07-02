<?php

namespace App\UseCases\Admin\Brunch;

use App\Models\Brunch;
use App\Services\BrunchService;
use Illuminate\Http\RedirectResponse;
use PHPUnit\Framework\Attributes\CodeCoverageIgnore;

class DeleteBrunchUseCase
{
    #[CodeCoverageIgnore]
    public function __construct(
        protected BrunchService $brunchService,
    ) {
    }

    public function use(Brunch $user): string|RedirectResponse
    {
        try {
            $this->brunchService->delete($user);

            return redirect()
                ->route(config('resourseroutes.brunch') . '.index')
                ->with('message', ['status' => 'success', 'text' => 'Филиал успешно удален!']);
        } catch (\Throwable $exception) {
            return redirect()->back()->with('message', ['status' => 'danger', 'text' => $exception->getMessage()]);
        }
    }
}
