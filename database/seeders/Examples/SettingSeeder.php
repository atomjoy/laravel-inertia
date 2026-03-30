<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		Setting::truncate();

		// Colors

		Setting::factory()->create([
			'name' => 'user_bg_color',
			'value' => '2f2f2f',
			'input' => 'color',
		]);

		Setting::factory()->create([
			'name' => 'user_accent_color',
			'value' => 'ff0033',
			'input' => 'color',
		]);

		Setting::factory()->create([
			'name' => 'user_text_color',
			'value' => 'ffffff',
			'input' => 'color',
		]);

		// Images

		Setting::factory()->create([
			'name' => 'user_bg_img',
			'value' => '/default/page/bg.webp',
			'input' => 'image',
		]);

		Setting::factory()->create([
			'name' => 'user_avatar_img',
			'value' => '/default/page/avatar.webp',
			'input' => 'image',
		]);

		Setting::factory()->create([
			'name' => 'user_profil_img',
			'value' => '/default/page/profil.webp',
			'input' => 'image',
		]);

		Setting::factory()->create([
			'name' => 'user_donate_img',
			'value' => '/default/page/profil.webp',
			'input' => 'image',
		]);

		Setting::factory()->create([
			'name' => 'user_person_img',
			'value' => '/default/page/person.webp',
			'input' => 'image',
		]);

		// Files

		Setting::factory()->create([
			'name' => 'user_presspack_mp3',
			'value' => '/default/page/presspack.mp3',
			'input' => 'file_mp3',
		]);

		Setting::factory()->create([
			'name' => 'user_presspack_zip',
			'value' => '/default/page/presspack.zip',
			'input' => 'file_zip',
		]);

		Setting::factory()->create([
			'name' => 'user_presspack_pdf',
			'value' => '/default/page/presspack.pdf',
			'input' => 'file_pdf',
		]);

		// Texts

		Setting::factory()->create([
			'name' => 'user_about',
			'value' =>  fake()->sentence(15),
			'input' => 'textarea',
		]);

		// Links

		Setting::factory()->create([
			'name' => 'page_live_link',
			'value' =>  'https://www.youtube.com/@music',
			'input' => 'text',
		]);

		Setting::factory()->create([
			'name' => 'page_video_link',
			'value' =>  null,
			'input' => 'text',
		]);

		Setting::factory()->create([
			'name' => 'page_song_link',
			'value' =>  null,
			'input' => 'text',
		]);

		Setting::factory()->create([
			'name' => 'page_event_link',
			'value' =>  null,
			'input' => 'text',
		]);

		// Limits (not needed)

		// Setting::factory()->create([
		// 	'name' => 'page_video_limit',
		// 	'value' =>  4,
		// 	'input' => 'text',
		// ]);

		// Setting::factory()->create([
		// 	'name' => 'page_song_limit',
		// 	'value' =>  4,
		// 	'input' => 'text',
		// ]);

		// Setting::factory()->create([
		// 	'name' => 'page_event_limit',
		// 	'value' =>  4,
		// 	'input' => 'text',
		// ]);
	}
}
