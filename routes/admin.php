<?php

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
});
