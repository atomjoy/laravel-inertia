<?php

namespace Database\Seeders;

use App\Models\Donation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DonationSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		// Donation::truncate();

		// Today
		Donation::factory()->create(['email' => 'dzik@example.com', 'amount' => 55660, 'name' => 'Dziki Dzik']);

		Donation::factory()->create(['email' => 'max@example.com', 'amount' => 1000]);
		Donation::factory()->create(['email' => 'max@example.com', 'amount' => 2000]);
		Donation::factory()->create(['email' => 'max@example.com', 'amount' => 3000]);

		Donation::factory()->create(['email' => 'ben@example.com', 'amount' => 4000]);
		Donation::factory()->create(['email' => 'ben@example.com', 'amount' => 5000]);

		Donation::factory()->create(['email' => 'ken@example.com', 'amount' => 6000]);
		Donation::factory()->create(['email' => 'ken@example.com', 'amount' => 7000]);

		Donation::factory()->create(['email' => 'alex@example.com', 'amount' => 8000, 'name' => 'Alex']);
		Donation::factory()->create(['email' => 'alex@example.com', 'amount' => 9000, 'name' => 'Alex']);
		Donation::factory()->create(['email' => 'alex@example.com', 'amount' => 10000, 'name' => 'Alex']);
		Donation::factory()->create(['email' => 'alex@example.com', 'amount' => 11000, 'name' => 'Alex']);

		Donation::factory()->create(['email' => 'comka@example.com', 'amount' => 12000]);
		Donation::factory()->create(['email' => 'comka@example.com', 'amount' => 13000]);
		// Suma: 146660

		// Yesterday
		Donation::factory()->create(['email' => 'beforek@example.com', 'amount' => 40000, 'created_at' => now()->subDay()]);
		Donation::factory()->create(['email' => 'beforek@example.com', 'amount' => 60000, 'created_at' => now()->subDay()]);
		// Suma: 246660

		// Week
		Donation::factory()->create(['email' => 'alek@example.com', 'amount' => 50000, 'created_at' => now()->subDays(10)]);
		Donation::factory()->create(['email' => 'alek@example.com', 'amount' => 100000, 'created_at' => now()->subDays(10)]);
		// Suma: 396660
	}
}
