<?php

namespace App\Http\Controllers\Donations;

use Throwable;
use App\Http\Controllers\Controller;
use App\Models\Setting;

class SettingApiController extends Controller
{
	/**
	 * Get all settings
	 *
	 * @return mixed
	 */
	public function index()
	{
		try {
			return Setting::all();
		} catch (Throwable $e) {
			report($e);
			return response()->json([
				'error' => $e->getMessage()
			], 422);
		}
	}
}
