<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Upload\UserImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Throwable;

/**
 * Admin user controller
 */
class UserController extends Controller
{
	/**
	 * Allowed order by fields
	 *
	 * @var array
	 */
	protected $allow_orderby = ['id', 'email', 'name', 'mobile'];

	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		Gate::authorize('viewAny', User::class);

		$q = User::query();

		$perpage = request()->integer('perpage', default: config('default.panel_perpage', 12));

		$orderby = request()->input('orderby', default: 'id');

		if (!in_array($orderby, $this->allow_orderby)) {
			$orderby = 'id';
		}

		request()->filled('asc') ?  $q->oldest($orderby) :  $q->latest($orderby);

		if (request()->filled('search')) {
			$q->searchField('name', request()->input('search'));
		}

		if (request()->filled('search')) {
			$q->searchField('email', request()->input('search'));
		}

		// if (request()->filled('search')) {
		//     $q->searchField('mobile', request()->input('search'));
		// }

		return new UserCollection($q->paginate($perpage));
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
	public function store(UserStoreRequest $request)
	{
		Gate::authorize('create', User::class);

		$user = User::create($request->safe()->except(['image', 'password']));

		$user->password = Hash::make($request->validated()['password']);

		$user->save();

		try {
			$path = (new UserImage)->upload($request, $user);
		} catch (Throwable $e) {
			report($e->getMessage());
		}

		return response()->json([
			'message' => 'Created',
			'data' => $user,
		], 201);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(User $user)
	{
		Gate::authorize('view', $user);

		return new UserResource($user);
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(User $user)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UserUpdateRequest $request, User $user)
	{
		Gate::authorize('update', $user);

		$user->update($request->safe()->except(['image', 'email', 'password']));

		(new UserImage())->upload($request, $user);

		return response()->json([
			'message' => 'Updated',
			'data' => $user,
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(User $user)
	{
		Gate::authorize('delete', $user);

		// Disabled

		return response()->json([
			'message' => 'Deleted',
			'data' => $user,
		]);
	}

	/**
	 * Get logged user details.
	 *
	 * @param Request $request
	 * @return object Logged user
	 */
	function details(Request $request)
	{
		return Auth::user()->fresh(['roles', 'permissions']) ?? null;
	}
}
