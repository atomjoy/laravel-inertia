<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'name' => fake()->firstName() . ' ' . fake()->lastName(),
			'email' => fake()->email(),
			'status' => fake()->randomElement(['success', 'processing', 'pending', 'canceled', 'failed']),
			'avatar' => fake()->randomElement(['/default/man.webp', '/default/woman.webp', '/default/zebra.webp', '/default/donkey.webp', '/default/avatar.webp']),
			'amount' => rand(10, 6000),
		];
	}
}
