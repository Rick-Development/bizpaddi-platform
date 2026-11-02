<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    protected static bool $isResolving = false;

    /**
     * Resolve the current active tenant ID, or null if platform super admin / unauthenticated.
     */
    public static function getActiveTenantId(): ?int
    {
        if (static::$isResolving) {
            return null;
        }

        static::$isResolving = true;

        try {
            // 1. Super admin impersonating a tenant business
            if (session()->has('impersonated_tenant_id')) {
                return (int) session('impersonated_tenant_id');
            }

            // 2. Explicit tenant bound in container (subdomain routing, queue jobs, etc.)
            if (app()->has('current_tenant_id')) {
                return (int) app('current_tenant_id');
            }

            // 3. Admin guard (Business workspace admin or Super admin)
            if (auth('admin')->check()) {
                $admin = auth('admin')->user();
                if ($admin && method_exists($admin, 'isSuperAdmin') && $admin->isSuperAdmin()) {
                    return null; // Platform super admin has global access
                }
                return ($admin && !empty($admin->tenant_id)) ? (int) $admin->tenant_id : null;
            }

            // 4. Client guard (Client Portal)
            if (auth('client')->check()) {
                $client = auth('client')->user();
                return ($client && !empty($client->tenant_id)) ? (int) $client->tenant_id : null;
            }

            // 5. Default web guard (Staff / Team member)
            if (auth()->check()) {
                $user = auth()->user();
                if ($user && method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
                    return null;
                }
                return ($user && !empty($user->tenant_id)) ? (int) $user->tenant_id : null;
            }

            return null;
        } finally {
            static::$isResolving = false;
        }
    }

    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Skip in console (migrations, seeders, queues) unless explicitly bound
        if (app()->runningInConsole() && !app()->has('current_tenant_id')) {
            return;
        }

        $tenantId = self::getActiveTenantId();

        if ($tenantId !== null) {
            $builder->where($model->getTable() . '.tenant_id', $tenantId);
        }
    }
}
