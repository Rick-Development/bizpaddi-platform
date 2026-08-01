<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $type
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $type): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.'
            ], 401);
        }

        $isValid = match ($type) {
            'admin'  => $user instanceof \App\Models\Admin,
            'team'   => $user instanceof \App\Models\User,
            'client' => $user instanceof \Modules\Clients\Models\Client,
            default  => false,
        };

        if (!$isValid) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Access denied for this user type.'
            ], 403);
        }

        return $next($request);
    }
}
