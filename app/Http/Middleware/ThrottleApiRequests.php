<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ThrottleApiRequests
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('api/*')) {
            $ip = $request->ip();
            $key = "api:{$ip}";

            if (RateLimiter::tooManyAttempts($key, 5)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Too Many Requests',
                    'data' => [],
                ], 429);
            }

            RateLimiter::hit($key);
        }

        return $next($request);
    }
}
