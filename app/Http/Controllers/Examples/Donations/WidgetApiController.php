<?php

namespace App\Http\Controllers\Donations;

use Throwable;
use App\Http\Controllers\Controller;
use App\Models\Widget;

class WidgetApiController extends Controller
{
	public $portals = ['youtube', 'twitch', 'kick', 'vimeo', 'spotify', 'tiktok'];

	/**
	 * Get visible widgets
	 *
	 * @return mixed
	 */
	public function index()
	{
		try {
			return Widget::where('is_visible', 1)->get();
		} catch (Throwable $e) {
			report($e);
			return response()->json([
				'error' => $e->getMessage()
			], 422);
		}
	}

	/**
	 * Get widgets by portal
	 *
	 * @return mixed
	 */
	public function custom($portal)
	{
		try {
			if (!in_array($portal, $this->portals)) {
				$portal = 'youtube';
			}
			return Widget::where('is_visible', 1)->where('website', $portal)->get();
		} catch (Throwable $e) {
			report($e);
			return response()->json([
				'error' => $e->getMessage()
			], 422);
		}
	}
}
