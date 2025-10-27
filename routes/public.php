<?php

use App\Http\Controllers\Sites\Public\BookingController;
use App\Http\Controllers\Sites\Public\CheckoutController;
use App\Http\Controllers\Sites\Public\CompanyController;
use App\Http\Controllers\Sites\Public\HomeController;
use App\Http\Controllers\Webhooks\StripeWebhookController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Company browsing and details
Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
Route::get('/companies/{company}', [CompanyController::class, 'show'])->name('companies.show');

// Booking flow
Route::prefix('booking')->name('booking.')->group(function () {
    Route::get('/companies/{company}/staff/{staff}', [BookingController::class, 'create'])->name('create');
    Route::get('/confirmation/{confirmationToken}', [BookingController::class, 'confirmation'])->name('confirmation');
    Route::get('/cancel/{confirmationToken}', [BookingController::class, 'cancelForm'])->name('cancel.form');
    Route::post('/cancel/{confirmationToken}', [BookingController::class, 'cancel'])->name('cancel');
});

// Checkout and payment
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/{order}', [CheckoutController::class, 'show'])->name('show');
    Route::post('/{order}/payment-intent', [CheckoutController::class, 'createPaymentIntent'])->name('payment-intent');
    Route::post('/{order}/confirm', [CheckoutController::class, 'confirm'])->name('confirm');
});

// Stripe webhook (must not have CSRF protection)
Route::post('/webhooks/stripe', [StripeWebhookController::class, 'handle'])->name('webhooks.stripe');
