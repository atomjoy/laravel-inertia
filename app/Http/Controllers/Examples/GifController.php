<?php

namespace App\Http\Controllers;

use App\Models\Gif;
use App\Http\Requests\StoreGifRequest;
use App\Http\Requests\UpdateGifRequest;
use App\Http\Resources\GifCollection;
use App\Http\Resources\GifResource;
use App\Upload\GifImage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class GifController extends Controller
{
	/**
	 * Allowed order by fields
	 *
	 * @var array
	 */
	protected $allow_orderby = ['id', 'name', 'created_at'];

	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		Gate::authorize('viewAny', Gif::class);

		$q = Gif::query();

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
		}

		return new GifCollection($q->paginate($perpage));
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
	public function store(StoreGifRequest $request)
	{
		Gate::authorize('create', Gif::class);

		$gif = Gif::create($request->safe()->only('name'));

		(new GifImage())->upload($request, $gif);

		return response()->json([
			'message' => 'Created',
			'data' => $gif,
		], 201);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Gif $gif)
	{
		Gate::authorize('view', $gif);

		return new GifResource($gif);
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Gif $gif)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdateGifRequest $request, Gif $gif)
	{
		Gate::authorize('update', $gif);

		$gif->update($request->safe()->only('name'));

		(new GifImage())->upload($request, $gif);

		return response()->json([
			'message' => 'Updated',
			'data' => $gif->fresh(),
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Gif $gif)
	{
		Gate::authorize('delete', $gif);

		$gif->delete();

		return response()->json([
			'message' => 'Deleted',
			'data' => $gif,
		]);
	}

	/**
	 * Last gif amount
	 */
	public function last()
	{
		return new GifResource(Gif::latest('id')->first());
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
