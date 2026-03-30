<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\Auth\RolesPermissionsSeeder;

class AppSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		$this->call([
			RolesPermissionsSeeder::class,
			AppAdminSeeder::class,
			GifSeeder::class,
			AudioSeeder::class,
			SettingSeeder::class,
		]);
	}
}
