<?php

use App\Http\Controllers\ChartPositionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('/api')->group(function () {
    Route::controller(ChartPositionController::class)->group(function () {
        Route::get('chart-positions', 'index');
    });
});
