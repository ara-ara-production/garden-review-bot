<?php

namespace App\Http\Middleware;

use App\Services\ReviewService;
use Illuminate\Http\Request;
use Inertia\Middleware;
use PHPUnit\Framework\Attributes\CodeCoverageIgnore;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    #[CodeCoverageIgnore]
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'csrf' => csrf_token(),
            'routes' => [
                ...config('resourseroutes'),
                'review_table_prefix' => app(ReviewService::class)->getUrlToken()
                ],
            'flash' => [
                'message' => fn () => $request->session()->get('message')
            ],
        ];
    }
}
