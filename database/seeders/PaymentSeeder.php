<?php

namespace Database\Seeders;

use App\Models\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$status = ['success', 'failed', 'processing', 'canceled', 'pending'];

		Payment::factory()->create([
			'status' => 'canceled',
			'amount' => rand(10, 600),
			'email' => fake()->email(),
			'created_at' => fake()->dateTimeThisYear(),
		]);

		Payment::factory()->create([
			'status' => 'failed',
			'amount' => rand(10, 600),
			'email' => fake()->email(),
			'created_at' => fake()->dateTimeThisYear(),
		]);

		Payment::factory()->create([
			'status' => 'success',
			'amount' => rand(10, 600),
			'email' => fake()->email(),
			'created_at' => fake()->dateTimeThisYear(),
		]);

		Payment::factory()->create([
			'status' => 'pending',
			'amount' => rand(10, 600),
			'email' => fake()->email(),
			'created_at' => fake()->dateTimeThisYear(),
		]);

		Payment::factory()->create([
			'status' => 'processing',
			'amount' => rand(10, 600),
			'email' => fake()->email(),
			'created_at' => fake()->dateTimeThisYear(),
		]);

		for ($i = 0; $i < 100; $i++) {
			Payment::factory()->create([
				'status' => $status[rand(0, 4)],
				'amount' => rand(40, 6000),
				'email' => fake()->email(),
				'created_at' => fake()->dateTimeThisYear(),
			]);
		}
	}
}
