<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Auth API Routes
|--------------------------------------------------------------------------
| Public auth endpoints – no authentication middleware required here.
| After login, protected routes across all modules should use:
|   middleware('auth:api_token')
|
| User type reference:
|   admin    – full access to all resources
|   consumer – restricted to their own data
*/

Route::prefix('v1')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');
});
