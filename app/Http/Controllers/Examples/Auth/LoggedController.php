<?php

namespace App\Http\Controllers\Auth;

use Exception;
use Throwable;
use App\Http\Controllers\Controller;
use App\Events\LoggedUser;
use App\Events\LoggedUserError;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoggedController extends Controller
{
	function index(Request $request)
	{
		try {
			if (Auth::check()) {
				LoggedUser::dispatch(Auth::user());

				return response()->json([
					'message' => __('logged.authenticated'),
					'user' => Auth::user()->fresh(['roles', 'permissions']),
					'guard' => 'web',
				], 200);
			} else {
				LoggedUserError::dispatch();
				throw new Exception("User not logged.");
			}
		} catch (Throwable $e) {
			if (config('app.debug')) {
				report($e);
			}

			return response()->json([
				'error' => __('logged.unauthenticated'),
				'user' => null,
				'guard' => 'web',
			], 422);
		}
	}
}
