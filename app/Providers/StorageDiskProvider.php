<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class StorageDiskProvider extends ServiceProvider
{
	/**
	 * Register services.
	 */
	public function register(): void
	{
		// Change storage local disk to public or ftp.
		if (config('filesystems.default') == 'local') {
			config(['filesystems.default' => 'public']);
		}
	}

	/**
	 * Bootstrap services.
	 */
	public function boot(): void
	{
		//
	}
}
