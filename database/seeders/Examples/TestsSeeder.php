<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\Auth\RolesPermissionsSeeder;
use Database\Seeders\Auth\UsersSeeder;
use Database\Seeders\Notifications\NotifySeeder;

// use Database\Seeders\Models\PostSeeder;

class TestsSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		$this->call([
			RolesPermissionsSeeder::class,
			UsersSeeder::class,
			AppShopSeeder::class,
			NotifySeeder::class,
			GifSeeder::class,
			AudioSeeder::class,
			DonationSeeder::class,
			TargetSeeder::class,
			SettingSeeder::class,
			// PostSeeder::class,
		]);
	}
}
