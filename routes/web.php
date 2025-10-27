<?php

use Illuminate\Support\Facades\Route;

// Include public routes
require __DIR__.'/public.php';

// Include admin routes
require __DIR__.'/admin.php';

// Jetstream dashboard (keeping for backwards compatibility)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('admin.dashboard');
    })->name('dashboard');
});
