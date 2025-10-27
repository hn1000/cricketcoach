<?php

use App\Http\Controllers\Sites\Public\BookingController;
use App\Http\Controllers\Sites\Public\CompanyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public API endpoints for booking flow (no authentication required)
Route::get('/companies/{company}/staff', [CompanyController::class, 'staff'])->name('api.companies.staff');
Route::get('/companies/{company}/staff/{staff}/availability', [CompanyController::class, 'availability'])->name('api.companies.staff.availability');
Route::post('/companies/{company}/staff/{staff}/validate-slot', [CompanyController::class, 'validateSlot'])->name('api.companies.staff.validate-slot');
Route::post('/bookings', [BookingController::class, 'store'])->name('api.bookings.store');
