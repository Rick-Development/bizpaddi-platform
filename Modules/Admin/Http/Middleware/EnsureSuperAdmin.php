<?php

namespace Modules\Admin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdmin
{
    /**
     * Handle an incoming request.
     * Restrict platform administration features strictly to Platform Super Administrators.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $admin = auth('admin')->user();

        if (!$admin || !method_exists($admin, 'isSuperAdmin') || !$admin->isSuperAdmin()) {
            abort(403, 'Unauthorized. Platform administration and global CMS features are restricted to Super Administrators.');
        }

        return $next($request);
    }
}
