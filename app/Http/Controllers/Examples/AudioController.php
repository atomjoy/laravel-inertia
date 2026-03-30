<?php

namespace App\Http\Controllers;

use App\Models\Audio;
use App\Http\Requests\StoreAudioRequest;
use App\Http\Requests\UpdateAudioRequest;
use App\Http\Resources\AudioCollection;
use App\Http\Resources\AudioResource;
use App\Upload\AudioMp3;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class AudioController extends Controller
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
		Gate::authorize('viewAny', Audio::class);

		$q = Audio::query();

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

		return new AudioCollection($q->paginate($perpage));
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
	public function store(StoreAudioRequest $request)
	{
		Gate::authorize('create', Audio::class);

		$audio = Audio::create($request->safe()->only('name'));

		(new AudioMp3())->upload($request, $audio);

		return response()->json([
			'message' => 'Created',
			'data' => $audio,
		], 201);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Audio $audio)
	{
		Gate::authorize('view', $audio);

		return new AudioResource($audio);
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Audio $audio)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdateAudioRequest $request, Audio $audio)
	{
		Gate::authorize('update', $audio);

		$audio->update($request->safe()->only('name'));

		(new AudioMp3())->upload($request, $audio);

		return response()->json([
			'message' => 'Updated',
			'data' => $audio->fresh(),
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Audio $audio)
	{
		Gate::authorize('delete', $audio);

		$audio->delete();

		return response()->json([
			'message' => 'Deleted',
			'data' => $audio,
		]);
	}

	/**
	 * Last gif amount
	 */
	public function last()
	{
		return new AudioResource(Audio::latest('id')->first());
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
