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
            'company_name' => ['required', 'string', 'max:120'],
            'first_name'   => ['required', 'string', 'max:60'],
            'last_name'    => ['required', 'string', 'max:60'],
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:admins,email'],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
            'phone'        => ['nullable', 'string', 'max:20'],
            'agree_terms'  => ['required', 'accepted'],
        ], [
            'email.unique'         => 'An account with this email address already exists. Please sign in.',
            'agree_terms.accepted' => 'You must accept the Terms of Service to create a workspace.',
        ]);

        $plan = SaasPlan::where('code', 'starter_monthly')->first() ?? SaasPlan::active()->first() ?? SaasPlan::first();

        DB::beginTransaction();
        try {
            $baseSlug = Str::slug($request->company_name);
            if (empty($baseSlug)) {
                $baseSlug = 'business';
            }
            $cleanSlug = $baseSlug;
            while (Tenant::where('slug', $cleanSlug)->exists()) {
                $cleanSlug = $baseSlug . '-' . rand(100, 9999);
            }

            $fullName = trim($request->first_name . ' ' . $request->last_name);
            $isPayg = (!$plan || $plan->code === 'pay_as_you_go' || $plan->billing_type === 'payg');

            // 1. Create Tenant Business (defaults to Pay-As-You-Transact free on-boarding)
            $tenant = Tenant::create([
                'name' => trim($request->company_name),
                'slug' => $cleanSlug,
                'email' => strtolower(trim($request->email)),
                'phone' => $request->phone,
                'status' => 'active',
                'billing_model' => 'payg',
                'wallet_balance' => 0.00,
                'trial_ends_at' => null,
                'currency' => 'NGN',
                'country' => 'NG',
                'ai_enabled' => false,
                'primary_color' => '#0E5E4A',
                'secondary_color' => '#F2A900',
                'accent_color' => '#10231C',
                'settings' => [
                    'phone' => $request->phone,
                    'currency' => 'NGN',
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

            return redirect()->route('admin.dashboard')->with('success', "🎉 Welcome to Bizpaddi! Your workspace for {$tenant->name} has been provisioned successfully.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors([
                'error' => 'Unable to complete workspace creation: ' . $e->getMessage(),
            ]);
        }
    }
}
