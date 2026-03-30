<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		// Product 1

		Attribute::create([
			'product_id' => 1,
			'name' => 'Size',
			'picker' => 'size',
		]); // id 1

		Attribute::create([
			'product_id' => 1,
			'name' => 'Color',
			'picker' => 'color',
		]); // 2

		// Product 2
		Attribute::create([
			'product_id' => 2,
			'name' => 'Procesor',
		]); // 3

		Attribute::create([
			'product_id' => 2,
			'name' => 'Ram'
		]); // 4

		// Product 3
		Attribute::create([
			'product_id' => 3,
			'name' => 'Procesor Mobile'
		]); // 5

		Attribute::create([
			'product_id' => 3,
			'name' => 'Ram Mobile'
		]); // 6

		// Product 1

		Attribute::create([
			'product_id' => 1,
			'name' => 'Material'
		]); // 7

		// Product 2

		Attribute::create([
			'product_id' => 2,
			'name' => 'Graphics'
		]); // 8

		// Product 4

		Attribute::create([
			'product_id' => 4,
			'name' => 'License'
		]); // 9
	}
}
