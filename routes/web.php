<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
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

// Display images
require __DIR__ . '/images.php';

// Settings
require __DIR__ . '/settings.php';
