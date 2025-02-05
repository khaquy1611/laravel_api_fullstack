<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\RoleController;
use App\Http\Controllers\Api\V1\UserController;



Route::group(['prefix' => 'v1/auth'], function ($router) {
    Route::post('login', [AuthController::class, 'authenticate']);
    Route::post('refresh-token', [AuthController::class, 'refresh']);
    
    Route::middleware(['jwt'])->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });

   
});

Route::prefix('v1/auth')->middleware(['jwt'])->group(function () {
    /* ROLE ROUTES */
    Route::group(['prefix' => 'roles'], function () {
        Route::get('all', [RoleController::class, 'all']);
        Route::delete('delete-multitple', [RoleController::class, 'deleteMultiple']);
    });
    Route::resource('roles', RoleController::class)->except(['create', 'edit']);
    /*------------------*/
    /* USER ROUTES */
    Route::group(['prefix' => 'users'], function () {
        Route::get('all', [UserController::class, 'all']);
        Route::delete('delete-multitple', [UserController::class, 'deleteMultiple']);
    });
    Route::resource('users', UserController::class)->except(['create', 'edit']);
});