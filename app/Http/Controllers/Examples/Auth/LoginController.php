<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Auth\Permissions\DisableEnum;
use App\Enums\Auth\RolesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
	function __construct()
	{
		Auth::shouldUse('web'); // Default guard
	}

	function index(LoginRequest $request)
	{
		$request->authenticate();

		$request->session()->regenerate();

		if (Auth::check()) {
			if (Auth::user()->hasRole(DisableEnum::DISABLE_LOGIN)) {
				Auth::logout();
				return response()->json([
					'error' => __('login.allow_login_banned'),
					'user' => null,
					'redirect' => null,
					'guard' => 'web',
				], 422);
			}

			// Auth::user()->hasRole(['worker', 'admin', 'super_admin'])
			if (Auth::user()->two_factor == 1 || $this->forceAdmin2FA()) {
				return response()->json([
					'message' => __('login.authenticated'),
					'user' => null,
					'redirect' => '/login/f2a/' . $request->createTwoFactor(Auth::user()),
					'guard' => 'web',
				], 200);
			}

			return response()->json([
				'message' => __('login.authenticated'),
				'user' => Auth::user()->fresh(['roles', 'permissions']),
				'redirect' => null,
				'guard' => 'web',
			], 200);
		} else {
			return response()->json([
				'error' => __('login.unauthenticated'),
				'user' => null,
				'redirect' => null,
				'guard' => 'web',
			], 422);
		}
	}

	function forceAdmin2FA()
	{
		if (config('access.force_admin_2fa', true)) {
			return Auth::user()->hasRole([
				RolesEnum::WORKER,
				RolesEnum::ADMIN,
				RolesEnum::SUPERADMIN,
			]);
		}

		return false;
	}
}
