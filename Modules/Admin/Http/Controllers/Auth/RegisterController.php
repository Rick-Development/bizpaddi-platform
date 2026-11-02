<?php

namespace Modules\Admin\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\SaasPlan;
use App\Models\Tenant;
use App\Models\TenantSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /**
     * Show self-service SaaS registration form.
     */
    public function showRegistrationForm(Request $request)
    {
        $selectedPlan = null;
        if ($request->has('plan')) {
            $selectedPlan = SaasPlan::where('code', $request->query('plan'))->first();
        }
        $plans = SaasPlan::active()->get();

        return view('admin::auth.register', compact('plans', 'selectedPlan'));
    }

    /**
     * Register a new business tenant and administrator.
     */
    public function register(Request $request)
    {
        $request->validate([
            'company_name'       => ['required', 'string', 'max:120'],
            'workspace_slug'     => ['nullable', 'string', 'max:80', 'regex:/^[a-z0-9\-]+$/i'],
            'industry'           => ['nullable', 'string', 'max:80'],
            'company_size'       => ['nullable', 'string', 'max:40'],
            'country'            => ['nullable', 'string', 'max:10'],
            'currency'           => ['nullable', 'string', 'max:10'],
            'rc_number'          => ['nullable', 'string', 'max:50'],
            'first_name'         => ['required', 'string', 'max:60'],
            'last_name'          => ['required', 'string', 'max:60'],
            'job_title'          => ['nullable', 'string', 'max:80'],
            'email'              => ['required', 'string', 'email', 'max:255', 'unique:admins,email'],
            'phone_country_code' => ['nullable', 'string', 'max:10'],
            'phone'              => ['nullable', 'string', 'max:25'],
            'password'           => ['required', 'string', 'min:8', 'confirmed'],
            'agree_terms'        => ['required', 'accepted'],
        ], [
            'email.unique'         => 'An account with this email address already exists. Please sign in.',
            'workspace_slug.regex' => 'Workspace handle may only contain letters, numbers, and hyphens.',
            'agree_terms.accepted' => 'You must accept the Terms of Service to create a workspace.',
        ]);

        $requestedPlanCode = $request->input('plan') ?? $request->query('plan');
        $plan = $requestedPlanCode
            ? SaasPlan::where('code', $requestedPlanCode)->first()
            : null;

        DB::beginTransaction();
        try {
            $baseSlug = Str::slug($request->filled('workspace_slug') ? $request->workspace_slug : $request->company_name);
            if (empty($baseSlug)) {
                $baseSlug = 'business';
            }
            $cleanSlug = $baseSlug;
            while (Tenant::where('slug', $cleanSlug)->exists()) {
                $cleanSlug = $baseSlug . '-' . rand(100, 9999);
            }

            $fullName = trim($request->first_name . ' ' . $request->last_name);
            $isPayg = (!$plan || $plan->code === 'pay_as_you_go' || $plan->billing_type === 'payg');

            $rawPhone = trim((string) $request->phone);
            $dialCode = trim((string) ($request->phone_country_code ?: '+234'));
            $formattedPhone = $rawPhone !== ''
                ? (str_starts_with($rawPhone, '+') ? $rawPhone : $dialCode . ' ' . ltrim($rawPhone, '0'))
                : null;

            $country = strtoupper($request->input('country', 'NG') ?: 'NG');
            $currency = strtoupper($request->input('currency', 'NGN') ?: 'NGN');

            // 1. Create Tenant Business (defaults to Pay-As-You-Transact free on-boarding)
            $tenant = Tenant::create([
                'name' => trim($request->company_name),
                'slug' => $cleanSlug,
                'email' => strtolower(trim($request->email)),
                'phone' => $formattedPhone,
                'status' => 'active',
                'billing_model' => 'payg',
                'wallet_balance' => 0.00,
                'trial_ends_at' => null,
                'currency' => $currency,
                'country' => $country,
                'ai_enabled' => false,
                'primary_color' => '#0E5E4A',
                'secondary_color' => '#F2A900',
                'accent_color' => '#10231C',
                'settings' => [
                    'phone' => $formattedPhone,
                    'currency' => $currency,
                    'country' => $country,
                    'industry' => $request->input('industry', 'General Business'),
                    'company_size' => $request->input('company_size', '1-10'),
                    'job_title' => $request->input('job_title', 'Founder / Administrator'),
                    'rc_number' => $request->input('rc_number'),
                    'selected_plan' => $plan?->code ?? 'pay_as_you_go',
                ],
            ]);

            // 2. Create Tenant Admin
            $admin = Admin::create([
                'tenant_id' => $tenant->id,
                'name' => $fullName,
                'email' => strtolower(trim($request->email)),
                'password' => Hash::make($request->password),
                'role' => 'admin',
                'is_active' => true,
            ]);

            DB::commit();

            // 4. Authenticate newly created Admin
            Auth::guard('admin')->login($admin);
            $request->session()->put('current_tenant_id', $tenant->id);
            $request->session()->regenerate();

            if ($plan && !$isPayg) {
                return redirect()->route('admin.billing.index')
                    ->with('success', "🎉 Welcome to Bizpaddi! Activate your selected {$plan->name} plan below or top up your operating wallet.");
            }

            return redirect()->route('admin.dashboard')->with('success', "🎉 Welcome to Bizpaddi! Your workspace for {$tenant->name} has been provisioned successfully.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors([
                'error' => 'Unable to complete workspace creation: ' . $e->getMessage(),
            ]);
        }
    }
}
