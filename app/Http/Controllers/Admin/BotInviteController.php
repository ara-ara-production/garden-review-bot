<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\BotInviteRequest;
use App\Models\BotInvite;
use App\Models\Brunch;
use App\Models\User;
use App\Services\BotInviteService;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class BotInviteController extends Controller
{
    public function __construct(
        protected BotInviteService $botInviteService,
    ) {}

    public function index(): Response
    {
        return Inertia::render('BotInvites/Index', [
            'roles' => UserRoleEnum::toArray(),
            'bots' => $this->botInviteService->botOptions(),
            'users' => User::query()
                ->orderBy('name')
                ->get(['id', 'name'])
                ->map(fn (User $user): array => [
                    'name' => (string) $user->id,
                    'value' => $user->name,
                ])
                ->values()
                ->all(),
            'brunches' => Brunch::query()
                ->orderBy('name')
                ->get(['id', 'name'])
                ->map(fn (Brunch $brunch): array => [
                    'name' => (string) $brunch->id,
                    'value' => $brunch->name,
                ])
                ->values()
                ->all(),
            'invites' => BotInvite::query()
                ->with(['brunch:id,name', 'user:id,name'])
                ->latest()
                ->limit(20)
                ->get()
                ->map(fn (BotInvite $invite): array => [
                    'id' => $invite->id,
                    'driver' => $invite->driver,
                    'bot' => $invite->bot,
                    'role' => $invite->role,
                    'assignment' => $invite->assignment,
                    'name_hint' => $invite->name_hint,
                    'user' => $invite->user?->name,
                    'max_uses' => $invite->max_uses,
                    'used_count' => $invite->used_count,
                    'is_active' => $invite->is_active,
                    'brunch' => $invite->brunch?->name,
                    'link' => $this->botInviteService->buildLink($invite),
                    'expires_at' => $invite->expires_at?->format('Y-m-d H:i'),
                ])
                ->values()
                ->all(),
        ]);
    }

    public function store(BotInviteRequest $request)
    {
        try {
            $invite = $this->botInviteService->create($request->validated());

            return redirect()
                ->route(config('resourseroutes.invite').'.index')
                ->with('message', [
                    'status' => 'success',
                    'text' => 'Ссылка создана: '.$this->botInviteService->buildLink($invite),
                ]);
        } catch (Throwable $exception) {
            return redirect()
                ->back()
                ->with('message', ['status' => 'danger', 'text' => $exception->getMessage()]);
        }
    }
}
