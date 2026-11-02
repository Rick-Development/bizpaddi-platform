<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            'webhooks/*',
            'api/v1/webhooks/*',
            'ai/chat/*',
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\ResolveTenantStorefront::class,
            \App\Http\Middleware\EnforceDomainRouting::class,
            \Modules\Analytics\Http\Middleware\TrackVisitor::class,
            \Modules\Admin\Http\Middleware\DynamicRateLimit::class,
        ]);

        $middleware->alias([
            'user_type' => \App\Http\Middleware\EnsureUserType::class,
        ]);

        $middleware->redirectUsersTo(function ($request) {
            $host = strtolower($request->getHost());
            $admin = \Illuminate\Support\Facades\Auth::guard('admin')->user();
            if ($admin) {
                if ($admin->role === 'super_admin') {
                    return route('super-admin.dashboard');
                }
                if (is_null($admin->tenant_id)) {
                    return url('/admin');
                }
                return route('admin.dashboard');
            }

            if (str_starts_with($host, 'admin.') || $request->is('super-admin/*') || $request->is('super-admin')) {
                return route('super-admin.dashboard');
            }
            if ($request->is('admin/*') || $request->is('admin')) {
                return url('/admin');
            }
            if ($request->is('dashboard/team/*') || $request->is('dashboard/team') || $request->is('team/*') || $request->is('team')) {
                return route('team.dashboard');
            }
            if ($request->is('dashboard/client/*') || $request->is('dashboard/client') || $request->is('client/*') || $request->is('client')) {
                return route('client.dashboard');
            }
            if (str_starts_with($host, 'app.') || $request->is('dashboard/*') || $request->is('dashboard')) {
                return route('admin.dashboard');
            }
            return '/home';
        });

        $middleware->redirectGuestsTo(function ($request) {
            $host = strtolower($request->getHost());
            if ($request->is('super-admin/*') || $request->is('super-admin')) {
                return route('super-admin.login');
            }
            if ($request->is('admin/*') || $request->is('admin')) {
                return route('platform-admin.login');
            }
            if (str_starts_with($host, 'admin.')) {
                return route('super-admin.login');
            }
            if ($request->is('dashboard/team/*') || $request->is('dashboard/team') || $request->is('team/*') || $request->is('team')) {
                return route('team.login');
            }
            if ($request->is('dashboard/client/*') || $request->is('dashboard/client') || $request->is('client/*') || $request->is('client')) {
                return route('client.login');
            }
            if (str_starts_with($host, 'app.') || $request->is('dashboard/*') || $request->is('dashboard')) {
                return route('admin.login');
            }
            return route('admin.login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
