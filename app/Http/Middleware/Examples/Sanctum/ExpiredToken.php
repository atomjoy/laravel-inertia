<?php

namespace App\Http\Middleware\Sanctum;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * Sanctum expired token middleware.
 *
 * Add middleware in bootstrap/app.php
 * $middleware->api(prepend: [ \App\Http\Middleware\Sanctum\ExpiredToken::class ]);
 */
class ExpiredToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request
     * @param  \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        $bearer = $request->bearerToken();

        if ($bearer) {
            $token = PersonalAccessToken::findToken($bearer);

            if ($token instanceof PersonalAccessToken) {
                if ($token->expires_at && $token->expires_at->isPast()) {
                    $request->merge([
                        'token_expired' => true,
                        'token_details' => $token
                    ]);

                    return response()->json([
                        'message' => 'Expired token.',
                        'token_expired' => true,
                        'token_details' => $token
                    ], 403);
                }
            }
        }

        return $next($request);
    }
}
