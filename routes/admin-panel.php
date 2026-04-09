<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Admin panel
Route::group([
	'name' => 'admin.',
	'prefix' => 'admin/',
	'middleware' => ['auth', 'verified', 'role:admin|superadmin']
], function () {

	Route::get('users', function () {
		return Inertia::render('admin/users/Index');
	})->name('users');

	Route::resource('payments', PaymentController::class);
});
