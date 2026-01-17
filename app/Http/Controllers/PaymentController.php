<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
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
			"amount"    => "sometimes|array|min:2",
			"amount.*"  => "sometimes|numeric|distinct|min:0",
			"status"    => "sometimes|array|min:1",
			"status.*"  => "sometimes|string|distinct|min:1",
			"created_at"    => "sometimes|array|min:2",
			"created_at.*"  => "sometimes|string|distinct|date_format:Y-m-d",
			// 'sku' => 'required|string|regex:​​/^[a-zA-Z0-9]+$/',
		]);

		$filters = [];
		$perPage = request()->integer('per_page', 10);
		$email = request()->input('email', null);
		$status = request()->input('status', null);
		$amount = request()->input('amount', null);
		$created_at = request()->input('created_at', null);
		$sortField = request()->input('sort_field', 'id');
		$sortDirection = request()->input('sort_direction', 'desc');
		$sortDirection == 'desc' ? $sortDirection = 'desc' : $sortDirection = 'asc';

		$filters[] = ['id' => 'status', 'value' => $status];
		$filters[] = ['id' => 'amount', 'value' => $amount];
		$filters[] = ['id' => 'email', 'value' => $email];
		$filters[] = ['id' => 'created_at', 'value' => $created_at];

		$amount_max = (int) ((Payment::max("amount") / 100) + 1);

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
					$amount[0] = $amount[0] * 100;
					$amount[1] = $amount[1] * 100;
					$query->whereBetween('amount', $amount); // In cents
				}
			})->when($created_at, function ($query, $created_at) {
				if (!empty($created_at[0]) && !empty($created_at[1])) {
					$query->whereDate('created_at', '>=', date($created_at[0]));
					$query->whereDate('created_at', '<=', date($created_at[1]));
				}
			})->orderBy($sortField, $sortDirection)->paginate(perPage: $perPage);

		if (request()->wantsJson()) {
			return response()->json([
				'payload' => $payload,
				'amount_max' => $amount_max ?? 10000,
				'filter' => $filters,
				'filter_errors' => $validator->fails() ? $validator->errors() : null,
			], 200);
		} else {
			return Inertia::render('Payments/Index', [
				'data' => $payload,
				'amount_max' => $amount_max ?? 10000,
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
