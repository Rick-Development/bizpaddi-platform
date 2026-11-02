<?php

namespace Modules\SuperAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SaasPlan;
use App\Models\Tenant;
use App\Models\TenantPayment;
use App\Models\TenantSubscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        $totalTenants = Tenant::count();
        $activeTenants = Tenant::where('status', 'active')->count();
        $trialingTenants = Tenant::where('status', 'trialing')->count();
        $suspendedTenants = Tenant::where('status', 'suspended')->count();

        // Calculate Monthly Recurring Revenue (MRR)
        $activeSubscriptions = TenantSubscription::where('status', 'active')
            ->with('plan')
            ->get();

        $mrr = $activeSubscriptions->sum(function ($sub) {
            if (!$sub->plan) return 0;
            if ($sub->plan->billing_interval === 'yearly') {
                return (float) ($sub->amount_paid / 12);
            }
            return (float) $sub->amount_paid;
        });

        $totalUsers = User::count();
        $totalPayments = TenantPayment::where('status', 'successful')->sum('amount');

        $recentTenants = Tenant::with(['activeSubscription.plan'])
            ->latest()
            ->take(5)
            ->get();

        $recentPayments = TenantPayment::with('tenant')
            ->latest()
            ->take(6)
            ->get();

        $plans = SaasPlan::active()->withCount('subscriptions')->get();

        return view('superadmin::dashboard', compact(
            'totalTenants',
            'activeTenants',
            'trialingTenants',
            'suspendedTenants',
            'mrr',
            'totalUsers',
            'totalPayments',
            'recentTenants',
            'recentPayments',
            'plans'
        ));
    }
}
