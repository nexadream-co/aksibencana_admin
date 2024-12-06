<?php

use App\Http\Controllers\Admin\BranchOffices\BranchOfficeController;
use App\Http\Controllers\Admin\Deliveries\DeliveryController;
use App\Http\Controllers\Admin\Disasters\DisasterController;
use App\Http\Controllers\Admin\Disasters\DisasterStationContoller;
use App\Http\Controllers\Admin\Donations\DonationController;
use App\Http\Controllers\Admin\Donations\DonationPrayerController;
use App\Http\Controllers\Admin\Donations\FundraiserController;
use App\Http\Controllers\Admin\Education\EducationController;
use App\Http\Controllers\Admin\Home\HomeController;
use App\Http\Controllers\Admin\Locations\LocationController;
use App\Http\Controllers\Admin\Logistics\LogisticController;
use App\Http\Controllers\Admin\Users\UserController;
use App\Http\Controllers\Admin\Volunteers\AbilityController;
use App\Http\Controllers\Admin\Volunteers\VolunteerAssignmentController;
use App\Http\Controllers\Admin\Volunteers\VolunteerController;
use App\Http\Controllers\HomeController as ControllersHomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes(['register' => false]);

Route::middleware(['auth', 'role:admin|superadmin'])->group(function () {
    Route::get('/', [HomeController::class, 'index']);

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
    | Volunteers Routes
    |--------------------------------------------------------------------------
    |
    | Manage volunteers data API
    |
    */

    Route::get('/volunteer/{id}/assignments', [VolunteerAssignmentController::class, 'index'])->name('volunteer_assignments');

    Route::get('/volunteer/{id}/assignment/create', [VolunteerAssignmentController::class, 'create'])->name('volunteer_assignment_create');
    
    Route::post('/volunteer/{id}/assignment/store', [VolunteerAssignmentController::class, 'store'])->name('volunteer_assignment_store');

    Route::get('/volunteer/{id}/assignment/edit/{assignment_id}', [VolunteerAssignmentController::class, 'edit'])->name('volunteer_assignment_edit');

    Route::put('/volunteer/{id}/assignment/update/{assignment_id}', [VolunteerAssignmentController::class, 'update'])->name('volunteer_assignment_update');

    Route::delete('/volunteer/{id}/assignment/destroy/{assignment_id}', [VolunteerAssignmentController::class, 'destroy'])->name('volunteer_assignment_delete');

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

    Route::get('/branch-office/create', [BranchOfficeController::class, 'create'])->name('branch_office_create');

    Route::get('/branch-office/edit/{id}', [BranchOfficeController::class, 'edit'])->name('branch_office_edit');

    Route::post('/branch-office/store', [BranchOfficeController::class, 'store'])->name('branch_office_store');

    Route::put('/branch-office/update/{id}', [BranchOfficeController::class, 'update'])->name('branch_office_update');

    Route::delete('/branch-office/delete/{id}', [BranchOfficeController::class, 'destroy'])->name('branch_office_delete');

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

    Route::get('/users', [UserController::class, 'index'])->name('users');

    Route::get('/user/create', [UserController::class, 'create'])->name('user_create');

    Route::post('/user/store', [UserController::class, 'store'])->name('user_store');
    
    Route::delete('/user/delete/{id}', [UserController::class, 'destroy'])->name('user_delete');

    Route::post('/user/reset-password/{id}', [UserController::class, 'resetPassword'])->name('user_reset_password');

    Route::get('/user/search', [UserController::class, 'searchUsers'])->name('user_search');

    Route::get('/user/profile', [UserController::class, 'profile'])->name('user_profile');

    Route::put('/user/profile/update', [UserController::class, 'profileUpdate'])->name('user_profile_update');

    Route::put('/user/profile/change-password', [UserController::class, 'profileChangePassword'])->name('user_profile_change_password');

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

    Route::get('/logistic/create', [LogisticController::class, 'create'])->name('logistic_create');

    Route::get('/logistic/edit/{id}', [LogisticController::class, 'edit'])->name('logistic_edit');

    Route::post('/logistic/store', [LogisticController::class, 'store'])->name('logistic_store');

    Route::put('/logistic/update/{id}', [LogisticController::class, 'update'])->name('logistic_update');

    Route::delete('/logistic/delete/{id}', [LogisticController::class, 'destroy'])->name('logistic_delete');

    /*
    |--------------------------------------------------------------------------
    | Disasters Routes
    |--------------------------------------------------------------------------
    |
    | Manage disasters data API
    |
    */

    Route::get('/disasters', [DisasterController::class, 'index'])->name('disasters');

    Route::get('/disaster/create', [DisasterController::class, 'create'])->name('disaster_create');

    Route::get('/disaster/search', [DisasterController::class, 'searchDisasters'])->name('disaster_search');

    Route::get('/disaster/search/station', [DisasterController::class, 'searchDisasterStation'])->name('disaster_search_station');

    Route::get('/disaster/edit/{id}', [DisasterController::class, 'edit'])->name('disaster_edit');

    Route::post('/disaster/store', [DisasterController::class, 'store'])->name('disaster_store');

    Route::put('/disaster/update/{id}', [DisasterController::class, 'update'])->name('disaster_update');

    Route::delete('/disaster/delete/{id}', [DisasterController::class, 'destroy'])->name('disaster_delete');

    /*
    |--------------------------------------------------------------------------
    | Disaster station Routes
    |--------------------------------------------------------------------------
    |
    | Manage disaster stations data API
    |
    */

    Route::get('/disaster/{id}/station', [DisasterStationContoller::class, 'index'])->name('disaster_stations');

    Route::get('/disaster/{id}/create', [DisasterStationContoller::class, 'create'])->name('disaster_station_create');

    Route::post('/disaster/{id}/store', [DisasterStationContoller::class, 'store'])->name('disaster_station_store');

    Route::get('/disaster/{id}/edit/{station_id}', [DisasterStationContoller::class, 'edit'])->name('disaster_station_edit');

    Route::put('/disaster/{id}/update/{station_id}', [DisasterStationContoller::class, 'update'])->name('disaster_station_update');

    Route::delete('/disaster/{id}/delete/{station_id}', [DisasterStationContoller::class, 'destroy'])->name('disaster_station_delete');

    /*
    |--------------------------------------------------------------------------
    | Deliveries Routes
    |--------------------------------------------------------------------------
    |
    | Manage deliveries data API
    |
    */

    Route::get('/deliveries', [DeliveryController::class, 'index'])->name('deliveries');

    Route::get('/delivery/create', [DeliveryController::class, 'create'])->name('delivery_create');

    Route::post('/delivery/store', [DeliveryController::class, 'store'])->name('delivery_store');

    Route::get('{any}', [ControllersHomeController::class, 'index'])->name('index');
});
