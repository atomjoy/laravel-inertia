<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogRequest
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request
	 * @param  \Symfony\Component\HttpFoundation\Response
	 */
	public function handle(Request $request, Closure $next): Response
	{
		Log::build([
			'driver' => 'single',
			'path' => storage_path('logs/access.log'),
		])->info('Incoming request', [
			'ip' => $request->ip(),
			'url' => $request->fullUrl(),
			'method' => $request->method(),
			'user_agent' => $request->userAgent()
		]);

		return $next($request);
	}
}
