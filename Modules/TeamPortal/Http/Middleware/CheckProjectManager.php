<?php

namespace Modules\TeamPortal\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckProjectManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Allow admin impersonation or admin auth to inspect project manager routes
        if (session()->has('admin_impersonator_id') || \Illuminate\Support\Facades\Auth::guard('admin')->check()) {
            return $next($request);
        }

        if (!\Illuminate\Support\Facades\Auth::guard('web')->check()) {
            return redirect()->route('team.login');
        }

        $user = \Illuminate\Support\Facades\Auth::guard('web')->user();

        // Check if user has project manager role or permissions
        if (!$user?->isProjectManager()) {
            abort(403, 'Access denied. Project Manager role required.');
        }

        // If accessing a specific project, verify they manage it
        if ($request->route('project')) {
            $project = $request->route('project');
            
            if (!$project->canBeManagedBy($user)) {
                abort(403, 'You do not have authority to manage this project.');
            }
        }

        return $next($request);
    }
}
