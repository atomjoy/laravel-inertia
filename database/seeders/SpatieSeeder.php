<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SpatieSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		// Roles
		$superadmin = Role::create(['name' => 'superadmin']);
		$admin = Role::create(['name' => 'admin']);
		$writer = Role::create(['name' => 'writer']);

		// Permissions
		$update_profil = Permission::create(['name' => 'profil_update']);
		$delete_account = Permission::create(['name' => 'account_delete']);
	}
}
