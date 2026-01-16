<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class PaymentController extends Controller
{
	/**
	 * Allowed order by fields
	 *
	 * @var array
	 */
	protected $orderby = ['id', 'email', 'amount', 'status', 'created_at'];

	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		$validator = Validator::make($request->all(), [
			"amount"    => "sometimes|array|min:1",
			"amount.*"  => "sometimes|numeric|distinct|min:1",
			"status"    => "sometimes|array|min:1",
			"status.*"  => "sometimes|string|distinct|min:1",
			// 'sku' => 'required|string|regex:​​/^[a-zA-Z0-9]+$/',
		]);

		$filters = [];
		$perPage = request()->integer('per_page', 10);
		$email = request()->input('email', null);
		$status = request()->input('status', null);
		$amount = request()->input('amount', null);
		$sortField = request()->input('sort_field', 'id');
		$sortDirection = request()->input('sort_direction', 'desc');
		$sortDirection == 'desc' ? $sortDirection = 'desc' : $sortDirection = 'asc';

		if (!empty($status)) {
			$filters[] = ['id' => 'status', 'value' => $status];
			$filters[] = ['id' => 'amount', 'value' => $amount];
			$filters[] = ['id' => 'email', 'value' => $email];
		}

		$slider_min = Payment::min("amount");
		$slider_max = Payment::max("amount");

		$payload =  Payment::query()
			->when($status, function ($query, $status) {
				if (is_array($status) && !empty($status)) {
					$query->whereIn('status', $status);
				}
			})->when($email, function ($query, $email) {
				if (!empty($email)) {
					$query->where('email', 'LIKE', '%' . $email . '%');
				}
			})->when($amount, function ($query, $amount) {
				if (!empty($amount)) {
					$query->whereBetween('amount', $amount);
					// $min = (int) $amount[0] ?? 0;
					// $max = (int) $amount[1] ?? 5000;
					// $query->where('amount', '>=', $min)->where('amount', '<=', $max);
				}
			})->orderBy($sortField, $sortDirection)->paginate(perPage: $perPage);

		if (request()->wantsJson()) {
			return response()->json([
				'payload' => $payload,
				'slider_min' => $slider_min ?? 0,
				'slider_max' => $slider_max ?? 10000,
				'filter' => $filters,
				'filter_errors' => $validator->fails() ? $validator->errors() : null,
			], 200);
		} else {
			return Inertia::render('Payments/Index', [
				'data' => $payload,
				'slider_min' => $slider_min ?? 0,
				'slider_max' => $slider_max ?? 10000,
				'filter' => $filters,
				'filter_errors' => $validator->fails() ? $validator->errors() : null,
				// 'json' => new JsonResponse(['key' => 'value']),
				// 'users' => User::all()->map(fn($user) => [
				//     'id' => $user->id,
				//     'name' => $user->name,
				//     'email' => $user->email,
				//     // 'edit_url' => route('users.edit', $user),
				// ]),
				// 'create_url' => route('users.create'),
			]);
		}
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(StorePaymentRequest $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Payment $payment)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Payment $payment)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdatePaymentRequest $request, Payment $payment)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Payment $payment)
	{
		//
	}
}
