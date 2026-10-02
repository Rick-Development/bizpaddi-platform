<?php

namespace Modules\TeamPortal\Http\Middleware;

	use Closure;
	use Illuminate\Http\Request;

	class CheckUserStatus
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
	        // Allow admin impersonation to inspect the account regardless of status
	        if (session()->has('admin_impersonator_id') || auth('admin')->check()) {
	            return $next($request);
	        }

	        if (auth()->check() && auth()->user()->status !== 'active') {
	            // Allow logout and account-status routes
	            if ($request->routeIs('team.account-status') || $request->routeIs('team.logout')) {
	                return $next($request);
	            }

	            return redirect()->route('team.account-status');
	        }

	        // If user is active but tries to access account-status, redirect to dashboard
	        if (auth()->check() && auth()->user()->status === 'active' && $request->routeIs('team.account-status')) {
	            return redirect()->route('team.dashboard');
	        }

	        return $next($request);
	    }
	}
