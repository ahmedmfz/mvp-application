<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\Api\UsersController;


Route::apiResource('users', UsersController::class)->names('users');

