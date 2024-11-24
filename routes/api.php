<?php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\BranchOffices\BranchOfficeController;
use App\Http\Controllers\API\Disasters\DisasterController;
use App\Http\Controllers\API\Educations\EducationController;
use App\Http\Controllers\API\Locations\LocationController;
use App\Http\Controllers\API\Logistics\LogisticController;
use App\Http\Controllers\API\Media\MediaController;
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

    Route::put('/user/update', [AuthController::class, 'updateUser']);

    Route::post('/change-password', [AuthController::class, 'changePassword']);

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

    Route::put('/volunteer/update', [VolunteerController::class, 'update']);

    Route::patch('/volunteer/update-status', [VolunteerController::class, 'updateStatusVolunteer']);

    Route::patch('/volunteer/update-availability-status', [VolunteerController::class, 'updateAvailabilityStatusVolunteer']);

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
    | Media Routes
    |--------------------------------------------------------------------------
    |
    | Manage Media data API
    |
    */

    Route::post('/media/store-file', [MediaController::class, 'storeFile']);

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

    /*
    |--------------------------------------------------------------------------
    | Branch Office Routes
    |--------------------------------------------------------------------------
    |
    | Manage branch offices data APIs
    |
    */

    Route::get('/branch-offices', [BranchOfficeController::class, 'index']);


    /*
    |--------------------------------------------------------------------------
    | Disaster
    |--------------------------------------------------------------------------
    |
    | Manage disaster data APIs
    |
    */

    Route::get('/disaster/categories', [DisasterController::class, 'categories']);

    Route::post('/disaster/create', [DisasterController::class, 'store']);

    Route::get('/disasters', [DisasterController::class, 'index']);

    Route::get('/disaster/{id}', [DisasterController::class, 'show']);

    Route::get('/disasters/mine', [DisasterController::class, 'me']);

    /*
    |--------------------------------------------------------------------------
    | Logistics
    |--------------------------------------------------------------------------
    |
    | Manage logistics data APIs
    |
    */

    Route::get('/logistics', [LogisticController::class, 'index']);

    Route::get('/logistic/{id}', [LogisticController::class, 'show']);

    Route::get('/logistic/{id}/receipt-tracks', [LogisticController::class, 'logisticReceiptTracks']);

    Route::post('/logistic/create', [LogisticController::class, 'store']);
});
