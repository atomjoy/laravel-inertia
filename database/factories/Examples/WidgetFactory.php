<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Widget>
 */
class WidgetFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'title' => 'Youtube video',
			'url' => 'https://www.youtube.com/embed/36YnV9STBqc?si=mqJneAcbmYQtq_Ac',
			'website' => 'youtube',
		];

		// <iframe width="560" height="315" src="https://www.youtube.com/embed/36YnV9STBqc?si=mqJneAcbmYQtq_Ac" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
		// <iframe src="https://player.twitch.tv/?<channel, video, or collection>&parent=streamernews.example.com" height="<height>" width="<width>" allowfullscreen></iframe>
		// <iframe data-testid="embed-iframe" src="https://open.spotify.com/embed/playlist/37i9dQZF1DX7zqr9q1MPG7?utm_source=generator&theme=0" width="100%" height="500" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
		// https://player.twitch.tv/?channel=xqcow&parent=www.example.com
	}
}
