<?php

namespace Database\Seeders;

use App\Models\Target;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TargetSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		Target::factory()->create(['amount' => 20000]);
		Target::factory()->create(['amount' => 30000]);
		Target::factory()->create(['amount' => rand(20000, 90000)]);
	}
}
