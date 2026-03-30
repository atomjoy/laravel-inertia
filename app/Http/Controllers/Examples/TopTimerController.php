<?php

namespace App\Http\Controllers;

use App\Models\TopTimer;
use App\Http\Requests\StoreTopTimerRequest;
use App\Http\Requests\UpdateTopTimerRequest;
use App\Http\Resources\TopTimerCollection;
use App\Http\Resources\TopTimerResource;
use Illuminate\Support\Facades\Gate;

class TopTimerController extends Controller
{
	/**
	 * Allowed order by fields
	 *
	 * @var array
	 */
	protected $allow_orderby = ['id', 'endtime', 'created_at'];

	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		Gate::authorize('viewAny', TopTimer::class);

		$q = TopTimer::query();

		$perpage = request()->integer('perpage', default: config('default.panel_perpage', 12));

		$orderby = request()->input('orderby', default: 'id');

		if (!in_array($orderby, $this->allow_orderby)) {
			$orderby = 'id';
		}

		if (request()->filled('sortpage')) {
			request()->query('sortpage') == 'asc' ? $q->oldest($orderby) : $q->latest($orderby);
		}


		if (request()->filled('search')) {
			$q->searchField('endtime', request()->input('search'));
		}

		return new TopTimerCollection($q->paginate($perpage));
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
	public function store(StoreTopTimerRequest $request)
	{
		Gate::authorize('create', TopTimer::class);

		$valid = $request->validated();

		$topTimer = TopTimer::create($valid);

		return response()->json([
			'message' => 'Created',
			'data' => $topTimer,
		], 201);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(TopTimer $topTimer)
	{
		Gate::authorize('view', $topTimer);

		return new TopTimerResource($topTimer);
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(TopTimer $topTimer)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdateTopTimerRequest $request, TopTimer $topTimer)
	{
		Gate::authorize('update', $topTimer);

		$valid = $request->validated();
		$topTimer->update($valid);

		return response()->json([
			'message' => 'Updated',
			'data' => $topTimer->fresh(),
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(TopTimer $topTimer)
	{
		Gate::authorize('delete', $topTimer);

		$topTimer->delete();

		return response()->json([
			'message' => 'Deleted',
			'data' => $topTimer,
		]);
	}

	/**
	 * Last toptimer amount
	 */
	public function last()
	{
		return new TopTimerResource(TopTimer::latest('id')->first());
	}
}
