<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        return Inertia::render('Users/Index', [
            'users' => User::paginate(1),
            // 'users' => User::all()->map(fn($user) => [
            //     'id' => $user->id,
            //     'name' => $user->name,
            //     'email' => $user->email,
            //     // 'edit_url' => route('users.edit', $user),
            // ]),
            // 'create_url' => route('users.create'),
        ]);
    }
}
