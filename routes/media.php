<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// Display image from html
// <img src="image/media/avatars/donkey.webp">

// Image path media/avatars/donkey.webp
// file in private/media/avatars/donkey.webp directory
// for storage disk default settings (FILESYSTEM_DISK=local).
// In php.ini change session.cache_limiter = nocache to empty string session.cache_limiter = ''.
Route::get('/image/{path}', function ($path) {
	// Check path
	if (Storage::has($path)) {
		// Get file content from cache
		$content = Cache::store('file')->remember(
			md5($path),
			now()->addMinutes(15),
			function () use ($path) {
				return Storage::get($path);
			}
		);
		// Display image
		return response($content)->header('Content-Type', 'image/webp');
	} else {
		// Default image
		return response()->file(public_path('/default/avatar.webp'), [
			'Content-Type' => 'image/webp',
			'Cache-Control' => 'public, max-age=3600'
		]);
	}
})->where('path', '.*');
