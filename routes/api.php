<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\RoleController;



Route::group(['prefix' => 'v1/auth'], function ($router) {
    Route::post('login', [AuthController::class, 'authenticate']);
    Route::post('refresh-token', [AuthController::class, 'refresh']);
    
    Route::middleware(['jwt'])->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });

   
});

Route::prefix('v1/auth')->middleware(['jwt'])->group(function () {
    Route::group(['prefix' => 'roles'], function () {
        Route::get('all', [RoleController::class, 'all']);
        Route::resource('', RoleController::class)->except(['create', 'edit']);
    });
});