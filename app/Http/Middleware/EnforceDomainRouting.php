<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnforceDomainRouting
{
    /**
     * Route requests across the 3-domain architecture:
     * 1. bizpaddi.com       -> Public marketing site & tenant storefronts
     * 2. app.bizpaddi.com   -> Customer workspaces (Business, Team, Client portals)
     * 3. admin.bizpaddi.com -> Super Admin & Platform Staff portal
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If ResolveTenantStorefront already matched a specific tenant subdomain/custom domain, let it pass
        if (app()->bound('current_tenant')) {
            return $next($request);
        }

        $host = strtolower($request->getHost());
        $baseDomain = strtolower(config('app.domain', 'bizpaddi.com'));
        $appDomain = strtolower(config('app.app_domain', 'app.' . $baseDomain));
        $adminDomain = strtolower(config('app.admin_domain', 'admin.' . $baseDomain));

        // When admin domain is merged into app domain (e.g. 2-domain architecture on Railway)
        $isMergedAdmin = ($adminDomain === $appDomain || $adminDomain === 'app.' . $baseDomain || env('APP_ADMIN_DOMAIN') === 'app.' . $baseDomain || env('MERGE_ADMIN_INTO_APP', true));

        // Determine current domain role: 'admin', 'app', 'web', or null (unscoped local IP like 127.0.0.1)
        $domainRole = null;
        $rootDomain = $baseDomain;

        if (!$isMergedAdmin && ($host === $adminDomain || str_starts_with($host, 'admin.') || str_starts_with($host, 'superadmin.'))) {
            $domainRole = 'admin';
            $rootDomain = preg_replace('/^(super)?admin\./', '', $host);
        } elseif ($host === $appDomain || str_starts_with($host, 'app.') || ($isMergedAdmin && (str_starts_with($host, 'admin.') || str_starts_with($host, 'superadmin.')))) {
            $domainRole = 'app';
            $rootDomain = preg_replace('/^(app|(super)?admin)\./', '', $host);
        } elseif ($host === $baseDomain || $host === 'www.' . $baseDomain || config('app.enforce_subdomains')) {
            $domainRole = 'web';
            $rootDomain = preg_replace('/^www\./', '', $host);
            if ($rootDomain === 'localhost' || filter_var($rootDomain, FILTER_VALIDATE_IP)) {
                $rootDomain = $baseDomain;
            }
        }

        // If accessed via plain localhost / 127.0.0.1 without ENFORCE_SUBDOMAINS, allow all routes for local convenience
        if ($domainRole === null) {
            return $next($request);
        }

        $scheme = $request->getScheme();
        $port = $request->getPort();
        $portSuffix = ($port && !in_array($port, [80, 443])) ? ':' . $port : '';

        $webOrigin = "{$scheme}://{$rootDomain}{$portSuffix}";
        $appOrigin = "{$scheme}://app.{$rootDomain}{$portSuffix}";
        $adminOrigin = $isMergedAdmin ? $appOrigin : "{$scheme}://admin.{$rootDomain}{$portSuffix}";

        // If traffic arrives on legacy admin. subdomain and admin is merged, redirect to app. domain immediately
        if ($isMergedAdmin && (str_starts_with($host, 'admin.') || str_starts_with($host, 'superadmin.'))) {
            return redirect()->away($appOrigin . $request->getRequestUri());
        }

        $path = trim($request->path(), '/');

        // Shared infrastructure & asset endpoints allowed on all 3 domains
        if ($this->isSharedPath($path)) {
            return $next($request);
        }

        // 1. Handle ADMIN domain (admin.bizpaddi.com)
        if ($domainRole === 'admin') {
            if ($path === '') {
                $adminUser = Auth::guard('admin')->user();
                if ($adminUser) {
                    if ($adminUser->role === 'super_admin') {
                        return redirect('/super-admin');
                    }
                    if (is_null($adminUser->tenant_id)) {
                        return redirect('/admin');
                    }
                    return redirect()->away($appOrigin . '/dashboard');
                }
                return redirect('/super-admin/login');
            }

            if ($request->isMethod('GET')) {
                if ($this->isMarketingPath($path)) {
                    return redirect()->away($webOrigin . $request->getRequestUri());
                }

                if ($this->isCustomerAppPath($path, $request)) {
                    return redirect()->away($appOrigin . $request->getRequestUri());
                }
            }

            return $next($request);
        }

        // 2. Handle APP domain (app.bizpaddi.com)
        if ($domainRole === 'app') {
            if ($path === '') {
                $adminUser = Auth::guard('admin')->user();
                if ($adminUser) {
                    if ($adminUser->role === 'super_admin') {
                        return redirect('/super-admin');
                    }
                    if (is_null($adminUser->tenant_id)) {
                        return redirect('/admin');
                    }
                    return redirect('/dashboard');
                }
                if (Auth::guard('web')->check()) {
                    return redirect('/dashboard/team/dashboard');
                }
                if (Auth::guard('client')->check()) {
                    return redirect('/dashboard/client/dashboard');
                }
                return redirect('/dashboard/login');
            }

            if ($request->isMethod('GET')) {
                // If admin is NOT merged, redirect platform admin paths away to adminOrigin
                if (!$isMergedAdmin && $this->isPlatformAdminPath($path)) {
                    return redirect()->away($adminOrigin . $request->getRequestUri());
                }

                if ($this->isMarketingPath($path)) {
                    return redirect()->away($webOrigin . $request->getRequestUri());
                }
            }

            return $next($request);
        }

        // 3. Handle WEB domain (bizpaddi.com)
        if ($domainRole === 'web' && $request->isMethod('GET')) {
            if ($this->isPlatformAdminPath($path)) {
                return redirect()->away($adminOrigin . $request->getRequestUri());
            }

            if ($this->isCustomerAppPath($path, $request)) {
                return redirect()->away($appOrigin . $request->getRequestUri());
            }
        }

        return $next($request);
    }

    /**
     * Endpoints that must work on any domain without redirecting.
     */
    protected function isSharedPath(string $path): bool
    {
        if ($path === 'super-admin/stop-impersonating') {
            return true;
        }

        $sharedPrefixes = [
            'storage/',
            'broadcasting/',
            'notifications',
            'fcm-token',
            'lang/',
            'up',
            'clear-cache',
            'deploy-git-pull',
            'webhooks/',
            'api/',
            'livewire/',
            '_debugbar/',
            'build/',
            'favicon.ico',
        ];

        foreach ($sharedPrefixes as $prefix) {
            if ($path === rtrim($prefix, '/') || str_starts_with($path, $prefix)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Super Admin & Platform Staff portal routes (belong on admin.bizpaddi.com).
     */
    protected function isPlatformAdminPath(string $path): bool
    {
        if ($path === 'super-admin' || str_starts_with($path, 'super-admin/')) {
            return $path !== 'super-admin/stop-impersonating';
        }

        $platformAdminExactOrPrefixes = [
            'admin',
            'admin/login',
            'admin/logout',
            'admin/dashboard',
            'admin/tenants',
            'admin/plans',
            'admin/transactions',
            'admin/administrators',
            'admin/website-content',
        ];

        foreach ($platformAdminExactOrPrefixes as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix . '/')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Customer Workspace routes (belong on app.bizpaddi.com).
     */
    protected function isCustomerAppPath(string $path, Request $request): bool
    {
        // Allow platform SuperAdmin to access corporate CMS management routes under /dashboard/* on admin.bizpaddi.com when not impersonating
        $adminUser = Auth::guard('admin')->user();
        if (
            $adminUser &&
            $adminUser->role === 'super_admin' &&
            !session()->has('impersonated_tenant_id') &&
            $this->isSuperAdminCmsDashboardPath($path)
        ) {
            return false;
        }

        $appPrefixes = [
            'dashboard',
            'login',
            'register',
            'team',
            'client',
            'accounting',
            'accountings',
            'project-management',
            'projectmanagements',
            'comments',
            'attachments',
            'time-logs',
            'ai',
            'mcp',
            'kyc',
        ];

        foreach ($appPrefixes as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix . '/')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Platform-level CMS routes inside /dashboard/* that SuperAdmin manages.
     */
    protected function isSuperAdminCmsDashboardPath(string $path): bool
    {
        $cmsPrefixes = [
            'dashboard/hero-sliders',
            'dashboard/featured-content',
            'dashboard/testimonials',
            'dashboard/leaders',
            'dashboard/timeline',
            'dashboard/awards',
            'dashboard/projects',
            'dashboard/industries',
            'dashboard/posts',
            'dashboard/categories',
            'dashboard/jobs',
            'dashboard/applications',
            'dashboard/seo',
            'dashboard/navigation',
            'dashboard/home',
            'dashboard/investor',
            'dashboard/visitors',
            'dashboard/rate-limits',
            'dashboard/system',
            'dashboard/subsidiaries',
            'dashboard/legal-pages',
        ];

        foreach ($cmsPrefixes as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix . '/')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Public marketing & storefront pages (belong on bizpaddi.com).
     */
    protected function isMarketingPath(string $path): bool
    {
        $marketingPrefixes = [
            'features',
            'pricing',
            'faq',
            'store',
            'about',
            'services',
            'portfolio',
            'careers',
            'blog',
            'contact',
            'legal',
            'privacy-policy',
            'terms-conditions',
            'subsidiaries',
            'investor',
        ];

        foreach ($marketingPrefixes as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix . '/')) {
                return true;
            }
        }

        return false;
    }
}

