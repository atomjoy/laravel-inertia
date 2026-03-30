<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Http\Requests\StoreDonationRequest;
use App\Http\Requests\UpdateDonationRequest;
use App\Http\Resources\DonationCollection;
use App\Http\Resources\DonationResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DonationController extends Controller
{
	/**
	 * Allowed order by fields
	 *
	 * @var array
	 */
	protected $allow_orderby = ['id', 'name', 'is_seen', 'email', 'amount', 'created_at'];

	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		Gate::authorize('viewAny', Donation::class);

		$q = Donation::query();

		$perpage = request()->integer('perpage', default: config('default.panel_perpage', 12));

		$orderby = request()->input('orderby', default: 'id');

		if (!in_array($orderby, $this->allow_orderby)) {
			$orderby = 'id';
		}

		if (request()->filled('sortpage')) {
			request()->query('sortpage') == 'asc' ? $q->oldest($orderby) : $q->latest($orderby);
		}

		if (request()->filled('search')) {
			$q->searchField('name', request()->input('search'));
			$q->searchField('email', request()->input('search'));
			$q->searchField('amount', request()->input('search'));
		}

		return new DonationCollection($q->paginate($perpage));
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
	public function store(StoreDonationRequest $request)
	{
		Gate::authorize('create', Donation::class);

		$valid = $request->validated();

		$valid['amount'] = number_format($valid['amount'] * 100, 0, '.', '');
		$valid['payment_id'] = Str::uuid();
		$valid['external_id'] = 'fake-' . Str::uuid();
		$valid['url'] = 'https://fake.com/' . Str::uuid();

		$donation = Donation::create($valid);

		$donation->save();

		return response()->json([
			'message' => 'Created',
			'data' => $donation,
		], 201);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Donation $donation)
	{
		Gate::authorize('view', $donation);

		return new DonationResource($donation);
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Donation $donation)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdateDonationRequest $request, Donation $donation)
	{
		Gate::authorize('update', $donation);

		$valid = $request->validated();
		$valid['amount'] = number_format($valid['amount'] * 100, 0, '.', '');

		$donation->update($valid);

		return response()->json([
			'message' => 'Updated',
			'data' => $donation->fresh(),
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Donation $donation)
	{
		return $this->actionDisabled();

		Gate::authorize('delete', $donation);

		$donation->delete();

		return response()->json([
			'message' => 'Deleted',
			'data' => $donation,
		]);
	}

	/**
	 * Last donation amount
	 */
	public function last()
	{
		return new DonationResource(Donation::latest('id')->first());
	}

	/**
	 * Disable action
	 */
	public function actionDisabled()
	{
		return response()->json([
			'error' => 'Action disabled',
		], 422);
	}
}
