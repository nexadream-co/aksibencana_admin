<?php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Educations\EducationController;
use App\Http\Controllers\API\Locations\LocationController;
use App\Http\Controllers\API\Notifications\NotificationController;
use App\Http\Controllers\API\Volunteers\VolunteerController;
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

    /*
    |--------------------------------------------------------------------------
    | Volunteer Routes
    |--------------------------------------------------------------------------
    |
    | Manage volunteer data API
    |
    */

    Route::get('/volunteer/detail/{id}', [VolunteerController::class, 'show']);

    Route::post('/volunteer/register', [VolunteerController::class, 'register']);

    Route::get('/volunteer/abilities', [VolunteerController::class, 'abilities']);

    /*
    |--------------------------------------------------------------------------
    | Education Routes
    |--------------------------------------------------------------------------
    |
    | Manage education data API
    |
    */

    Route::get('/educations', [EducationController::class, 'index']);

    /*
    |--------------------------------------------------------------------------
    | Notification Routes
    |--------------------------------------------------------------------------
    |
    | Manage notification data API
    |
    */

    Route::get('/notifications', [NotificationController::class, 'index']);

    /*
    |--------------------------------------------------------------------------
    | Location Routes
    |--------------------------------------------------------------------------
    |
    | Manage location data API
    |
    */

    Route::get('/locations', [LocationController::class, 'index']);
});
