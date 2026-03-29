<?php

use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\WorkshopController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'Welcome', [
	'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
	Route::inertia('dashboard', 'Dashboard')->name('dashboard');

	// Dashboard route
	Route::get('/dashboard', [WorkshopController::class, 'index'])->name('dashboard');

	// Registration Routes 
	Route::post('/workshops/{workshop}/register', [RegistrationController::class, 'store'])->name('workshop.register');
	Route::delete('/workshops/{workshop}/unregister', [RegistrationController::class, 'destroy'])->name('workshop.unregister');

	// Admin Routes
	Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
		Route::resource('workshops', WorkshopController::class)->except(['index', 'show']);
	});

});

require __DIR__ . '/settings.php';
