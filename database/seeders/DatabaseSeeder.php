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
		// Seeders
		$this->call(SpatieSeeder::class);
		$this->call(PaymentSeeder::class);

		// Users
		// User::factory(10)->create();

		// Super User
		$user = User::factory()->create([
			'name' => 'Super User',
			'email' => 'super@example.com',
		]);
		$user->assignRole('superadmin');

		// Admin User
		$user = User::factory()->create([
			'name' => 'Admin User',
			'email' => 'admin@example.com',
		]);
		$user->assignRole('admin', 'writer');
		$user->givePermissionTo('profil_update');

		// Test User
		$user = User::factory()->create([
			'name' => 'Test User',
			'email' => 'test@example.com',
		]);
		$user->givePermissionTo('profil_update');
	}
}
