<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class PanelController extends Controller
{
	/**
	 * Count orders.
	 */
	public function ordersCount()
	{
		return response()->json([
			'count' => Order::where('user_id', Auth::id())->count()
		]);
	}
}
