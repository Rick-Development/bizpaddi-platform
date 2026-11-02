<?php

namespace Modules\SuperAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SaasPlan;
use App\Models\Tenant;
use App\Models\TenantSubscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TenantController extends Controller
{
    public function index(Request $request)
    {
        $query = Tenant::with(['activeSubscription.plan', 'users']);

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $tenants = $query->latest()->paginate(15)->withQueryString();

        return view('superadmin::tenants.index', compact('tenants'));
    }

    public function create()
    {
        $plans = SaasPlan::active()->get();
        return view('superadmin::tenants.create', compact('plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:100|alpha_dash|unique:tenants,slug',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'plan_id' => 'required|exists:saas_plans,id',
            'trial_days' => 'nullable|integer|min:0|max:365',
            'ai_enabled' => 'nullable|boolean',
            'ai_monthly_token_quota' => 'nullable|integer|min:0',
        ]);

        $trialDays = (int) ($request->get('trial_days') ?? 14);

        $tenant = Tenant::create([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => $trialDays > 0 ? 'trialing' : 'active',
            'trial_ends_at' => $trialDays > 0 ? Carbon::now()->addDays($trialDays) : null,
            'currency' => 'NGN',
            'ai_enabled' => $request->boolean('ai_enabled'),
            'ai_monthly_token_quota' => $request->get('ai_monthly_token_quota', 0),
            'primary_color' => '#0E5E4A',
            'secondary_color' => '#10231C',
            'accent_color' => '#F2A900',
        ]);

        $plan = SaasPlan::findOrFail($request->plan_id);

        TenantSubscription::create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'seats_purchased' => $plan->included_users ?: 1,
            'amount_paid' => $plan->price,
            'currency' => 'NGN',
            'current_period_start' => Carbon::now(),
            'current_period_end' => Carbon::now()->addMonth(),
            'status' => $tenant->status,
            'payment_method' => 'billway',
        ]);

        return redirect()->route('super-admin.tenants.show', $tenant)
            ->with('success', "Tenant business {$tenant->name} created successfully!");
    }

    public function show(Tenant $tenant)
    {
        $tenant->load(['activeSubscription.plan', 'subscriptions.plan', 'payments', 'users']);
        $usersCount = $tenant->users()->count();
        $subscription = $tenant->activeSubscription;

        return view('superadmin::tenants.show', compact('tenant', 'usersCount', 'subscription'));
    }

    public function edit(Tenant $tenant)
    {
        $plans = SaasPlan::active()->get();
        return view('superadmin::tenants.edit', compact('tenant', 'plans'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => "required|string|max:100|alpha_dash|unique:tenants,slug,{$tenant->id}",
            'custom_domain' => "nullable|string|max:255|unique:tenants,custom_domain,{$tenant->id}",
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'currency' => 'required|string|max:10',
            'status' => 'required|in:trialing,active,past_due,suspended,cancelled',
            'ai_enabled' => 'nullable|boolean',
            'ai_monthly_token_quota' => 'nullable|integer|min:0',
            'primary_color' => 'nullable|string|max:20',
            'accent_color' => 'nullable|string|max:20',
        ]);

        $tenant->update([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
            'custom_domain' => $request->custom_domain ? Str::lower(trim($request->custom_domain)) : null,
            'email' => $request->email,
            'phone' => $request->phone,
            'currency' => $request->currency,
            'status' => $request->status,
            'ai_enabled' => $request->boolean('ai_enabled'),
            'ai_monthly_token_quota' => $request->get('ai_monthly_token_quota', 0),
            'primary_color' => $request->primary_color ?: '#0E5E4A',
            'accent_color' => $request->accent_color ?: '#F2A900',
        ]);

        return redirect()->route('super-admin.tenants.show', $tenant)
            ->with('success', 'Tenant details updated successfully.');
    }

    public function toggleStatus(Request $request, Tenant $tenant)
    {
        $newStatus = $tenant->status === 'active' ? 'suspended' : 'active';
        $tenant->update(['status' => $newStatus]);

        return back()->with('success', "Tenant status changed to {$newStatus}.");
    }

    public function extendTrial(Request $request, Tenant $tenant)
    {
        $days = (int) $request->get('days', 14);
        $currentEnd = $tenant->trial_ends_at && $tenant->trial_ends_at->isFuture()
            ? $tenant->trial_ends_at
            : Carbon::now();

        $tenant->update([
            'status' => 'trialing',
            'trial_ends_at' => $currentEnd->copy()->addDays($days),
        ]);

        return back()->with('success', "Trial extended by {$days} days for {$tenant->name}.");
    }

    public function impersonate(Tenant $tenant)
    {
        session(['impersonated_tenant_id' => $tenant->id]);
        return redirect()->route('admin.dashboard')
            ->with('info', "Now managing {$tenant->name} workspace. Return to Super Admin anytime.");
    }

    public function stopImpersonating()
    {
        session()->forget('impersonated_tenant_id');
        return redirect()->route('super-admin.dashboard')
            ->with('success', 'Returned to Super Admin control center.');
    }
}
