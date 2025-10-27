<?php

use Illuminate\Support\Facades\Route;

// Include public routes
require __DIR__.'/public.php';

// Include admin routes
require __DIR__.'/admin.php';

// Dashboard redirect based on user role
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        // Redirect based on role
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('customer.dashboard');
    })->name('dashboard');
});

// Include customer routes
require __DIR__.'/customer.php';
