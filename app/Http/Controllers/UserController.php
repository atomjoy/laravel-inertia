<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
	public function index()
	{
		return Inertia::render('Users/Index', [
			'users' => User::paginate(5),
			// 'users' => User::query()->paginate()->transform(function ($user) {
			//  $user->additional([]);
			// 	$user['can'] = ['update_user' => Auth::user()->can('update', $user)];
			// 	return UserResource::make($user);
			// }),
			// 'users' => User::all()->map(fn($user) => [
			//     'id' => $user->id,
			//     'name' => $user->name,
			//     'email' => $user->email,
			//     // 'update_url' => route('users.update', $user),
			// ]),
			// 'create_url' => route('users.create'),
			// 'json' => new JsonResponse(['key' => 'value']),
		]);
	}
}
