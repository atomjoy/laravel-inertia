<?php

namespace App\Http\Controllers;

use App\Models\Widget;
use App\Http\Requests\StoreWidgetRequest;
use App\Http\Requests\UpdateWidgetRequest;
use App\Http\Resources\WidgetCollection;
use App\Http\Resources\WidgetResource;
use Illuminate\Support\Facades\Gate;

class WidgetController extends Controller
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
		Gate::authorize('viewAny', Widget::class);

		$q = Widget::query();

		$perpage = request()->integer('perpage', default: config('default.panel_perpage', 12));

		$orderby = request()->input('orderby', default: 'id');

		if (!in_array($orderby, $this->allow_orderby)) {
			$orderby = 'id';
		}

		if (request()->filled('sortpage')) {
			request()->query('sortpage') == 'asc' ? $q->oldest($orderby) : $q->latest($orderby);
		}


		if (request()->filled('search')) {
			$q->searchField('title', request()->input('search'));
			$q->searchField('website', request()->input('search'));
		}

		return new WidgetCollection($q->paginate($perpage));
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
	public function store(StoreWidgetRequest $request)
	{
		Gate::authorize('create', Widget::class);

		$valid = $request->validated();

		$widget = Widget::create($valid);

		return response()->json([
			'message' => 'Created',
			'data' => $widget,
		], 201);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Widget $widget)
	{
		Gate::authorize('view', $widget);

		return new WidgetResource($widget);
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Widget $widget)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdateWidgetRequest $request, Widget $widget)
	{
		Gate::authorize('update', $widget);

		$valid = $request->validated();
		$widget->update($valid);

		return response()->json([
			'message' => 'Updated',
			'data' => $widget->fresh(),
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Widget $widget)
	{
		Gate::authorize('delete', $widget);

		$widget->delete();

		return response()->json([
			'message' => 'Deleted',
			'data' => $widget,
		]);
	}

	/**
	 * Last widget amount
	 */
	public function last()
	{
		return new WidgetResource(Widget::latest('id')->first());
	}
}
