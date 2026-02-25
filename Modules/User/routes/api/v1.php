<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\Api\UsersController;


Route::apiResource('users', UsersController::class)->names('users');


Route::post('/users/bulk', [UsersController::class, 'storeBulk']);


//for testing
Route::get('/create-users-json', [UsersController::class, 'createUsersJson']);

