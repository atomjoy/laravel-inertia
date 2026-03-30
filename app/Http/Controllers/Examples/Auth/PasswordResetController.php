<?php

namespace App\Http\Controllers\Auth;

use Exception;
use Throwable;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Events\PasswordReset;
use App\Events\PasswordResetError;
use App\Exceptions\JsonException;
use App\Http\Requests\Auth\PasswordResetRequest;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
	function index(PasswordResetRequest $request)
	{
		$valid = $request->validated();
		$password = uniqid('Wow8#');
		$user = null;

		try {
			$user = User::where('email', $valid['email'])->first();

			if (!$user instanceof User) {
				throw new Exception(__("reset.password.error.user"), 422);
			}
		} catch (Throwable $e) {
			PasswordResetError::dispatch($valid);
			throw new JsonException(__("reset.password.invalid"), 422);
		}

		try {
			if (empty($user->email_verified_at)) {
				$user->email_verified_at = now();
			}

			$user->password = Hash::make($password);
			$user->save();

			PasswordReset::dispatch($user, $password);

			return response()->json([
				'message' => __("reset.password.success")
			], 200);
		} catch (Throwable $e) {
			report($e);
			PasswordResetError::dispatch($valid);
			throw new JsonException(__("reset.password.error"), 422);
		}
	}
}
