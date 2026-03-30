<?php

namespace Database\Seeders;

use App\Models\Audio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AudioSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		Audio::truncate();

		Audio::factory()->create(['path' => '/media/mp3/1.mp3', 'name' => 'Audio 1']);
		Audio::factory()->create(['path' => '/media/mp3/2.mp3', 'name' => 'Audio 2']);
		Audio::factory()->create(['path' => '/media/mp3/3.mp3', 'name' => 'Audio 3']);
		Audio::factory()->create(['path' => '/media/mp3/4.mp3', 'name' => 'Audio 4']);
		Audio::factory()->create(['path' => '/media/mp3/5.mp3', 'name' => 'Audio 5']);
		Audio::factory()->create(['path' => '/media/mp3/6.mp3', 'name' => 'Audio 6']);
		Audio::factory()->create(['path' => '/media/mp3/7.mp3', 'name' => 'Audio 7']);
		Audio::factory()->create(['path' => '/media/mp3/8.mp3', 'name' => 'Audio 8']);
		Audio::factory()->create(['path' => '/media/mp3/9.mp3', 'name' => 'Audio 9']);
		Audio::factory()->create(['path' => '/media/mp3/10.mp3', 'name' => 'Audio 10']);
		Audio::factory()->create(['path' => '/media/mp3/11.mp3', 'name' => 'Audio 11']);
		Audio::factory()->create(['path' => '/media/mp3/12.mp3', 'name' => 'Audio 12']);
		Audio::factory()->create(['path' => '/media/mp3/13.mp3', 'name' => 'Audio 13']);
		Audio::factory()->create(['path' => '/media/mp3/14.mp3', 'name' => 'Audio 14']);

		@mkdir(storage_path('app/public/media/mp3'), recursive: true);

		for ($i = 1; $i <= 14; $i++) {
			copy(
				base_path('public/default/mp3/' . $i . '.mp3'),
				storage_path('app/public/media/mp3/' . $i . '.mp3')
			);
		}
	}
}
