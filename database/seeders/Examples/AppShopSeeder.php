<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppShopSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		$this->call([
			CategorySeeder::class,
			ProductSeeder::class,
			AttributeSeeder::class,
			PropertySeeder::class,
			SkuSeeder::class,
		]);
	}
}
