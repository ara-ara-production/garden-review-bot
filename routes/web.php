<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\BrunchController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TelegramController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;

Route::prefix('tg-bot')->group(function () {
    Route::prefix(config('telegram.webhook_prefix'))->group(function () {
        Route::get('/', fn() => Telegram::bot()->getMe());

        Route::post('webhook', [TelegramController::class, 'getWebhookUpdate'])->withoutMiddleware(
            [VerifyCsrfToken::class]
        );
    });
});

Route::prefix('reviews')->group(function () {
    Route::prefix(config('resourseroutes.review_table_prefix'))->get('/', [ReviewController::class, 'index']);
    Route::prefix(config('resourseroutes.review_table_prefix'))->get('export', [ReviewController::class, 'export']);
    Route::prefix(config('resourseroutes.review_table_prefix'))->get('stats', [ReviewController::class, 'stats']);
    Route::get('find-and-notify', [ReviewController::class, 'findAndNotify']);
});

Route::middleware('guest')->group(function () {
    Route::inertia('/', 'Welcome');

    Route::prefix('login')->group(function () {
        Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('/', [AuthenticatedSessionController::class, 'store']);
    });
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);


    Route::prefix(config('resourseroutes.backendprefix'))->group(function () {
        Route::inertia('/', 'WelcomeBackend')->name('dashboard');
        Route::resource(config('resourseroutes.user'), UserController::class);
        Route::resource(config('resourseroutes.brunch'), BrunchController::class);
        Route::get('logs', [LogViewerController::class, 'index']);
    });
});

