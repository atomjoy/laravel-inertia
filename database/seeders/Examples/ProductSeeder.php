<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$pr1 = Product::create([
			'name' => 'T-Shirt',
			'slug' => 'product-1',
			'description' => 'T-Shirt description.'
		]);

		$pr2 = Product::create([
			'name' => 'Laptop',
			'slug' => 'product-2',
			'description' => 'Laptop description.'
		]);

		$pr3 = Product::create([
			'name' => 'Smartphone',
			'slug' => 'product-3',
			'description' => 'Smartphone description.'
		]);

		$pr4 = Product::create([
			'name' => 'Plugin',
			'slug' => 'product-4',
			'description' => 'Plugin description.'
		]);

		// 1
		$cat = Category::where('slug', 'clothes')->first();
		$cat->products()->attach($pr1);

		$cat = Category::where('slug', 't-shirt-men')->first();
		$cat->products()->attach($pr1);

		// 2
		$cat = Category::where('slug', 'laptops')->first();
		$cat->products()->attach($pr2);

		$cat = Category::where('slug', 'amd')->first();
		$cat->products()->attach($pr2);

		// 3
		$cat = Category::where('slug', 'smartphones')->first();
		$cat->products()->attach($pr3);

		$cat = Category::where('slug', 'apple')->first();
		$cat->products()->attach($pr3);

		// 4
		$cat = Category::where('slug', 'virtuals')->first();
		$cat->products()->attach($pr4);

		$cat = Category::where('slug', 'plugins')->first();
		$cat->products()->attach($pr4);
	}
}
