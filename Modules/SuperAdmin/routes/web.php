<?php

use Illuminate\Support\Facades\Route;
use Modules\SuperAdmin\Http\Controllers\SuperAdminDashboardController;
use Modules\SuperAdmin\Http\Controllers\TenantController;
use Modules\SuperAdmin\Http\Controllers\PlanController;
use Modules\SuperAdmin\Http\Controllers\TransactionController;
use Modules\SuperAdmin\Http\Controllers\SettingsController;

use Modules\SuperAdmin\Http\Controllers\SuperAdminAuthController;
use Modules\SuperAdmin\Http\Controllers\PlatformAdminAuthController;

// Super Admin Authentication Routes (/super-admin/login)
Route::prefix('super-admin')->name('super-admin.')->middleware(['web'])->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [SuperAdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [SuperAdminAuthController::class, 'login']);
    });
    Route::post('/logout', [SuperAdminAuthController::class, 'logout'])->name('logout');
});

// Super Admin Protected Routes (/super-admin/...)
Route::prefix('super-admin')
    ->name('super-admin.')
    ->middleware(['web', 'auth:admin'])
    ->group(function () {
        // Dashboard
        Route::get('/', [SuperAdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [SuperAdminDashboardController::class, 'index']);

        // Tenants / Businesses Management
        Route::resource('tenants', TenantController::class);
        Route::post('tenants/{tenant}/status', [TenantController::class, 'toggleStatus'])->name('tenants.status');
        Route::post('tenants/{tenant}/extend-trial', [TenantController::class, 'extendTrial'])->name('tenants.extend-trial');
        Route::post('tenants/{tenant}/impersonate', [TenantController::class, 'impersonate'])->name('tenants.impersonate');
        Route::get('stop-impersonating', [TenantController::class, 'stopImpersonating'])->name('stop-impersonating');

        // Subscription Plans (Tiered & Per-Seat)
        Route::resource('plans', PlanController::class);

        // Billway Transactions
        Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('transactions/{payment}', [TransactionController::class, 'show'])->name('transactions.show');

        // Super Admin Settings (Billway & Platform)
        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');

        // Website Copy & Content CMS
        Route::get('website-content', [\Modules\SuperAdmin\Http\Controllers\WebsiteContentController::class, 'index'])->name('website-content.index');
        Route::post('website-content', [\Modules\SuperAdmin\Http\Controllers\WebsiteContentController::class, 'update'])->name('website-content.update');

        // Platform Administrators
        Route::resource('administrators', \Modules\Admin\Http\Controllers\AdminManagementController::class);
    });

// Platform Admin Staff Portal (/admin) for internal workers managing the platform
Route::prefix('admin')
    ->name('platform-admin.')
    ->middleware(['web'])
    ->group(function () {
        Route::middleware('guest:admin')->group(function () {
            Route::get('/login', [PlatformAdminAuthController::class, 'showLoginForm'])->name('login');
            Route::post('/login', [PlatformAdminAuthController::class, 'login']);
        });
        Route::post('/logout', [PlatformAdminAuthController::class, 'logout'])->name('logout');

        Route::middleware(['auth:admin'])->group(function () {
            Route::get('/', function () {
                $user = auth('admin')->user();
                if ($user && $user->tenant_id) {
                    return redirect('/dashboard');
                }
                return app(SuperAdminDashboardController::class)->index();
            })->name('dashboard');

            Route::get('/dashboard', function () {
                $user = auth('admin')->user();
                if ($user && $user->tenant_id) {
                    return redirect('/dashboard');
                }
                return app(SuperAdminDashboardController::class)->index();
            });

            Route::resource('tenants', TenantController::class);
            Route::resource('plans', PlanController::class);
            Route::resource('transactions', TransactionController::class);
            Route::resource('administrators', \Modules\Admin\Http\Controllers\AdminManagementController::class);
            Route::get('website-content', [\Modules\SuperAdmin\Http\Controllers\WebsiteContentController::class, 'index'])->name('website-content.index');
            Route::post('website-content', [\Modules\SuperAdmin\Http\Controllers\WebsiteContentController::class, 'update'])->name('website-content.update');
        });
    });

// Redirect any legacy /admin/{path} for business tenants to /dashboard/{path}
Route::get('admin/{path}', function ($path) {
    return redirect('/dashboard/' . $path);
})->where('path', '^(?!login|logout|dashboard|tenants|plans|transactions|administrators|website-content).*$')->middleware(['web']);

// Public Webhook for Billway (CSRF exempt)
Route::post('api/v1/webhooks/billway', [TransactionController::class, 'handleWebhook'])->name('webhooks.billway');
