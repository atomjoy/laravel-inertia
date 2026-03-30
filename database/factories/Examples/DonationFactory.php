<?php

namespace Database\Factories;

use App\Enums\Payments\PaymentStatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Donation>
 */
class DonationFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'name' => fake()->firstName(),
			'last_name' => fake()->lastName(),
			'email' => fake()->unique()->safeEmail(),
			'phone' => '+48' . rand(800100100, 977988999),
			'message' => fake()->sentence(rand(10, 20)),
			'payment_id' => Str::uuid(),
			'external_id' => Str::uuid7() . rand(111, 999),
			'status' => PaymentStatusEnum::COMPLETED->value,
			'amount' => rand(1000, 50000),
			'gif' => rand(1, 17),
			'currency' => 'pln',
			'is_seen' => 0,
			'url' => '',
			'ip' => '127.0.0.1',
		];
	}
}
