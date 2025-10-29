<?php

use App\Http\Controllers\Sites\Public\BookingController;
use App\Http\Controllers\Sites\Public\CheckoutController;
use App\Http\Controllers\Sites\Public\CompanyController;
use App\Http\Controllers\Sites\Public\EnquiryMessageController;
use App\Http\Controllers\Sites\Public\HomeController;
use App\Http\Controllers\Sites\Public\PageController;
use App\Http\Controllers\Webhooks\StripeWebhookController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Static content pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/how-it-works', [PageController::class, 'howItWorks'])->name('how-it-works');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'contactSubmit'])->name('contact.submit');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');

// Company browsing and details
Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
Route::get('/companies/{company}', [CompanyController::class, 'show'])->name('companies.show');

// Booking flow
Route::prefix('booking')->name('booking.')->group(function () {
    Route::get('/companies/{company}/staff/{staff}', [BookingController::class, 'create'])->name('create');

    // Authenticated routes (booking creation requires login)
    Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
        Route::post('/store', [BookingController::class, 'store'])->name('store');
    });

    Route::get('/confirmation/{confirmationToken}', [BookingController::class, 'confirmation'])->name('confirmation');
    Route::get('/cancel/{confirmationToken}', [BookingController::class, 'cancelForm'])->name('cancel.form');
    Route::post('/cancel/{confirmationToken}', [BookingController::class, 'cancel'])->name('cancel');
});

// Checkout and payment (requires authentication)
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/{order}', [CheckoutController::class, 'show'])->name('show');
    Route::post('/{order}/payment-intent', [CheckoutController::class, 'createPaymentIntent'])->name('payment-intent');
    Route::post('/{order}/confirm', [CheckoutController::class, 'confirm'])->name('confirm');
});

// Enquiry system (messaging instead of booking)
Route::prefix('enquiry')->name('enquiry.')->group(function () {
    Route::get('/companies/{company}/staff/{staff}', [EnquiryMessageController::class, 'create'])->name('create');

    Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
        Route::post('/companies/{company}/staff/{staff}', [EnquiryMessageController::class, 'store'])->name('store');
        Route::get('/success/{enquiry}', [EnquiryMessageController::class, 'success'])->name('success');
    });
});

// Stripe webhook (must not have CSRF protection)
Route::post('/webhooks/stripe', [StripeWebhookController::class, 'handle'])->name('webhooks.stripe');
