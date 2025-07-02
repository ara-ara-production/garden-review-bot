<?php

namespace App\UseCases\Admin\User;

use App\Services\UserService;
use Inertia\Inertia;
use PHPUnit\Framework\Attributes\CodeCoverageIgnore;

class GetUserListUseCase
{
    #[CodeCoverageIgnore]
    public function __construct(
        protected UserSErvice $userService
    ){}

    public function use()
    {
        try {
            $paginator = $this->userService->getPaginator();

            return Inertia::render(
                'Users/Index',
                [
                    'paginator' => $paginator,
                ]
            );
        } catch (\Throwable $exception) {
            return redirect()->back()->with('message', ['status' => 'danger', 'text' => $exception->getMessage()]);
        }
    }
}
