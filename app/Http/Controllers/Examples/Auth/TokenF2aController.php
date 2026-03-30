<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Exceptions\JsonException;
use App\Http\Requests\Auth\TokenF2aRequest;
use App\Models\User;

class TokenF2aController extends Controller
{
	function index(TokenF2aRequest $request)
	{
		$user = $request->authenticate();

		if ($user instanceof User) {

			$token = $user->createToken(
				$request->device_name,
				['*'],
				now()->addYear()
			)->plainTextToken;

			return response()->json([
				'message' => __('login.authenticated'),
				'user' => $user->fresh(['roles', 'permissions']),
				'token' => $token,
				'guard' => 'web',
			], 200);
		} else {
			throw new JsonException(__('login.f2a_error'), 422);
		}
	}
}
