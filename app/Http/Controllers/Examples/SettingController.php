<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Http\Requests\StoreSettingRequest;
use App\Http\Requests\UpdateSettingRequest;
use App\Http\Resources\SettingCollection;
use App\Http\Resources\SettingResource;
use App\Upload\SettingFile;
use App\Upload\SettingImage;
use Illuminate\Support\Facades\Gate;

class SettingController extends Controller
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
		Gate::authorize('viewAny', Setting::class);

		$q = Setting::query();

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

		return new SettingCollection($q->paginate($perpage));
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
	public function store(StoreSettingRequest $request)
	{
		Gate::authorize('create', Setting::class);

		$valid = $request->safe()->only(['name', 'value', 'input']);

		$setting = Setting::create($valid);
		// Jpg, Png, Webp
		(new SettingImage())->upload($request, $setting);
		// Zip, Pdf, Mp3
		(new SettingFile())->upload($request, $setting);

		return response()->json([
			'message' => 'Created',
			'data' => $setting,
		], 201);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Setting $setting)
	{
		Gate::authorize('view', $setting);

		return new SettingResource($setting);
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Setting $setting)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdateSettingRequest $request, Setting $setting)
	{
		Gate::authorize('update', $setting);

		$valid = $request->safe()->only(['name', 'value']);

		$setting->update($valid);
		// Jpg, Png, Webp
		(new SettingImage())->upload($request, $setting);
		// Zip, Pdf, Mp3
		(new SettingFile())->upload($request, $setting);

		return response()->json([
			'message' => 'Updated',
			'data' => $setting->fresh(),
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Setting $setting)
	{
		Gate::authorize('delete', $setting);

		$setting->delete();

		return response()->json([
			'message' => 'Deleted',
			'data' => $setting,
		]);
	}

	/**
	 * Last setting amount
	 */
	public function last()
	{
		return new SettingResource(Setting::latest('id')->first());
	}
}
