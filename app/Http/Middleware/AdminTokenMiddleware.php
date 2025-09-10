<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Simple bearer token auth for admin-only API access.
 * Compares Authorization header token to config('api.admin_token') using constant-time comparison.
 */
final class AdminTokenMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $configuredToken = (string) config('api.admin_token');

        if ($configuredToken === '') {
            return response()->json([
                'message' => 'API admin token not configured.'
            ], 503);
        }

        $header = (string) $request->header('Authorization', '');

        if (! str_starts_with($header, 'Bearer ')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $provided = substr($header, 7);

        if (! hash_equals($configuredToken, $provided)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}


