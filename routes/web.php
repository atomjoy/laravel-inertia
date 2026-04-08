<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
	return Inertia::render('Welcome', [
		'canRegister' => Features::enabled(Features::registration()),
	]);
})->name('home');

// Client panel
require __DIR__ . '/client-panel.php';

// Admin panel
require __DIR__ . '/admin-panel.php';

// Media files, images
require __DIR__ . '/media.php';

// Settings
require __DIR__ . '/settings.php';
