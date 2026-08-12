<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            'webhooks/*',
            'ai/chat/*',
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
            \Modules\Analytics\Http\Middleware\TrackVisitor::class,
            \Modules\Admin\Http\Middleware\DynamicRateLimit::class,
        ]);

        $middleware->alias([
            'user_type' => \App\Http\Middleware\EnsureUserType::class,
        ]);

        $middleware->redirectUsersTo(function ($request) {
            if ($request->is('admin/*') || $request->is('admin')) {
                return route('admin.dashboard');
            }
            if ($request->is('team/*') || $request->is('team')) {
                return route('team.dashboard');
            }
            if ($request->is('client/*') || $request->is('client')) {
                return route('client.dashboard');
            }
            return '/home';
        });

        $middleware->redirectGuestsTo(function ($request) {
            if ($request->is('admin/*') || $request->is('admin')) {
                return route('admin.login');
            }
            if ($request->is('team/*') || $request->is('team')) {
                return route('team.login');
            }
            if ($request->is('client/*') || $request->is('client')) {
                return route('client.login');
            }
            return route('client.login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
