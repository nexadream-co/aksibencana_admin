<?php

use App\Http\Controllers\Admin\BranchOffices\BranchOfficeController;
use App\Http\Controllers\Admin\Education\EducationController;
use App\Http\Controllers\Admin\Locations\LocationController;
use App\Http\Controllers\Admin\Users\UserController;
use App\Http\Controllers\Admin\Volunteers\AbilityController;
use App\Http\Controllers\Admin\Volunteers\VolunteerController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes(['register' => false]);

Route::middleware(['auth', 'role:admin|superadmin'])->group(function () {
    Route::get('/', [HomeController::class, 'root']);

    /*
    |--------------------------------------------------------------------------
    | Volunteers Routes
    |--------------------------------------------------------------------------
    |
    | Manage volunteers data API
    |
    */

    Route::get('/volunteers', [VolunteerController::class, 'index'])->name('volunteers');

    Route::get('/volunteer/create', [VolunteerController::class, 'create'])->name('volunteer_create');

    Route::get('/volunteer/edit/{id}', [VolunteerController::class, 'edit'])->name('volunteer_edit');

    Route::put('/volunteer/update/{id}', [VolunteerController::class, 'update'])->name('volunteer_update');

    Route::delete('/volunteer/delete/{id}', [VolunteerController::class, 'destroy'])->name('volunteer_delete');

    Route::post('/volunteer/store', [VolunteerController::class, 'store'])->name('volunteer_store');

    /*
    |--------------------------------------------------------------------------
    | Abilities Routes
    |--------------------------------------------------------------------------
    |
    | Manage abilities data API
    |
    */

    Route::get('/abilities', [AbilityController::class, 'index'])->name('abilities');

    Route::get('/ability/create', [AbilityController::class, 'create'])->name('ability_create');

    Route::post('/ability/store', [AbilityController::class, 'store'])->name('ability_store');

    Route::get('/ability/edit/{id}', [AbilityController::class, 'edit'])->name('ability_edit');

    Route::put('/ability/update/{id}', [AbilityController::class, 'update'])->name('ability_update');

    Route::delete('/ability/delete/{id}', [AbilityController::class, 'destroy'])->name('ability_delete');

    /*
    |--------------------------------------------------------------------------
    | Branch Offices Routes
    |--------------------------------------------------------------------------
    |
    | Manage branch offices data API
    |
    */

    Route::get('/branch-offices', [BranchOfficeController::class, 'index'])->name('branch_offices');

    /*
    |--------------------------------------------------------------------------
    | Education Routes
    |--------------------------------------------------------------------------
    |
    | Manage Education data API
    |
    */

    Route::get('/education', [EducationController::class, 'index'])->name('education');

    /*
    |--------------------------------------------------------------------------
    | Location Routes
    |--------------------------------------------------------------------------
    |
    | Manage Location data API
    |
    */

    Route::get('/location/district/search', [LocationController::class, 'searchDistricts'])->name('location_district_search');

    /*
    |--------------------------------------------------------------------------
    | User Routes
    |--------------------------------------------------------------------------
    |
    | Manage User data API
    |
    */

    Route::get('/user/search', [UserController::class, 'searchUsers'])->name('user_search');

    Route::get('{any}', [HomeController::class, 'index'])->name('index');
});
