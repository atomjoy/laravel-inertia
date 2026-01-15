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
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$perPage = request()->input('per_page', 5);
		$filters = [];

		return Inertia::render('Payments/Index', [
			// 'data' => Payment::paginate(5),
			'data' => Payment::paginate(perPage: $perPage),
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
