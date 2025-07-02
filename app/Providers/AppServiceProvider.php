<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use PHPUnit\Framework\Attributes\CodeCoverageIgnore;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[CodeCoverageIgnore]
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    #[CodeCoverageIgnore]
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}
