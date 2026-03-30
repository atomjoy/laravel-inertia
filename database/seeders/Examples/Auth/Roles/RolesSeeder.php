<?php

namespace Database\Seeders\Auth\Roles;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Enums\Auth\RolesEnum;
use App\Enums\Auth\MembershipEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		// Create roles
		foreach (RolesEnum::cases() as $item) {
			// Role::create(['name' => $item->value, 'guard_name' => 'web']);
			app(Role::class)->findOrCreate($item->value, 'web');
		}

		// Create membership roles
		foreach (MembershipEnum::cases() as $item) {
			// Role::create(['name' => $item->value, 'guard_name' => 'web']);
			app(Role::class)->findOrCreate($item->value, 'web');
		}
	}
}
