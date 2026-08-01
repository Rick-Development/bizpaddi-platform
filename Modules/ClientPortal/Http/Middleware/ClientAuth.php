<?php

namespace Modules\ClientPortal\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('client')->check()) {
            return redirect()->route('client.login');
        }

        return $next($request);
    }
}
