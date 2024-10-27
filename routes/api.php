<?php

use App\Http\Controllers\API\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [AuthController::class, 'login']);

Route::post('/auth/register', [AuthController::class, 'register']);

Route::post('/auth/email/forgot-password', [AuthController::class, 'sendEmailResetPassword']);

Route::group(['middleware' => ['auth:sanctum', 'role:user']], function () {
    /*
    |--------------------------------------------------------------------------
    | Auth Routes
    |--------------------------------------------------------------------------
    |
    | Manage auth data API
    |
    */

    Route::get('/user', [AuthController::class, 'show']);

    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::delete('/auth/remove-account', [AuthController::class, 'removeAccount']);
});
