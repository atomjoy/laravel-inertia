<?php

namespace Database\Seeders\Auth\Roles;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Enums\Auth\Permissions\DisableEnum;
use App\Enums\Auth\Permissions\OrderEnum;
use App\Enums\Auth\Permissions\RoleEnum;
use App\Enums\Auth\Permissions\UserEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		// Create model permissions

		foreach (RoleEnum::cases() as $item) {
			// Permission::create(['name' => $item->value, 'guard_name' => 'web']);
			app(Permission::class)->findOrCreate($item->value, 'web');
		}

		foreach (UserEnum::cases() as $item) {
			// Permission::create(['name' => $item->value, 'guard_name' => 'web']);
			app(Permission::class)->findOrCreate($item->value, 'web');
		}

		foreach (DisableEnum::cases() as $item) {
			// Permission::create(['name' => $item->value, 'guard_name' => 'web']);
			app(Permission::class)->findOrCreate($item->value, 'web');
		}

		foreach (OrderEnum::cases() as $item) {
			// Permission::create(['name' => $item->value, 'guard_name' => 'web']);
			app(Permission::class)->findOrCreate($item->value, 'web');
		}
	}
}
