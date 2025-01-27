<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;

Route::get('v1/auth/login', [AuthController::class, 'authenticate']);