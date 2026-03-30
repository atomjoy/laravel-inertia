<?php

namespace Database\Seeders\Auth\Users;

use App\Enums\Auth\RolesEnum;
use App\Models\User;
use Spatie\Permission\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Client panel user
 */
class WriterSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		if (! User::where('email', 'writer@github.com')->first() instanceof User) {

			$user = User::factory()->create([
				'name' => 'Writer User',
				'email' => 'writer@github.com',
				'password' => Hash::make('Password123#')
			]);

			$this->roles($user);
		}
	}

	function roles($user, $guard_name = 'web')
	{
		$role = app(Role::class)->findOrCreate(RolesEnum::WRITER->value, $guard_name);
		$user->assignRole($role);
	}
}
