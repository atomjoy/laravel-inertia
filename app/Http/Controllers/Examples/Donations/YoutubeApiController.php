<?php

namespace App\Http\Controllers\Donations;

use Exception;
use Throwable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class YoutubeApiController extends Controller
{
	/**
	 * Youtube live viewers
	 *
	 * $url = 'https://youtube.googleapis.com/youtube/v3/videos?part=statistics%2Cstatus%2CliveStreamingDetails&id={VIDEO_ID}&key={KEY}';
	 */
	public function youtubeLiveCurrentViewers()
	{
		try {
			$count = Cache::store('file')
				->remember(
					'youtubeConcurrentViewers',
					config('access.youtube.current_refresh', 60),
					function () {
						$key = env('YOUTUBE_API_KEY', '');
						$videoId = Storage::disk('local')->get(config('access.youtube.current', 'youtube-current.txt'));
						if (!empty($videoId) && !empty($key)) {
							$res = Http::get('https://youtube.googleapis.com/youtube/v3/videos?part=liveStreamingDetails&id=' . $videoId . '&key=' . $key)->json();
							$cnt = $res['items'][0]['liveStreamingDetails']['concurrentViewers'] ?? 0;
						}
						return  $cnt ?? 0;
					}
				);
		} catch (Throwable $e) {
			report($e);
			return 0;
		}

		return $count;
	}

	/**
	 * Youtube live viewers save stream videoId to file
	 */
	public function updateYoutubeStream()
	{
		try {
			$videoId = request()->input('current_stream');

			if (empty($videoId)) {
				throw new Exception("Empty video id", 422);
			}

			$this->saveYoutubeCurrentStream($videoId);

			return response()->json([
				'message' => 'Created'
			]);
		} catch (Throwable $e) {
			report($e);

			return response()->json([
				'error' => $e->getMessage()
			], 422);
		}
	}

	/**
	 * Youtube live viewers save stream videoId to file
	 */
	public function getYoutubeStream()
	{
		try {
			$videoId = Storage::disk('local')->get(config('access.youtube.current', 'youtube-current.txt'));
			return response()->json([
				'video_id' => $videoId ?? ''
			]);
		} catch (Throwable $e) {
			report($e);
			return response()->json([
				'error' => $e->getMessage()
			], 422);
		}
	}

	/**
	 * Youtube live viewers save stream videoId to file
	 */
	public function saveYoutubeCurrentStream($videoId = '36YnV9STBqc')
	{
		return Storage::disk('local')->put(config('access.youtube.current', 'youtube-current.txt'), $videoId);
	}
}
