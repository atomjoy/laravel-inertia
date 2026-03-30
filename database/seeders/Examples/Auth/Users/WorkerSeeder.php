<?php

namespace Database\Seeders\Auth\Users;

use App\Enums\Auth\Permissions\OrderEnum;
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
class WorkerSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		if (! User::where('email', 'worker@github.com')->first() instanceof User) {
			$user = User::factory()->create([
				'name' => 'Worker User',
				'email' => 'worker@github.com',
				'password' => Hash::make('Password123#')
			]);

			$this->roles($user);

			$this->permissions($user);
		}
	}

	function roles($user, $guard_name = 'web')
	{
		$role = app(Role::class)->findOrCreate(RolesEnum::WORKER->value, $guard_name);
		$user->assignRole($role);
	}

	function permissions($user, $guard_name = 'web')
	{
		$permissions = OrderEnum::cases();
		foreach ($permissions as $item) {
			$permission = app(Permission::class)->findOrCreate($item->value, $guard_name);
			$user->givePermissionTo($permission);
		}
	}
}
