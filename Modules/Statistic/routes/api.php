<?php

use Illuminate\Support\Facades\Route;
use Modules\Statistic\Http\Controllers\StatisticController;


Route::apiResource('statistics', StatisticController::class)->names('statistic');

