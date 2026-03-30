<?php

namespace Database\Seeders;

use App\Models\Gif;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GifSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		Gif::truncate();

		Gif::factory()->create(['image' => '/media/gifs/1.gif', 'name' => 'Gif 1']);
		Gif::factory()->create(['image' => '/media/gifs/2.gif', 'name' => 'Gif 2']);
		Gif::factory()->create(['image' => '/media/gifs/3.gif', 'name' => 'Gif 3']);
		Gif::factory()->create(['image' => '/media/gifs/4.gif', 'name' => 'Gif 4']);
		Gif::factory()->create(['image' => '/media/gifs/5.gif', 'name' => 'Gif 5']);
		Gif::factory()->create(['image' => '/media/gifs/6.gif', 'name' => 'Gif 6']);
		Gif::factory()->create(['image' => '/media/gifs/7.gif', 'name' => 'Gif 7']);
		Gif::factory()->create(['image' => '/media/gifs/8.gif', 'name' => 'Gif 8']);
		Gif::factory()->create(['image' => '/media/gifs/9.gif', 'name' => 'Gif 9']);
		Gif::factory()->create(['image' => '/media/gifs/10.gif', 'name' => 'Gif 10']);
		Gif::factory()->create(['image' => '/media/gifs/11.gif', 'name' => 'Gif 11']);
		Gif::factory()->create(['image' => '/media/gifs/12.gif', 'name' => 'Gif 12']);
		Gif::factory()->create(['image' => '/media/gifs/13.gif', 'name' => 'Gif 13']);
		Gif::factory()->create(['image' => '/media/gifs/14.gif', 'name' => 'Gif 14']);
		Gif::factory()->create(['image' => '/media/gifs/15.gif', 'name' => 'Gif 15']);
		Gif::factory()->create(['image' => '/media/gifs/16.gif', 'name' => 'Gif 16']);
		Gif::factory()->create(['image' => '/media/gifs/17.gif', 'name' => 'Gif 17']);

		@mkdir(storage_path('app/public/media/gifs'), recursive: true);

		for ($i = 1; $i <= 17; $i++) {
			copy(
				base_path('public/default/gif/' . $i . '.gif'),
				storage_path('app/public/media/gifs/' . $i . '.gif')
			);
		}
	}
}
