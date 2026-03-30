<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		// Main cat
		$cat1 = Category::factory()->create([
			'name' => 'Clothes',
			'slug' => 'clothes',
		]);

		// Sub
		$cat2 = Category::factory()->create([
			'name' => 'Men',
			'slug' => 'men',
			'parent_id' => $cat1->id,
		]);

		$cat3 = Category::factory()->create([
			'name' => 'Women',
			'slug' => 'women',
			'parent_id' => $cat1->id,
		]);

		// Sub sub
		$cat4 = Category::factory()->create([
			'name' => 'T-Shirt Men',
			'slug' => 't-shirt-men',
			'parent_id' => $cat2->id,
		]);

		$cat5 = Category::factory()->create([
			'name' => 'T-Shirt Women',
			'slug' => 't-shirt-women',
			'parent_id' => $cat3->id,
		]);

		// Main cat
		$cat6 = Category::factory()->create([
			'name' => 'Electronics',
			'slug' => 'electronics',
		]);

		// Sub
		$cat7 = Category::factory()->create([
			'name' => 'Laptops',
			'slug' => 'laptops',
			'parent_id' => $cat6->id,
		]);

		// Sub
		$cat8 = Category::factory()->create([
			'name' => 'Smartphones',
			'slug' => 'smartphones',
			'parent_id' => $cat6->id,
		]);

		// Sub sub
		$cat9 = Category::factory()->create([
			'name' => 'Amd',
			'slug' => 'amd',
			'parent_id' => $cat7->id,
		]);

		$cat10 = Category::factory()->create([
			'name' => 'Intel',
			'slug' => 'intel',
			'parent_id' => $cat7->id,
		]);

		// Sub sub
		$cat11 = Category::factory()->create([
			'name' => 'Apple',
			'slug' => 'apple',
			'parent_id' => $cat8->id,
		]);

		$cat12 = Category::factory()->create([
			'name' => 'Samsung',
			'slug' => 'samsung',
			'parent_id' => $cat8->id,
		]);

		$virt = Category::factory()->create([
			'name' => 'Virtuals',
			'slug' => 'virtuals',
		]);

		$virt1 = Category::factory()->create([
			'name' => 'Plugins',
			'slug' => 'plugins',
			'parent_id' => $virt->id,
		]);
	}
}
