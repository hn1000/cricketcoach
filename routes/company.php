<?php

use App\Http\Controllers\Sites\Company\DashboardController;
use App\Http\Controllers\Sites\Company\StaffController;
use App\Http\Controllers\Sites\Company\BookingController;
use App\Http\Controllers\Sites\Company\EnquiryMessageController;
use Illuminate\Support\Facades\Route;

// Company portal routes (protected by authentication and company middleware)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'company',
])->prefix('company')->name('company.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Staff management
    Route::resource('staff', StaffController::class)->except(['show']);

    // Bookings
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [BookingController::class, 'index'])->name('index');
        Route::get('/{booking}', [BookingController::class, 'show'])->name('show');
        Route::patch('/{booking}/status', [BookingController::class, 'updateStatus'])->name('update-status');
    });

    // Enquiries
    Route::prefix('enquiries')->name('enquiries.')->group(function () {
        Route::get('/', [EnquiryMessageController::class, 'index'])->name('index');
        Route::get('/{enquiry}', [EnquiryMessageController::class, 'show'])->name('show');
        Route::patch('/{enquiry}/status', [EnquiryMessageController::class, 'updateStatus'])->name('update-status');
    });
});
