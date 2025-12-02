<?php

use App\Jobs\GetReviewsFromTwoGis;
use App\Jobs\GetReviewsFromYandexMap;
use App\Jobs\GetReviewsFromYandexVendor;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::job(new GetReviewsFromTwoGis)->everyMinute();
Schedule::job(new GetReviewsFromYandexVendor())->everyFifteenMinutes();
Schedule::job(new GetReviewsFromYandexMap())->everyFifteenMinutes();
