<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		$this->call(SpatieSeeder::class);
		$this->call(PaymentSeeder::class);

		// User::factory(10)->create();

		// Test User
		$user = User::factory()->create([
			'name' => 'Test User',
			'email' => 'test@example.com',
		]);
		$user->givePermissionTo('update_profil');
		$user->givePermissionTo('delete_account');

		// Admin User
		$user = User::factory()->create([
			'name' => 'Admin User',
			'email' => 'admin@example.com',
		]);
		$user->assignRole('admin', 'writer');
		$user->givePermissionTo('update_profil');
	}
}
