<?php

// use App\Http\Controllers\ClientOrdersController;
// use App\Http\Controllers\ClientPaymentsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Client panel
Route::group([
	'middleware' => ['auth', 'verified']
], function () {

	Route::get('dashboard', function () {
		return Inertia::render('Dashboard');
	})->name('dashboard');

	// Route::resource('orders', ClientOrdersController::class);
	// Route::resource('payments', ClientPaymentsController::class);
});
