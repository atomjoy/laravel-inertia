<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use Illuminate\Http\Resources\Json\JsonResource;
use Inertia\Inertia;

class PaymentController extends Controller
{
	/**
	 * Allowed order by fields
	 *
	 * @var array
	 */
	protected $orderby = ['id', 'email', 'status', 'created_at'];

	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$filters = [];
		$perPage = request()->input('per_page', 10);
		$email = request()->input('email', null);
		$status = request()->input('status', null);
		$sortField = request()->input('sort_field', 'id');
		$sortDirection = request()->input('sort_direction', 'desc');
		$sortDirection == 'desc' ? $sortDirection = 'desc' : $sortDirection = 'asc';
		if (!empty($status)) {
			$filters[] = [
				'id' => 'status',
				'value' => $status
			];
		}

		$payload =  Payment::query()->when($status, function ($query, $status) {
			if (is_array($status) && !empty($status)) {
				$query->whereIn('status', $status);
			}
		})->when($email, function ($query, $email) {
			if (!empty($email)) {
				$query->where('email', 'LIKE', '%' . $email . '%');
			}
		})->orderBy($sortField, $sortDirection)->paginate(perPage: $perPage);

		if (request()->wantsJson()) {
			return response()->json([
				'payload' => $payload,
				'filter' => $filters,
			], 200);
		} else {
			return Inertia::render('Payments/Index', [
				'data' => $payload,
				'filter' => $filters,
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
