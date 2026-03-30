<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Sku;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkuSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		// Product 1

		// S-Blue
		$sku = Sku::create([
			'net_price' => 5000,
			'tax_price' => 1150,
			'gross_price' => 6150,
			'net_sale_price' => 4500,
			'tax_sale_price' => 1035,
			'gross_sale_price' => 5535,
			'sku' => 'sku-100',
			'name' => 'sku-100',
			'slug' => 'sku-100',
			'product_id' => 1,
			'stock_quantity' => 5
		]);

		// Size id 1, Color id 2
		$sku->attributes()->attach(1, ['property_id' => 1]); // Property S id 1
		$sku->attributes()->attach(2, ['property_id' => 3]); // Property Blue id 3
		$sku->attributes()->attach(7, ['property_id' => 13]); // Property Wool id 13

		// Blue
		$img = new Image();
		$img->path = 'https://raw.githubusercontent.com/atomjoy/laravel-products/refs/heads/main/public/default/koszulka_blue_1.jpg';
		$sku->images()->save($img);

		$img = new Image();
		$img->path = 'https://raw.githubusercontent.com/atomjoy/laravel-products/refs/heads/main/public/default/koszulka_blue_2.jpg';
		$sku->images()->save($img);

		$img = new Image();
		$img->path = 'https://raw.githubusercontent.com/atomjoy/laravel-products/refs/heads/main/public/default/koszulka_blue_3.jpg';
		$sku->images()->save($img);

		// S-Red
		$sku = Sku::create([
			'net_price' => 6000,
			'sku' => 'sku-200',
			'name' => 'sku-200',
			'slug' => 'sku-200',
			'product_id' => 1,
			'stock_quantity' => 5
		]);

		// Size, Color
		$sku->attributes()->attach(1, ['property_id' => 1]);
		$sku->attributes()->attach(2, ['property_id' => 4]);
		$sku->attributes()->attach(7, ['property_id' => 14]);

		// Red
		$img = new Image();
		$img->path = 'https://raw.githubusercontent.com/atomjoy/laravel-products/refs/heads/main/public/default/koszulka_red_1.jpg';
		$img->featured = 1;
		$sku->images()->save($img);

		$img = new Image();
		$img->path = 'https://raw.githubusercontent.com/atomjoy/laravel-products/refs/heads/main/public/default/koszulka_red_2.jpg';
		$sku->images()->save($img);

		$img = new Image();
		$img->path = 'https://raw.githubusercontent.com/atomjoy/laravel-products/refs/heads/main/public/default/koszulka_red_3.jpg';
		$sku->images()->save($img);

		// M-Blue
		$sku = Sku::create([
			'net_price' => 7000,
			'sku' => 'sku-300',
			'name' => 'sku-300',
			'slug' => 'sku-300',
			'product_id' => 1,
			'stock_quantity' => 6
		]);

		// Size, Color
		$sku->attributes()->attach(1, ['property_id' => 2]);
		$sku->attributes()->attach(2, ['property_id' => 3]);
		$sku->attributes()->attach(7, ['property_id' => 13]);

		// Blue
		$img = new Image();
		$img->path = 'https://raw.githubusercontent.com/atomjoy/laravel-products/refs/heads/main/public/default/koszulka_blue_1.jpg';
		$img->featured = 1;
		$sku->images()->save($img);

		$img = new Image();
		$img->path = 'https://raw.githubusercontent.com/atomjoy/laravel-products/refs/heads/main/public/default/koszulka_blue_2.jpg';
		$sku->images()->save($img);

		$img = new Image();
		$img->path = 'https://raw.githubusercontent.com/atomjoy/laravel-products/refs/heads/main/public/default/koszulka_blue_3.jpg';
		$sku->images()->save($img);

		// M-Red
		$sku = Sku::create([
			'net_price' => 8000,
			'net_sale_price' => 7599,
			'sku' => 'sku-400',
			'name' => 'sku-400',
			'slug' => 'sku-400',
			'product_id' => 1,
			'stock_quantity' => 6
		]);

		// Size, Color
		$sku->attributes()->attach(1, ['property_id' => 2]);
		$sku->attributes()->attach(2, ['property_id' => 4]);
		$sku->attributes()->attach(7, ['property_id' => 15]);

		// Red
		$img = new Image();
		$img->path = 'https://raw.githubusercontent.com/atomjoy/laravel-products/refs/heads/main/public/default/koszulka_red_1.jpg';
		$img->featured = 1;
		$sku->images()->save($img);

		$img = new Image();
		$img->path = 'https://raw.githubusercontent.com/atomjoy/laravel-products/refs/heads/main/public/default/koszulka_red_2.jpg';
		$sku->images()->save($img);

		$img = new Image();
		$img->path = 'https://raw.githubusercontent.com/atomjoy/laravel-products/refs/heads/main/public/default/koszulka_red_3.jpg';
		$sku->images()->save($img);

		// Product 2

		// Intel-32GB
		$sku = Sku::create([
			'net_price' => 50000,
			'sku' => 'sku-501',
			'name' => 'sku-501',
			'slug' => 'sku-501',
			'product_id' => 2,
			'stock_quantity' => 3
		]);

		// Procesor 3, Ram 4
		$sku->attributes()->attach(3, ['property_id' => 5]);
		$sku->attributes()->attach(4, ['property_id' => 7]);

		// Intel-64GB
		$sku = Sku::create([
			'net_price' => 60000,
			'sku' => 'sku-502',
			'name' => 'sku-502',
			'slug' => 'sku-502',
			'product_id' => 2,
			'stock_quantity' => 3
		]);

		// Procesor, Ram
		$sku->attributes()->attach(3, ['property_id' => 5]);
		$sku->attributes()->attach(4, ['property_id' => 8]);

		// Amd-32GB
		$sku = Sku::create([
			'net_price' => 50000,
			'sku' => 'sku-601',
			'name' => 'sku-601',
			'slug' => 'sku-601',
			'product_id' => 2,
			'stock_quantity' => 6
		]);

		// Procesor, Ram
		$sku->attributes()->attach(3, ['property_id' => 6]);
		$sku->attributes()->attach(4, ['property_id' => 7]);

		// Amd-64GB
		$sku = Sku::create([
			'net_price' => 60000,
			'sku' => 'sku-602',
			'name' => 'sku-602',
			'slug' => 'sku-602',
			'product_id' => 2,
			'stock_quantity' => 6
		]);

		// Procesor, Ram
		$sku->attributes()->attach(3, ['property_id' => 6]);
		$sku->attributes()->attach(4, ['property_id' => 8]);

		// Product 3

		// AppleA17-32GB
		$sku = Sku::create([
			'net_price' => 95500,
			'sku' => 'sku-9000',
			'name' => 'sku-9000',
			'slug' => 'sku-9000',
			'product_id' => 3,
			'stock_quantity' => 13
		]);

		// Procesor, Ram
		$sku->attributes()->attach(5, ['property_id' => 9]);
		$sku->attributes()->attach(6, ['property_id' => 11]);

		// Product 4 (Virtual)

		// Plugin
		$sku = Sku::create([
			'net_price' => 15500,
			'sku' => 'sku-plugin-100',
			'name' => 'sku-plugin-100',
			'slug' => 'sku-plugin-100',
			'product_id' => 4,
			'stock_quantity' => 11
		]);

		// Licence (Individual)
		$sku->attributes()->attach(9, ['property_id' => 18]);

		// Plugin
		$sku = Sku::create([
			'net_price' => 25500,
			'sku' => 'sku-plugin-200',
			'name' => 'sku-plugin-200',
			'slug' => 'sku-plugin-200',
			'product_id' => 4,
			'stock_quantity' => 22
		]);

		// Licence (Developer)
		$sku->attributes()->attach(9, ['property_id' => 19]);
	}
}
