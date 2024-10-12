<?php

use App\Http\Controllers\API\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::post('/register', [AuthController::class, 'register']);

Route::post('/resend-verification', [AuthController::class, 'resendVerification']);

Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    /*
    |--------------------------------------------------------------------------
    | Auth Routes
    |--------------------------------------------------------------------------
    |
    | Manage auth data API
    |
    */

    Route::get('/user', [AuthController::class, 'show']);

    Route::post('/logout', [AuthController::class, 'logout']);
});
