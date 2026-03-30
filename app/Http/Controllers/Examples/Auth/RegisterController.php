<?php

namespace App\Http\Controllers\Auth;

use Throwable;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Events\RegisterUser;
use App\Events\RegisterUserError;
use App\Exceptions\JsonException;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
	function index(RegisterRequest $request)
	{
		$valid = $request->validated();

		// User panel registration disabled (only admin user login allowed)
		return response()->json([
			'error' => __('registration.disabled'),
		], 422);

		try {
			$user = User::create([
				'name' => $valid['name'],
				'email' => $valid['email'],
				'password' => Hash::make($valid['password']),
			]);

			$user->forceFill([
				'remember_token' => $this->createPasswordToken(),
			])->save();

			// $user->profile()->updateOrCreate([
			//     'user_id' => $user->id
			// ], [
			//     'name' => $valid['name'],
			//     'username' => uniqid('user.'),
			// ]);

			// $user->address()->updateOrCreate([
			//     'user_id' => $user->id
			// ], []);

			RegisterUser::dispatch($user);

			return response()->json([
				'message' => __("register.success"),
				'created' => true
			], 201);
		} catch (Throwable $e) {
			report($e);
			RegisterUserError::dispatch($valid);
			throw new JsonException(__("register.error"), 422);
		}
	}

	public function createPasswordToken()
	{
		return substr(uniqid(md5(microtime())), 0, rand(16, 21));
	}
}
