<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
	/**
	 * The root template that's loaded on the first page visit.
	 *
	 * @see https://inertiajs.com/server-side-setup#root-template
	 *
	 * @var string
	 */
	protected $rootView = 'app';

	/**
	 * Determines the current asset version.
	 *
	 * @see https://inertiajs.com/asset-versioning
	 */
	public function version(Request $request): ?string
	{
		return parent::version($request);
	}

	/**
	 * Define the props that are shared by default.
	 *
	 * @see https://inertiajs.com/shared-data
	 *
	 * @return array<string, mixed>
	 */
	public function share(Request $request): array
	{
		[$message, $author] = str(Inspiring::quotes()->random())->explode('-');

		return [
			...parent::share($request),
			'name' => config('app.name'),
			'quote' => ['message' => trim($message), 'author' => trim($author)],
			'auth' => [
				// With casts and relation if loaded
				// 'user' => $request->user(),
				// Limit relations data (does not casts datetime:Y-m-d H:i:s here), must create formated attribute
				'user' => $request->user()?->only(['id', 'name', 'email', 'created_at', 'formated_created_at']),
				// Always refresh relations with casts
				// 'user' => $request->user()?->fresh(['roles', 'permissions']),
				// With Spatie roles and permissions
				'role' => [
					'superadmin' => $request->user()?->isSuperAdmin(),
					'admin' => $request->user()?->isAdmin(),
					'writer' => $request->user()?->isWriter(),
				],
				'permission' => [
					'profil_update' => $request->user()?->can('profil_update'),
					'account_delete' => $request->user()?->can('account_delete'),
				],
				'roles' => $request->user()?->allRoles(),
				'permissions' => $request->user()?->allPermissions(),
			],
			'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
		];
	}
}
