<?php

namespace Modules\SuperAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SaasPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlanController extends Controller
{
    public function index()
    {
        $plans = SaasPlan::withCount('subscriptions')->latest()->get();
        return view('superadmin::plans.index', compact('plans'));
    }

    public function create()
    {
        $availableFeatures = $this->getAvailableFeatures();
        return view('superadmin::plans.create', compact('availableFeatures'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100|alpha_dash|unique:saas_plans,code',
            'description' => 'nullable|string',
            'billing_type' => 'required|in:flat_tiered,per_seat,hybrid,payg',
            'price' => 'required|numeric|min:0',
            'price_per_user' => 'nullable|numeric|min:0',
            'billing_interval' => 'required|in:monthly,yearly',
            'included_users' => 'required|integer|min:1',
            'max_users' => 'nullable|integer|min:1',
            'enable_ai' => 'nullable|boolean',
            'enable_custom_domain' => 'nullable|boolean',
            'features' => 'nullable|array',
            'is_active' => 'nullable|boolean',
        ]);

        SaasPlan::create([
            'name' => $request->name,
            'code' => Str::slug($request->code, '_'),
            'description' => $request->description,
            'billing_type' => $request->billing_type,
            'price' => $request->price,
            'price_per_user' => $request->get('price_per_user', 0.00),
            'billing_interval' => $request->billing_interval,
            'included_users' => $request->included_users,
            'max_users' => $request->max_users ?: null,
            'enable_ai' => $request->boolean('enable_ai'),
            'enable_custom_domain' => $request->boolean('enable_custom_domain'),
            'features' => $request->features ?? [],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('super-admin.plans.index')
            ->with('success', 'New price plan created successfully.');
    }

    public function edit(SaasPlan $plan)
    {
        $availableFeatures = $this->getAvailableFeatures();
        return view('superadmin::plans.edit', compact('plan', 'availableFeatures'));
    }

    public function update(Request $request, SaasPlan $plan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => "required|string|max:100|alpha_dash|unique:saas_plans,code,{$plan->id}",
            'description' => 'nullable|string',
            'billing_type' => 'required|in:flat_tiered,per_seat,hybrid,payg',
            'price' => 'required|numeric|min:0',
            'price_per_user' => 'nullable|numeric|min:0',
            'billing_interval' => 'required|in:monthly,yearly',
            'included_users' => 'required|integer|min:1',
            'max_users' => 'nullable|integer|min:1',
            'enable_ai' => 'nullable|boolean',
            'enable_custom_domain' => 'nullable|boolean',
            'features' => 'nullable|array',
            'is_active' => 'nullable|boolean',
        ]);

        $plan->update([
            'name' => $request->name,
            'code' => Str::slug($request->code, '_'),
            'description' => $request->description,
            'billing_type' => $request->billing_type,
            'price' => $request->price,
            'price_per_user' => $request->get('price_per_user', 0.00),
            'billing_interval' => $request->billing_interval,
            'included_users' => $request->included_users,
            'max_users' => $request->max_users ?: null,
            'enable_ai' => $request->boolean('enable_ai'),
            'enable_custom_domain' => $request->boolean('enable_custom_domain'),
            'features' => $request->features ?? [],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('super-admin.plans.index')
            ->with('success', "Plan '{$plan->name}' updated successfully.");
    }

    public function destroy(SaasPlan $plan)
    {
        $plan->update(['is_active' => false]);
        return back()->with('success', "Plan '{$plan->name}' deactivated.");
    }

    protected function getAvailableFeatures(): array
    {
        return [
            'invoicing' => 'Invoicing & Quotes',
            'projects' => 'Project Management & Milestones',
            'tasks_kanban' => 'Kanban Board & Bug Tracking',
            'team_attendance' => 'Staff Clock-in & Attendance',
            'payroll' => 'Staff Payroll & Disbursements',
            'client_portal' => 'Client Portal with Self-Service Pay',
            'crm_leads' => 'CRM Leads & Outreach Pipelines',
            'storefront_website' => 'Public Storefront & CMS Builder',
            'custom_domain' => 'Custom Domain Mapping',
            'contracts_management' => 'Contracts & Digital Signatures',
            'social_media_hub' => 'Omnichannel Social Inbox',
            'recruitment_ats' => 'Recruitment & Job Board',
            'ai_center' => 'AI Center & Prompt Tools',
            'ai_workforce' => 'Autonomous AI Employees',
        ];
    }
}
