<?php

namespace Modules\Admin\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\SaasPlan;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /**
     * Blocked placeholder, fake, and disposable email domains.
     */
    protected array $blockedDomains = [
        'example.com', 'example.org', 'example.net', 'example.edu',
        'test.com', 'test.org', 'test.net', 'testing.com',
        'fake.com', 'fakemail.com', 'fakeinbox.com', 'invalid.com',
        'localhost', 'domain.com', 'email.com', 'sample.com', 'demo.com',
        'company.com', 'mycompany.com', 'yoursite.com', 'yourdomain.com',
        'mailinator.com', 'yopmail.com', 'tempmail.com', 'temp-mail.org',
        '10minutemail.com', 'guerrillamail.com', 'sharklasers.com',
        'trashmail.com', 'getnada.com', 'maildrop.cc', 'dispostable.com',
        'throwawaymail.com', 'burnermail.io', 'mohmal.com', 'mailnesia.com',
    ];

    /**
     * Blocked fake or reserved top-level extensions.
     */
    protected array $blockedTlds = [
        'example', 'invalid', 'localhost', 'test', 'local', 'internal', 'fake', 'lan',
    ];

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
     * Validate that an email address does not use a fake/disposable domain or invalid TLD/MX.
     */
    protected function validateEmailDomain(string $email): ?string
    {
        $email = strtolower(trim($email));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !str_contains($email, '@')) {
            return 'Please enter a valid email address.';
        }

        [, $domain] = explode('@', $email, 2);
        $domain = trim($domain, '.');

        if (!str_contains($domain, '.')) {
            return 'Please enter an email address with a valid domain extension (e.g. .com, .ng, .org).';
        }

        $parts = explode('.', $domain);
        $tld = end($parts);

        if (strlen($tld) < 2 || in_array($tld, $this->blockedTlds, true)) {
            return "The domain extension '.{$tld}' is not allowed. Please use a real email address.";
        }

        if (in_array($domain, $this->blockedDomains, true)) {
            return "Emails from '@{$domain}' are not permitted. Please use your real personal or business email.";
        }

        foreach ($this->blockedDomains as $blocked) {
            if (str_ends_with($domain, '.' . $blocked)) {
                return "Emails from '@{$domain}' are not permitted. Please use your real personal or business email.";
            }
        }

        if (function_exists('checkdnsrr')) {
            $hasMx = @checkdnsrr($domain, 'MX');
            $hasA = !$hasMx && (@checkdnsrr($domain, 'A') || @checkdnsrr($domain, 'AAAA'));
            if (!$hasMx && !$hasA) {
                return "The email domain '@{$domain}' does not exist or cannot receive emails. Please check for typos.";
            }
        }

        return null;
    }

    /**
     * Send a 6-digit OTP verification code to the user's email during registration.
     */
    public function sendOtp(Request $request)
    {
        $email = strtolower(trim((string) $request->input('email')));

        if (empty($email)) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter your email address first.',
            ], 422);
        }

        $domainError = $this->validateEmailDomain($email);
        if ($domainError) {
            return response()->json([
                'success' => false,
                'message' => $domainError,
            ], 422);
        }

        if (Admin::where('email', $email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'An account with this email address already exists. Please sign in instead.',
            ], 422);
        }

        $throttleKey = 'reg-otp-send:' . $request->ip() . ':' . $email;
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return response()->json([
                'success' => false,
                'message' => "Too many code requests. Please wait {$seconds} seconds before trying again.",
            ], 429);
        }
        RateLimiter::hit($throttleKey, 300);

        $otp = (string) random_int(100000, 999999);
        $cacheKey = 'bizpaddi_reg_otp_' . hash('sha256', $email);

        Cache::put($cacheKey, $otp, now()->addMinutes(15));
        $request->session()->put($cacheKey, [
            'otp' => $otp,
            'expires_at' => now()->addMinutes(15)->timestamp,
        ]);

        $companyName = trim((string) $request->input('company_name', 'your workspace'));

        try {
            Mail::send([], [], function ($message) use ($email, $otp, $companyName) {
                $message->to($email)
                    ->subject("{$otp} is your Bizpaddi verification code")
                    ->html("
                        <div style='font-family: Arial, sans-serif; max-width: 520px; margin: 0 auto; background: #08110e; color: #ffffff; padding: 32px; border-radius: 16px; border: 1px solid #1f3a30;'>
                            <h2 style='margin: 0 0 12px; color: #ffffff; font-size: 20px;'>Verify Your Email Address</h2>
                            <p style='color: #b0c4bc; font-size: 14px; line-height: 1.6;'>
                                Use the 6-digit verification code below to complete creating your Bizpaddi workspace for <strong style='color: #ffffff;'>" . e($companyName) . "</strong>:
                            </p>
                            <div style='margin: 24px 0; padding: 18px; background: #10231C; border: 1px solid #0E5E4A; border-radius: 12px; text-align: center;'>
                                <span style='font-size: 32px; font-weight: 800; letter-spacing: 8px; color: #F2A900; font-family: monospace;'>{$otp}</span>
                            </div>
                            <p style='color: #8fa69d; font-size: 12px; line-height: 1.5; margin-bottom: 0;'>
                                This code expires in 15 minutes. If you did not request this code, you can safely ignore this email.
                            </p>
                        </div>
                    ");
            });
        } catch (\Throwable $e) {
            Log::error('Registration OTP email failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Email delivery error: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => "We sent a 6-digit verification code to {$email}.",
        ]);
    }

    /**
     * Step 2: Verify the 6-digit OTP code before advancing to Step 3 (Basic Info).
     */
    public function verifyOtp(Request $request)
    {
        $email = strtolower(trim((string) $request->input('email')));
        $submittedOtp = trim((string) $request->input('otp_code'));

        if (empty($email) || strlen($submittedOtp) !== 6) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter the 6-digit verification code sent to your email.',
            ], 422);
        }

        $cacheKey = 'bizpaddi_reg_otp_' . hash('sha256', $email);
        $cachedOtp = Cache::get($cacheKey);
        $sessionData = $request->session()->get($cacheKey);
        $sessionOtp = (is_array($sessionData) && ($sessionData['expires_at'] ?? 0) >= now()->timestamp)
            ? ($sessionData['otp'] ?? null)
            : null;

        $expectedOtp = $cachedOtp ?: $sessionOtp;

        if (!$expectedOtp || !hash_equals((string) $expectedOtp, $submittedOtp)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired verification code. Please check the code or request a new one.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully.',
        ]);
    }

    /**
     * Register a new business tenant and administrator after verifying OTP.
     */
    public function register(Request $request)
    {
        $request->validate([
            'company_name'       => ['required', 'string', 'max:120'],
            'first_name'         => ['required', 'string', 'max:60'],
            'last_name'          => ['required', 'string', 'max:60'],
            'email'              => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:admins,email',
                function ($attribute, $value, $fail) {
                    $error = $this->validateEmailDomain((string) $value);
                    if ($error) {
                        $fail($error);
                    }
                },
            ],
            'phone_country_code' => ['nullable', 'string', 'max:10'],
            'phone'              => ['nullable', 'string', 'max:25'],
            'password'           => ['required', 'string', 'min:8', 'confirmed'],
            'otp_code'           => ['required', 'string', 'size:6'],
            'agree_terms'        => ['required', 'accepted'],
        ], [
            'email.unique'         => 'An account with this email address already exists. Please sign in.',
            'password.confirmed'   => 'The password confirmation does not match.',
            'otp_code.required'    => 'Please enter the 6-digit OTP verification code sent to your email.',
            'otp_code.size'        => 'The OTP verification code must be 6 digits.',
            'agree_terms.accepted' => 'You must accept the Terms of Service to create a workspace.',
        ]);

        $email = strtolower(trim($request->email));
        $cacheKey = 'bizpaddi_reg_otp_' . hash('sha256', $email);
        $cachedOtp = Cache::get($cacheKey);
        $sessionData = $request->session()->get($cacheKey);
        $sessionOtp = (is_array($sessionData) && ($sessionData['expires_at'] ?? 0) >= now()->timestamp)
            ? ($sessionData['otp'] ?? null)
            : null;

        $expectedOtp = $cachedOtp ?: $sessionOtp;
        $submittedOtp = trim((string) $request->input('otp_code'));

        if (!$expectedOtp || !hash_equals((string) $expectedOtp, $submittedOtp)) {
            return back()->withInput()->withErrors([
                'otp_code' => 'Invalid or expired OTP verification code. Please request a new code and try again.',
            ]);
        }

        // Clear OTP after successful verification
        Cache::forget($cacheKey);
        $request->session()->forget($cacheKey);

        $requestedPlanCode = $request->input('plan') ?? $request->query('plan');
        $plan = $requestedPlanCode
            ? SaasPlan::where('code', $requestedPlanCode)->first()
            : null;

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

            $rawPhone = trim((string) $request->phone);
            $dialCode = trim((string) ($request->phone_country_code ?: '+234'));
            $formattedPhone = $rawPhone !== ''
                ? (str_starts_with($rawPhone, '+') ? $rawPhone : $dialCode . ' ' . ltrim($rawPhone, '0'))
                : null;

            // 1. Create Tenant Business (defaults to Pay-As-You-Transact free onboarding)
            $tenant = Tenant::create([
                'name' => trim($request->company_name),
                'slug' => $cleanSlug,
                'email' => $email,
                'phone' => $formattedPhone,
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
                    'phone' => $formattedPhone,
                    'currency' => 'NGN',
                    'email_verified_at' => now()->toIso8601String(),
                    'selected_plan' => $plan?->code ?? 'pay_as_you_go',
                ],
            ]);

            // 2. Create Tenant Admin
            $admin = Admin::create([
                'tenant_id' => $tenant->id,
                'name' => $fullName,
                'email' => $email,
                'password' => Hash::make($request->password),
                'role' => 'admin',
                'is_active' => true,
            ]);

            DB::commit();

            // 3. Authenticate newly created Admin
            Auth::guard('admin')->login($admin);
            $request->session()->put('current_tenant_id', $tenant->id);
            $request->session()->regenerate();

            if ($plan && !$isPayg) {
                return redirect()->route('admin.billing.index')
                    ->with('success', "🎉 Welcome to Bizpaddi! Activate your selected {$plan->name} plan below or top up your operating wallet.");
            }

            return redirect()->route('admin.dashboard')
                ->with('success', "🎉 Welcome to Bizpaddi! Your workspace for {$tenant->name} has been verified and provisioned.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors([
                'error' => 'Unable to complete workspace creation: ' . $e->getMessage(),
            ]);
        }
    }
}
