<?php

namespace App\UseCases\Admin\Brunch;

use App\Services\BrunchService;
use Inertia\Inertia;
use PHPUnit\Framework\Attributes\CodeCoverageIgnore;

class GetBrunchListUseCase
{
    #[CodeCoverageIgnore]
    public function __construct(
        protected BrunchService $brunchService
    ){}

    public function use()
    {
        try {
            $paginator = $this->brunchService->getPaginator();

            return Inertia::render(
                'Brunch/Index',
                [
                    'paginator' => $paginator,
                ]
            );
        } catch (\Throwable $exception) {
            return redirect()->back()->with('message', ['status' => 'danger', 'text' => $exception->getMessage()]);
        }
    }
}
