<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::post('/reviews', [ReviewController::class, 'create'])
    ->middleware('auth:sanctum');


Route::post('/auth', [ApiAuthController::class, 'apiLogin']);
