<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Auth\Permissions\DisableEnum;
use App\Enums\Auth\RolesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\TokenLoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TokenLoginController extends Controller
{
	function __construct()
	{
		Auth::shouldUse('web'); // Default guard
	}

	function index(TokenLoginRequest $request)
	{
		$user = $request->authenticate();

		if ($user instanceof User) {
			if ($user->hasRole(DisableEnum::DISABLE_LOGIN)) {
				return response()->json([
					'error' => __('login.allow_login_banned'),
					'user' => null,
					'redirect' => null,
					'guard' => 'web',
				], 422);
			}

			if ($user->two_factor == 1 || $this->forceAdmin2FA($user)) {
				return response()->json([
					'message' => __('login.authenticated'),
					'user' => null,
					'redirect' => '/api/token/f2a/' . $request->createTwoFactor($user),
					'guard' => 'web',
				], 200);
			}

			// Create token here or in token/f2a controller
			$token = $user->createToken(
				$request->device_name,
				['*'],
				now()->addYear()
			)->plainTextToken;

			return response()->json([
				'message' => __('login.authenticated'),
				'user' => $user->fresh(['roles', 'permissions']),
				'token' => $token,
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

	function forceAdmin2FA($user)
	{
		if (config('access.force_admin_2fa', true)) {
			return $user->hasRole([
				RolesEnum::WORKER,
				RolesEnum::ADMIN,
				RolesEnum::SUPERADMIN,
			]);
		}

		return false;
	}
}
