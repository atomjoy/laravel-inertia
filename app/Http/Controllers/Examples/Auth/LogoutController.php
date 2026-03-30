<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Http\Controllers\Controller;
use App\Events\LogoutUser;
use App\Events\LogoutUserError;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
	function index(Request $request)
	{
		try {
			Auth::shouldUse('web');

			if (Auth::check()) {
				LogoutUser::dispatch(Auth::user());
				Auth::logout();
			}

			// Destroy user and admin session
			if (config('default.logout_invalidate_session', false)) {
				$request->session()->flush();
				$request->session()->invalidate();
			} else {
				$request->session()->regenerate();
			}

			$request->session()->regenerateToken();

			session(['locale' => config('app.locale')]);

			return response()->json([
				'message' => __('logout.success'),
			], 200);
		} catch (Exception $e) {
			report($e);
			LogoutUserError::dispatch();

			return response()->json([
				'error' => __('logout.error'),
			], 422);
		}
	}
}
