<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Order;

class AdminPanelController extends Controller
{
	/**
	 * Admins list.
	 */
	public function admins()
	{
		return UserResource::collection(User::with(['roles', 'permissions'])->role(['admin', 'worker', 'super_admin'])->get());
	}

	/**
	 * Count users.
	 */
	public function usersCount()
	{
		return response()->json([
			'count' => User::count()
		]);
	}

	/**
	 * Orders users.
	 */
	public function ordersCount()
	{
		return response()->json([
			'count' => Order::count()
		]);
	}
}
