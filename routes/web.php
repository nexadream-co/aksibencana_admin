<?php

use App\Http\Controllers\Admin\BranchOffices\BranchOfficeController;
use App\Http\Controllers\Admin\Education\EducationController;
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

    Route::get('{any}', [HomeController::class, 'index'])->name('index');
});
