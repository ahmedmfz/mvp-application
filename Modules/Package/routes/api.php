<?php

use Illuminate\Support\Facades\Route;
use Modules\Package\Http\Controllers\Api\PackagesController;
use Modules\Package\Http\Controllers\Api\SubscriptionsController;

/*
|--------------------------------------------------------------------------
| Package API Routes
|--------------------------------------------------------------------------
| Package CRUD  → admin only  (auth:sanctum + role:admin)
| Subscriptions → any authenticated consumer  (auth:sanctum)
*/

// Admin-only: manage packages
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('v1')->group(function () {
    Route::apiResource('packages', PackagesController::class)->names('packages');
});

// Any authenticated consumer: subscribe & view history
Route::middleware(['auth:sanctum' , 'role:consumer'])->prefix('v1')->group(function () {
    Route::post('/subscriptions',  [SubscriptionsController::class, 'subscribe'])->name('subscriptions.subscribe');
    Route::get('/subscriptions',   [SubscriptionsController::class, 'history'])->name('subscriptions.history');
});
