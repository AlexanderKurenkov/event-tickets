<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\OrderController;

Route::prefix('v1')->group(function () {
    Route::post('/order', [OrderController::class, 'store']);
});
