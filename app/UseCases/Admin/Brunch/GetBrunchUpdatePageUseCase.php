<?php

namespace App\UseCases\Admin\Brunch;

use App\Dto\Admin\Factory\BrunchUpdateDtoFactory;
use App\Models\Brunch;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use PHPUnit\Framework\Attributes\CodeCoverageIgnore;

class GetBrunchUpdatePageUseCase
{
    #[CodeCoverageIgnore]
    public function __construct(
        protected BrunchUpdateDtoFactory $brunchUpdateDtoFactory,
    ) {
    }

    public function use(Brunch $user): \Inertia\Response|RedirectResponse
    {
        try {
            $dto = $this->brunchUpdateDtoFactory->fromModel($user);

            return Inertia::render(
                'Brunch/Update',
                ['values' => (array)$dto, 'users' => User::select('id as name', 'name as value')->get()->toArray()]
            );

        } catch (\Throwable $exception) {
            return redirect()->back()->with('message', ['status' => 'danger', 'text' => $exception->getMessage()]);
        }
    }
}
