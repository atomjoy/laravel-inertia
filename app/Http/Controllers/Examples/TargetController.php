<?php

namespace App\Http\Controllers;

use App\Models\Target;
use App\Http\Requests\StoreTargetRequest;
use App\Http\Requests\UpdateTargetRequest;
use App\Http\Resources\TargetCollection;
use App\Http\Resources\TargetResource;
use Illuminate\Support\Facades\Gate;

class TargetController extends Controller
{
	/**
	 * Allowed order by fields
	 *
	 * @var array
	 */
	protected $allow_orderby = ['id', 'amount', 'created_at'];

	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		Gate::authorize('viewAny', Target::class);

		$q = Target::query();

		$perpage = request()->integer('perpage', default: config('default.panel_perpage', 12));

		$orderby = request()->input('orderby', default: 'id');

		if (!in_array($orderby, $this->allow_orderby)) {
			$orderby = 'id';
		}

		if (request()->filled('sortpage')) {
			request()->query('sortpage') == 'asc' ? $q->oldest($orderby) : $q->latest($orderby);
		}


		if (request()->filled('search')) {
			$q->searchField('amount', request()->input('search'));
		}

		return new TargetCollection($q->paginate($perpage));
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
	public function store(StoreTargetRequest $request)
	{
		Gate::authorize('create', Target::class);

		$valid = $request->validated();

		$valid['amount'] = number_format($valid['amount'] * 100, 0, '.', '');

		$target = Target::create($valid);

		$target->save();

		return response()->json([
			'message' => 'Created',
			'data' => $target,
		], 201);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Target $target)
	{
		Gate::authorize('view', $target);

		return new TargetResource($target);
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Target $target)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdateTargetRequest $request, Target $target)
	{
		Gate::authorize('update', $target);

		$valid = $request->validated();
		$valid['amount'] = number_format($valid['amount'] * 100, 0, '.', '');

		$target->update($valid);

		return response()->json([
			'message' => 'Updated',
			'data' => $target->fresh(),
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Target $target)
	{
		Gate::authorize('delete', $target);

		$target->delete();

		return response()->json([
			'message' => 'Deleted',
			'data' => $target,
		]);
	}

	/**
	 * Last target amount
	 */
	public function last()
	{
		return new TargetResource(Target::latest('id')->first());
	}
}
