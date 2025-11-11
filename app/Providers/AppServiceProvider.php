<?php

namespace App\Providers;

use App\Services\ReviewService;
use Illuminate\Support\Facades\URL;
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
        if($this->app->environment('production')) {
            $this->app['request']->server->set('HTTPS', true);
            URL::forceScheme('https');
        }
        Vite::prefetch(concurrency: 3);

        config(['review_table_prefix' => app(ReviewService::class)->getUrlToken()]);
    }
}
