<?php

use Illuminate\Support\Facades\Route;
use Modules\Statistic\Http\Controllers\StatisticController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('statistics', StatisticController::class)->names('statistic');
});
