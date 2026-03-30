<?php

namespace App\Http\Controllers;

use App\Models\TopGreeting;
use App\Http\Requests\StoreTopGreetingRequest;
use App\Http\Requests\UpdateTopGreetingRequest;
use App\Http\Resources\TopGreetingCollection;
use App\Http\Resources\TopGreetingResource;
use Illuminate\Support\Facades\Gate;

class TopGreetingController extends Controller
{
	/**
	 * Allowed order by fields
	 *
	 * @var array
	 */
	protected $allow_orderby = ['id', 'style', 'created_at'];

	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		Gate::authorize('viewAny', TopGreeting::class);

		$q = TopGreeting::query();

		$perpage = request()->integer('perpage', default: config('default.panel_perpage', 12));

		$orderby = request()->input('orderby', default: 'id');

		if (!in_array($orderby, $this->allow_orderby)) {
			$orderby = 'id';
		}

		if (request()->filled('sortpage')) {
			request()->query('sortpage') == 'asc' ? $q->oldest($orderby) : $q->latest($orderby);
		}


		if (request()->filled('search')) {
			$q->searchField('style', request()->input('search'));
		}

		return new TopGreetingCollection($q->paginate($perpage));
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
	public function store(StoreTopGreetingRequest $request)
	{
		Gate::authorize('create', TopGreeting::class);

		$valid = $request->validated();

		$topGreeting = TopGreeting::create($valid);

		return response()->json([
			'message' => 'Created',
			'data' => $topGreeting,
		], 201);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(TopGreeting $topGreeting)
	{
		Gate::authorize('view', $topGreeting);

		return new TopGreetingResource($topGreeting);
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(TopGreeting $topGreeting)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdateTopGreetingRequest $request, TopGreeting $topGreeting)
	{
		Gate::authorize('update', $topGreeting);

		$valid = $request->validated();
		$topGreeting->update($valid);

		return response()->json([
			'message' => 'Updated',
			'data' => $topGreeting->fresh(),
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(TopGreeting $topGreeting)
	{
		Gate::authorize('delete', $topGreeting);

		$topGreeting->delete();

		return response()->json([
			'message' => 'Deleted',
			'data' => $topGreeting,
		]);
	}

	/**
	 * Last topgreeting amount
	 */
	public function last()
	{
		return new TopGreetingResource(TopGreeting::latest('id')->first());
	}
}
