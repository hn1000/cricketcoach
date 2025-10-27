<?php

use App\Http\Controllers\Sites\Admin\BookingSlotController;
use App\Http\Controllers\Sites\Admin\BookingSlotExceptionController;
use App\Http\Controllers\Sites\Admin\BookingSlotTemplateController;
use App\Http\Controllers\Sites\Admin\CompanyController;
use App\Http\Controllers\Sites\Admin\CompanyManageController;
use App\Http\Controllers\Sites\Admin\DashboardController;
use App\Http\Controllers\Sites\Admin\StaffController;
use Illuminate\Support\Facades\Route;

// Admin routes (all protected by authentication middleware)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('companies', CompanyController::class);

    // Company management routes
    Route::get('companies/{company}/manage/dashboard', [CompanyManageController::class, 'dashboard'])->name('companies.manage.dashboard');

    // Staff management routes (nested under companies)
    Route::resource('companies.staff', StaffController::class)->except(['show']);

    // OLD: Booking slots routes (nested under companies and staff) - Will be removed
    // Route::resource('companies.staff.booking-slots', BookingSlotController::class)->except(['show']);

    // NEW: Availability template routes (recurring weekly schedules)
    Route::resource('companies.staff.availability', BookingSlotTemplateController::class)->except(['show']);

    // Exception routes (one-off unavailable times)
    Route::resource('companies.staff.exceptions', BookingSlotExceptionController::class)->except(['show']);
});
