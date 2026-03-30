<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\IpUtils;

class AdminAllowedIps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request
     * @param  \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('api/admin/*')) {
            $list = config('access.admin_allowed_ips');

            if ($list != null) {
                $ips = explode('|', $list);

                if (!IpUtils::checkIp($request->ip(), $ips)) {
                    Log::build([
                        'driver' => 'single',
                        'path' => storage_path('logs/firewall.log'),
                    ])->error('Admin ip address is not allowed.', [
                        'ip' => $request->ip(),
                        'url' => $request->fullUrl(),
                        'method' => $request->method(),
                        'user_agent' => $request->userAgent(),
                        'user_id' => Auth::user()->id ?? null,
                    ]);

                    return response()->json([
                        'message' => 'Admin ip address is not allowed.'
                    ]);
                }
            }
        }

        return $next($request);
    }
}
