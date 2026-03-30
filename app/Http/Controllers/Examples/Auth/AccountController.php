<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AccountRequest;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
	function update(AccountRequest $request)
	{
		try {
			Auth::user()->update($request->validated());

			return response()->json([
				'message' => __("account.update.success"),
				'user' => Auth::user()->fresh(['roles', 'permissions']),
			], 200);
		} catch (Exception $e) {
			report($e);
			throw new JsonException(__("account.update.error"), 422);
		}
	}
}
