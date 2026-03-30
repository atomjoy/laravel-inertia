<?php

namespace App\Http\Controllers;

use App\Models\TopDonation;
use App\Http\Requests\StoreTopDonationRequest;
use App\Http\Requests\UpdateTopDonationRequest;
use App\Http\Resources\TopDonationCollection;
use App\Http\Resources\TopDonationResource;
use Illuminate\Support\Facades\Gate;

class TopDonationController extends Controller
{
	/**
	 * Allowed order by fields
	 *
	 * @var array
	 */
	protected $allow_orderby = ['id', 'show', 'created_at'];

	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		Gate::authorize('viewAny', TopDonation::class);

		$q = TopDonation::query();

		$perpage = request()->integer('perpage', default: config('default.panel_perpage', 12));

		$orderby = request()->input('orderby', default: 'id');

		if (!in_array($orderby, $this->allow_orderby)) {
			$orderby = 'id';
		}

		if (request()->filled('sortpage')) {
			request()->query('sortpage') == 'asc' ? $q->oldest($orderby) : $q->latest($orderby);
		}

		if (request()->filled('search')) {
			$q->searchField('show', request()->input('search'));
		}

		return new TopDonationCollection($q->paginate($perpage));
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
	public function store(StoreTopDonationRequest $request)
	{
		Gate::authorize('create', TopDonation::class);

		$valid = $request->validated();

		$topDonation = TopDonation::create($valid);

		return response()->json([
			'message' => 'Created',
			'data' => $topDonation,
		], 201);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(TopDonation $topDonation)
	{
		Gate::authorize('view', $topDonation);

		return new TopDonationResource($topDonation);
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(TopDonation $topDonation)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdateTopDonationRequest $request, TopDonation $topDonation)
	{
		Gate::authorize('update', $topDonation);

		$valid = $request->validated();

		$topDonation->update($valid);

		return response()->json([
			'message' => 'Updated',
			'data' => $topDonation->fresh(),
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(TopDonation $topDonation)
	{
		Gate::authorize('delete', $topDonation);

		$topDonation->delete();

		return response()->json([
			'message' => 'Deleted',
			'data' => $topDonation,
		]);
	}

	/**
	 * Last topdonation amount
	 */
	public function last()
	{
		return new TopDonationResource(TopDonation::latest('id')->first());
	}
}
