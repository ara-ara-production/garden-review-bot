<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\BotInviteController;
use App\Http\Controllers\Admin\BrunchController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BotWebhookController;
use App\Http\Controllers\ReviewController;
use App\Http\Middleware\CheckTokenInUrl;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;

Route::post('bots/{bot}/webhook', [BotWebhookController::class, 'handle'])
    ->withoutMiddleware([VerifyCsrfToken::class]);

Route::prefix('{token}/reviews')->middleware([CheckTokenInUrl::class])->group(function () {
    Route::get('/', [ReviewController::class, 'index']);
    Route::get('export', [ReviewController::class, 'export']);
    Route::get('stats', [ReviewController::class, 'stats']);
});

Route::get('reviews/find-and-notify', [ReviewController::class, 'findAndNotify']);

Route::middleware('guest')->group(function () {
    Route::inertia('/', 'Welcome');

    Route::prefix('login')->group(function () {
        Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('/', [AuthenticatedSessionController::class, 'store']);
    });
});

//Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);

    Route::prefix(config('resourseroutes.backendprefix'))->group(function () {
        Route::inertia('/', 'WelcomeBackend')->name('dashboard');
        Route::resource(config('resourseroutes.user'), UserController::class);
        Route::resource(config('resourseroutes.brunch'), BrunchController::class);
        Route::get(config('resourseroutes.invite'), [BotInviteController::class, 'index'])->name(
            config('resourseroutes.invite').'.index'
        );
        Route::post(config('resourseroutes.invite'), [BotInviteController::class, 'store'])->name(
            config('resourseroutes.invite').'.store'
        );
        Route::get('logs', [LogViewerController::class, 'index']);
    });
//});
