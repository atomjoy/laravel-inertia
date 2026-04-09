<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Client panel
Route::group([
	'middleware' => ['auth', 'verified']
], function () {

	Route::get('dashboard', function () {
		return Inertia::render('client/Dashboard');
	})->name('dashboard');

	Route::get('orders', function () {
		return Inertia::render('client/Orders');
	})->name('orders');
});
