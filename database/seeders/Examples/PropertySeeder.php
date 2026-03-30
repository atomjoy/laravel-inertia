<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		// Size
		Property::create([
			'attribute_id' => 1,
			'name' => 'S'
		]); // 1

		Property::create([
			'attribute_id' => 1,
			'name' => 'M',
		]); // 2

		// Color
		Property::create([
			'attribute_id' => 2,
			'name' => 'Blue',
			'value' => '#07f',
		]); // 3

		Property::create([
			'attribute_id' => 2,
			'name' => 'Red',
			'value' => '#f20',
		]); // 4

		// Procesor
		Property::create([
			'attribute_id' => 3,
			'name' => 'Intel'
		]); // 5

		Property::create([
			'attribute_id' => 3,
			'name' => 'Amd'
		]); // 6

		// Ram
		Property::create([
			'attribute_id' => 4,
			'name' => '32GB'
		]); // 7

		Property::create([
			'attribute_id' => 4,
			'name' => '64GB'
		]); // 8

		// Procesor Mobile
		Property::create([
			'attribute_id' => 5,
			'name' => 'Apple A17'
		]); // 9

		Property::create([
			'attribute_id' => 5,
			'name' => 'Exynos'
		]); // 10

		// Ram Mobile
		Property::create([
			'attribute_id' => 6,
			'name' => '32GB'
		]); // 11

		Property::create([
			'attribute_id' => 6,
			'name' => '64GB'
		]); // 12

		// Material
		Property::create([
			'attribute_id' => 7,
			'name' => 'Wool'
		]); // 13

		Property::create([
			'attribute_id' => 7,
			'name' => 'Cotton'
		]); // 14

		Property::create([
			'attribute_id' => 7,
			'name' => 'Polyester'
		]); // 15

		// Graphics
		Property::create([
			'attribute_id' => 8,
			'name' => 'Radeon'
		]); // 16

		Property::create([
			'attribute_id' => 8,
			'name' => 'Nvidia'
		]); // 17

		// License
		Property::create([
			'attribute_id' => 9,
			'name' => 'Individual'
		]); // 18

		Property::create([
			'attribute_id' => 9,
			'name' => 'Developer'
		]); // 19
	}
}
