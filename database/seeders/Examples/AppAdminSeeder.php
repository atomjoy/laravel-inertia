<?php

namespace Database\Seeders;

use App\Enums\Auth\Permissions\OrderEnum;
use App\Enums\Auth\Permissions\UserEnum;
use App\Enums\Auth\RolesEnum;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Admin panel user
 */
class AppAdminSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		$email = config('access.admin_email', 'admin@example.com');

		if (! User::where('email', $email)->first() instanceof User) {

			$user = User::factory()->create([
				'email' => $email,
				'name' => 'Admin User',
				'password' => Hash::make('Password123#'),
				'two_factor' => 1,
			]);

			$this->roles($user);

			$this->permissions($user);
		}
	}

	function roles($user, $guard_name = 'web')
	{
		$role = app(Role::class)->findOrCreate(RolesEnum::ADMIN->value, $guard_name);
		$user->assignRole($role);
	}

	function permissions($user, $guard_name = 'web')
	{
		$permissions = UserEnum::cases();
		foreach ($permissions as $item) {
			$permission = app(Permission::class)->findOrCreate($item->value, $guard_name);
			$user->givePermissionTo($permission);
		}

		$permissions = OrderEnum::cases();
		foreach ($permissions as $item) {
			$permission = app(Permission::class)->findOrCreate($item->value, $guard_name);
			$user->givePermissionTo($permission);
		}
	}
}
