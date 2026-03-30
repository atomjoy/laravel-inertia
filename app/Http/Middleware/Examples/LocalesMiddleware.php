<?php

namespace App\Http\Middleware;

use Closure;
use Throwable;

/**
 *  Only json response allowed
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  \Closure  $next
 * @return mixed
 *
 */
class LocalesMiddleware
{
	public function handle($request, Closure $next)
	{
		try {
			$lang =  session('locale', config('app.locale'));

			app()->setLocale($lang);

			// Query only
			if ($request->filled('locale')) {
				app()->setLocale($request->query('locale'));
			}
		} catch (Throwable $e) {
			report($e);
		}

		return $next($request);
	}
}
