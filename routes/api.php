<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;



Route::group(['prefix' => 'v1/auth'], function ($router) {
    Route::post('login', [AuthController::class, 'authenticate']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::middleware(['jwt'])->group(function () {
        Route::get('me', [AuthController::class, 'me']);
    });
});