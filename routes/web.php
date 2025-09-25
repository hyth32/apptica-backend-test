<?php

use App\Http\Controllers\ChartPositionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('/api')->middleware('throttle.api')->group(function () {
    Route::prefix('/v1')->group(function () {
        Route::controller(ChartPositionController::class)->group(function () {
            Route::get('chart-positions', 'index');
        });
    });
});
