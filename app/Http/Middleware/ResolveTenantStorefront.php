<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenantStorefront
{
    /**
     * Handle incoming request by resolving the tenant from subdomain or custom domain.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = strtolower($request->getHost());
        $baseHost = strtolower(config('app.domain', parse_url(config('app.url'), PHP_URL_HOST) ?? 'bizpaddi.com'));

        $platformHosts = [
            'localhost',
            '127.0.0.1',
            $baseHost,
            'www.' . $baseHost,
            'app.' . $baseHost,
            'admin.' . $baseHost,
            'superadmin.' . $baseHost,
            'app.localhost',
            'admin.localhost',
        ];

        $tenant = null;

        // 1. Check if accessing a custom domain (only when not on a primary platform host)
        if (!in_array($host, $platformHosts, true)) {
            $tenant = Tenant::where('custom_domain', $host)->first();

            // 2. Check if accessing a tenant subdomain (e.g. acme.bizpaddi.com)
            if (!$tenant && str_contains($host, '.' . $baseHost)) {
                $subdomain = str_replace('.' . $baseHost, '', $host);
                if (!in_array($subdomain, ['www', 'app', 'superadmin', 'admin', 'mail'], true)) {
                    $tenant = Tenant::where('slug', $subdomain)->first();
                }
            }
        }

        // 3. Fallback check for local development (e.g. ?tenant=slug)
        if (!$tenant && $request->has('tenant')) {
            $tenant = Tenant::where('slug', $request->get('tenant'))->first();
        }

        if ($tenant) {
            app()->instance('current_tenant', $tenant);
            app()->instance('current_tenant_id', $tenant->id);
            view()->share('currentTenant', $tenant);
        }

        return $next($request);
    }
}
