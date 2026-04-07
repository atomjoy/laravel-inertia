<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
	return Inertia::render('Welcome', [
		'canRegister' => Features::enabled(Features::registration()),
	]);
})->name('home');

Route::get('dashboard', function () {
	return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('users', function () {
	return Inertia::render('Users');
	// })->middleware(['auth', 'verified'])->name('users');
})->name('users');

Route::resource('payments', PaymentController::class);

// Display files, images from storage
require __DIR__ . '/media.php';

// Settings
require __DIR__ . '/settings.php';
