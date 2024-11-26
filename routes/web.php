<?php

use App\Http\Controllers\Admin\BranchOffices\BranchOfficeController;
use App\Http\Controllers\Admin\Deliveries\DeliveryController;
use App\Http\Controllers\Admin\Disasters\DisasterController;
use App\Http\Controllers\Admin\Donations\DonationController;
use App\Http\Controllers\Admin\Donations\DonationPrayerController;
use App\Http\Controllers\Admin\Donations\FundraiserController;
use App\Http\Controllers\Admin\Education\EducationController;
use App\Http\Controllers\Admin\Locations\LocationController;
use App\Http\Controllers\Admin\Logistics\LogisticController;
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

    /*
    |--------------------------------------------------------------------------
    | Donations Routes
    |--------------------------------------------------------------------------
    |
    | Manage donations data API
    |
    */

    Route::get('/donations', [DonationController::class, 'index'])->name('donations');

    Route::get('/donation/create', [DonationController::class, 'create'])->name('donation_create');

    Route::post('/donation/store', [DonationController::class, 'store'])->name('donation_store');

    Route::get('/donation/edit/{id}', [DonationController::class, 'edit'])->name('donation_edit');

    Route::put('/donation/update/{id}', [DonationController::class, 'update'])->name('donation_update');

    Route::delete('/donation/delete/{id}', [DonationController::class, 'destroy'])->name('donation_delete');

    /*
    |--------------------------------------------------------------------------
    | Fundraisers Routes
    |--------------------------------------------------------------------------
    |
    | Manage fundraisers data API
    |
    */

    Route::get('/fundraisers', [FundraiserController::class, 'index'])->name('fundraisers');

    /*
    |--------------------------------------------------------------------------
    | Prayers Routes
    |--------------------------------------------------------------------------
    |
    | Manage prayers data API
    |
    */

    Route::get('/prayers', [DonationPrayerController::class, 'index'])->name('prayers');

    /*
    |--------------------------------------------------------------------------
    | Logistics Routes
    |--------------------------------------------------------------------------
    |
    | Manage logistics data API
    |
    */

    Route::get('/logistics', [LogisticController::class, 'index'])->name('logistics');

    /*
    |--------------------------------------------------------------------------
    | Disasters Routes
    |--------------------------------------------------------------------------
    |
    | Manage disasters data API
    |
    */

    Route::get('/disasters', [DisasterController::class, 'index'])->name('disasters');

    /*
    |--------------------------------------------------------------------------
    | Deliveries Routes
    |--------------------------------------------------------------------------
    |
    | Manage deliveries data API
    |
    */

    Route::get('/deliveries', [DeliveryController::class, 'index'])->name('deliveries');

    Route::get('{any}', [HomeController::class, 'index'])->name('index');
});
