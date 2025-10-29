<?php

use App\Http\Controllers\Sites\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Sites\Admin\BookingSlotController;
use App\Http\Controllers\Sites\Admin\BookingSlotExceptionController;
use App\Http\Controllers\Sites\Admin\BookingSlotTemplateController;
use App\Http\Controllers\Sites\Admin\CompanyCalendarController;
use App\Http\Controllers\Sites\Admin\CompanyController;
use App\Http\Controllers\Sites\Admin\CompanyManageController;
use App\Http\Controllers\Sites\Admin\DashboardController;
use App\Http\Controllers\Sites\Admin\EnquiryMessageController;
use App\Http\Controllers\Sites\Admin\OrderController;
use App\Http\Controllers\Sites\Admin\StaffCalendarController;
use App\Http\Controllers\Sites\Admin\StaffController;
use Illuminate\Support\Facades\Route;

// Admin routes (protected by authentication and admin middleware)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'admin',
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

    // Staff Calendar routes
    Route::get('companies/{company}/staff/{staff}/calendar', [StaffCalendarController::class, 'index'])->name('companies.staff.calendar.index');
    Route::get('companies/{company}/staff/{staff}/calendar/events', [StaffCalendarController::class, 'events'])->name('companies.staff.calendar.events');

    // Company Calendar routes
    Route::get('companies/{company}/calendar', [CompanyCalendarController::class, 'index'])->name('companies.calendar.index');
    Route::get('companies/{company}/calendar/bookings', [CompanyCalendarController::class, 'bookings'])->name('companies.calendar.bookings');

    // Booking management routes
    Route::get('bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::patch('bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.updateStatus');
    Route::post('bookings/{booking}/cancel', [AdminBookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('bookings/{booking}/resend-confirmation', [AdminBookingController::class, 'resendConfirmation'])->name('bookings.resendConfirmation');

    // Order management routes
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('orders/{order}/refund', [OrderController::class, 'refund'])->name('orders.refund');

    // Enquiry management routes
    Route::prefix('enquiries')->name('enquiries.')->group(function () {
        Route::get('/', [EnquiryMessageController::class, 'index'])->name('index');
        Route::get('/{enquiry}', [EnquiryMessageController::class, 'show'])->name('show');
        Route::patch('/{enquiry}/status', [EnquiryMessageController::class, 'updateStatus'])->name('update-status');
    });
});
