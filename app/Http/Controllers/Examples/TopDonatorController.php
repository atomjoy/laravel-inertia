<?php

namespace App\Http\Controllers;

use App\Models\TopDonator;
use App\Http\Requests\StoreTopDonatorRequest;
use App\Http\Requests\UpdateTopDonatorRequest;
use App\Http\Resources\TopDonatorCollection;
use App\Http\Resources\TopDonatorResource;
use Illuminate\Support\Facades\Gate;

class TopDonatorController extends Controller
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
		Gate::authorize('viewAny', TopDonator::class);

		$q = TopDonator::query();

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

		return new TopDonatorCollection($q->paginate($perpage));
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
	public function store(StoreTopDonatorRequest $request)
	{
		Gate::authorize('create', TopDonator::class);

		$valid = $request->validated();

		$topDonator = TopDonator::create($valid);

		return response()->json([
			'message' => 'Created',
			'data' => $topDonator,
		], 201);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(TopDonator $topDonator)
	{
		Gate::authorize('view', $topDonator);

		return new TopDonatorResource($topDonator);
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(TopDonator $topDonator)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdateTopDonatorRequest $request, TopDonator $topDonator)
	{
		Gate::authorize('update', $topDonator);

		$valid = $request->validated();
		$topDonator->update($valid);

		return response()->json([
			'message' => 'Updated',
			'data' => $topDonator->fresh(),
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(TopDonator $topDonator)
	{
		Gate::authorize('delete', $topDonator);

		$topDonator->delete();

		return response()->json([
			'message' => 'Deleted',
			'data' => $topDonator,
		]);
	}

	/**
	 * Last topdonator amount
	 */
	public function last()
	{
		return new TopDonatorResource(TopDonator::latest('id')->first());
	}
}
