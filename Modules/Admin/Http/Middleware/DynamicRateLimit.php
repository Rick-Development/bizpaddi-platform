<?php

namespace Modules\Admin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Modules\Admin\Models\RateLimit;
use Symfony\Component\HttpFoundation\Response;

class DynamicRateLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = $request->route()?->getName();

        if ($routeName) {
            // Check if there is a rate limit rule for this route
            $limit = RateLimit::where('route_name', $routeName)
                ->where('is_enabled', true)
                ->first();

            if ($limit) {
                $key = 'dynamic_limit:' . $routeName . ':' . $request->ip();

                if (RateLimiter::tooManyAttempts($key, $limit->max_attempts)) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Too many requests. Please try again later.',
                        ], 429);
                    }
                    
                    abort(429, 'Too many requests. Please try again later.');
                }

                RateLimiter::hit($key, $limit->decay_minutes * 60);
            }
        }

        return $next($request);
    }
}
