<?php

namespace App\Http\Controllers\Auth;

use Throwable;
use App\Enums\Auth\RolesEnum;
use App\Events\AccountDelete;
use App\Exceptions\JsonException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AccountDeleteRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountDeleteController extends Controller
{
	/**
	 * Update the specified resource in storage.
	 */
	public function update(AccountDeleteRequest $request)
	{
		$valid = $request->validated();

		try {
			$user = Auth::guard('web')->user();

			if ($user->hasRole([
				RolesEnum::ADMIN,
				RolesEnum::WORKER,
				RolesEnum::SUPERADMIN,
			])) {
				return response()->json([
					'error' => __("The administrator account cannot be deleted."),
				], 422);
			}

			if (Hash::check($valid['current_password'], $user->password)) {
				// See Listeners/AccountDeleteListener
				AccountDelete::dispatch($user);

				return response()->json([
					'message' => __("account.delete.success"),
				], 200);
			} else {
				return response()->json([
					'error' => __("account.delete.invalid_password"),
				], 422);
			}
		} catch (Throwable $e) {
			report($e);
			throw new JsonException(__("account.delete.error"), 422);
		}
	}
}
