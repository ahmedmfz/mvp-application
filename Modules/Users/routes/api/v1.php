<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\Http\Controllers\Api\UsersController;


Route::apiResource('users', UsersController::class)->names('users');

