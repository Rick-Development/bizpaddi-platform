<!DOCTYPE html>
<html lang="en" class="h-full dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Your Business Workspace - Bizpaddi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/bizpaddi/icon.png') }}" type="image/png">
    <style>
        * { font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif; }
        [x-cloak] { display: none !important; }
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 1000px #0a1612 inset !important;
            -webkit-text-fill-color: #ffffff !important;
            caret-color: #ffffff !important;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>
</head>
<body class="min-h-full bg-gradient-to-br from-[#08110e] via-[#0c1a15] to-[#10231C] text-white flex flex-col justify-between selection:bg-emerald-500 selection:text-white"
      x-data="{
          step: {{ $errors->any() ? (old('otp_code') && !$errors->has('otp_code') && !$errors->has('email') ? 3 : ($errors->has('otp_code') ? 2 : 1)) : 1 }},
          email: @js(old('email', '')),
          otpCode: @js(old('otp_code', '')),
          companyName: @js(old('company_name', '')),
          firstName: @js(old('first_name', '')),
          lastName: @js(old('last_name', '')),
          phoneCode: @js(old('phone_country_code', '+234')),
          phone: @js(old('phone', '')),
          password: '',
          showPassword: false,
          loading: false,
          statusMessage: '',
          errorMessage: '',
          resendCooldown: 0,
          blockedDomains: [
              'example.com', 'example.org', 'example.net', 'example.edu',
              'test.com', 'test.org', 'test.net', 'testing.com',
              'fake.com', 'fakemail.com', 'fakeinbox.com', 'invalid.com',
              'localhost', 'domain.com', 'email.com', 'sample.com', 'demo.com',
              'company.com', 'mycompany.com', 'yoursite.com', 'yourdomain.com',
              'mailinator.com', 'yopmail.com', 'tempmail.com', 'temp-mail.org',
              '10minutemail.com', 'guerrillamail.com', 'sharklasers.com',
              'trashmail.com', 'getnada.com', 'maildrop.cc', 'dispostable.com',
              'throwawaymail.com', 'burnermail.io', 'mohmal.com', 'mailnesia.com'
          ],
          blockedTlds: ['example', 'invalid', 'localhost', 'test', 'local', 'internal', 'fake', 'lan'],
          get emailError() {
              const val = (this.email || '').trim().toLowerCase();
              if (!val) return '';
              if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
                  return 'Please enter a valid email address (e.g. name@gmail.com).';
              }
              const domain = val.split('@')[1];
              const parts = domain.split('.');
              const tld = parts[parts.length - 1];
              if (tld.length < 2 || this.blockedTlds.includes(tld)) {
                  return `The domain extension '.${tld}' is not allowed.`;
              }
              if (this.blockedDomains.includes(domain) || this.blockedDomains.some(d => domain.endsWith('.' + d))) {
                  return `Emails from '@${domain}' are not permitted. Please use your real email address.`;
              }
              return '';
          },
          get isEmailValid() {
              return this.email.trim().length > 3 && this.emailError === '';
          },
          async sendOtp() {
              this.errorMessage = '';
              this.statusMessage = '';
              if (!this.isEmailValid) {
                  this.errorMessage = this.emailError || 'Please enter a valid email address.';
                  return;
              }
              if (this.loading) return;

              this.loading = true;
              try {
                  const res = await fetch('{{ route('admin.register.send-otp') }}', {
                      method: 'POST',
                      headers: {
                          'Content-Type': 'application/json',
                          'Accept': 'application/json',
                          'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                      },
                      body: JSON.stringify({ email: this.email.trim() })
                  });
                  const data = await res.json();
                  if (!res.ok || !data.success) {
                      this.errorMessage = data.message || 'Could not send verification code. Please check your email.';
                  } else {
                      this.step = 2;
                      this.statusMessage = data.message;
                      this.startCooldown(60);
                  }
              } catch (e) {
                  this.errorMessage = 'Network error while sending verification code. Please try again.';
              } finally {
                  this.loading = false;
              }
          },
          async verifyOtpStep() {
              this.errorMessage = '';
              this.statusMessage = '';
              const cleanOtp = (this.otpCode || '').trim();
              if (cleanOtp.length !== 6) {
                  this.errorMessage = 'Please enter the 6-digit verification code sent to your email.';
                  return;
              }
              if (this.loading) return;

              this.loading = true;
              try {
                  const res = await fetch('{{ route('admin.register.verify-otp') }}', {
                      method: 'POST',
                      headers: {
                          'Content-Type': 'application/json',
                          'Accept': 'application/json',
                          'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                      },
                      body: JSON.stringify({
                          email: this.email.trim(),
                          otp_code: cleanOtp
                      })
                  });
                  const data = await res.json();
                  if (!res.ok || !data.success) {
                      this.errorMessage = data.message || 'Invalid or expired verification code.';
                  } else {
                      this.step = 3;
                  }
              } catch (e) {
                  this.errorMessage = 'Unable to verify code right now. Please try again.';
              } finally {
                  this.loading = false;
              }
          },
          startCooldown(seconds) {
              this.resendCooldown = seconds;
              const timer = setInterval(() => {
                  if (this.resendCooldown > 0) {
                      this.resendCooldown--;
                  } else {
                      clearInterval(timer);
                  }
              }, 1000);
          }
      }">

    <!-- Top Navigation Bar -->
    <header class="py-5 px-6 sm:px-12 border-b border-white/10 bg-[#08110e]/80 backdrop-blur-md sticky top-0 z-30 flex items-center justify-between">
        <a href="https://{{ config('app.domain', 'bizpaddi.com') }}" class="flex items-center space-x-3">
            <img src="{{ asset('images/bizpaddi/logo.png') }}" alt="Bizpaddi" class="h-8 w-auto">
        </a>
        <div class="flex items-center space-x-3 text-xs sm:text-sm">
            <span class="text-gray-400 hidden sm:inline">Already registered?</span>
            <a href="{{ route('admin.login') }}" class="px-4 py-2 rounded-xl font-bold bg-white/5 hover:bg-white/10 text-[#F2A900] border border-white/10 transition">
                Sign In &rarr;
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 flex items-center justify-center py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-6">

            <!-- Title -->
            <div class="text-center space-y-2">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-[#0E5E4A] shadow-xl border border-emerald-400/30 mb-1">
                    <img src="{{ asset('images/bizpaddi/icon.png') }}" alt="Bizpaddi" class="w-8 h-8 object-contain">
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight"
                    x-text="step === 1 ? 'Get Started with Bizpaddi' : (step === 2 ? 'Verify Your Email' : 'Complete Your Workspace')">
                    Get Started with Bizpaddi
                </h1>
                <p class="text-gray-400 text-xs sm:text-sm"
                   x-text="step === 1 ? 'Step 1 of 3 — Enter your email address to receive a verification code' : (step === 2 ? 'Step 2 of 3 — Enter the 6-digit code sent to your inbox' : 'Step 3 of 3 — Set up your basic profile and workspace')">
                </p>
            </div>

            <!-- 3-Step Progress Indicator -->
            <div class="grid grid-cols-3 gap-2">
                <div class="h-1.5 rounded-full transition-all duration-300" :class="step >= 1 ? 'bg-emerald-400' : 'bg-white/10'"></div>
                <div class="h-1.5 rounded-full transition-all duration-300" :class="step >= 2 ? 'bg-emerald-400' : 'bg-white/10'"></div>
                <div class="h-1.5 rounded-full transition-all duration-300" :class="step >= 3 ? 'bg-[#F2A900]' : 'bg-white/10'"></div>
            </div>

            <!-- Card -->
            <div class="bg-[#10231C]/95 backdrop-blur-xl rounded-3xl shadow-2xl p-7 sm:p-9 border border-white/10 text-white">

                @if ($errors->any())
                <div class="mb-5 bg-red-950/80 border border-red-500/40 p-4 rounded-xl text-xs text-red-200 space-y-1">
                    @foreach ($errors->all() as $error)
                        <p class="font-medium">• {{ $error }}</p>
                    @endforeach
                </div>
                @endif

                <div x-show="errorMessage" x-cloak class="mb-5 bg-red-950/80 border border-red-500/40 p-3.5 rounded-xl text-xs text-red-200 font-medium" x-text="errorMessage"></div>

                <!-- ==================================================== -->
                <!-- STEP 1: ENTER EMAIL ADDRESS                          -->
                <!-- ==================================================== -->
                <div x-show="step === 1" class="space-y-5">
                    <div class="space-y-2">
                        <label for="step1_email" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                            Email Address
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                            </div>
                            <input id="step1_email" type="email" required autofocus
                                x-model="email"
                                @keydown.enter.prevent="sendOtp()"
                                @input="errorMessage = ''"
                                placeholder="you@gmail.com or name@domain.com"
                                :class="emailError ? 'border-red-500 focus:border-red-500' : 'border-white/15 focus:border-emerald-500'"
                                class="w-full pl-11 pr-4 py-3.5 rounded-xl bg-[#0a1612] border text-sm text-white placeholder-gray-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
                        </div>
                        <p x-show="emailError" x-cloak class="text-xs text-red-400 font-medium pt-0.5" x-text="emailError"></p>
                        <p x-show="!emailError" class="text-[11px] text-gray-400">
                            Personal or business email accepted. We'll send a 6-digit code to verify it.
                        </p>
                    </div>

                    <button type="button"
                        @click="sendOtp()"
                        :disabled="!isEmailValid || loading"
                        class="w-full py-3.5 px-4 rounded-xl text-sm font-extrabold bg-[#0E5E4A] hover:bg-[#0c4e3e] disabled:opacity-40 disabled:cursor-not-allowed text-white shadow-lg shadow-[#0E5E4A]/30 transition duration-200 flex items-center justify-center space-x-2 border border-emerald-400/30">
                        <span x-show="!loading">Continue & Send Verification Code</span>
                        <span x-show="loading" x-cloak>Sending Verification Code...</span>
                        <svg x-show="!loading" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>

                <!-- ==================================================== -->
                <!-- STEP 2: 6-DIGIT EMAIL OTP VERIFICATION               -->
                <!-- ==================================================== -->
                <div x-show="step === 2" x-cloak class="space-y-5">
                    <div class="p-3.5 rounded-xl bg-[#0a1612] border border-white/10 flex items-center justify-between text-xs">
                        <div class="truncate pr-2">
                            <span class="text-gray-400 block text-[10px] uppercase">Code sent to</span>
                            <span class="font-bold text-emerald-300 truncate block" x-text="email"></span>
                        </div>
                        <button type="button" @click="step = 1; errorMessage = ''; statusMessage = '';"
                            class="text-[#F2A900] hover:underline font-bold shrink-0">
                            Change
                        </button>
                    </div>

                    <div class="space-y-2">
                        <label for="step2_otp" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                            6-Digit Verification Code
                        </label>
                        <input id="step2_otp" type="text" inputmode="numeric" maxlength="6"
                            x-model="otpCode"
                            @keydown.enter.prevent="verifyOtpStep()"
                            @input="otpCode = otpCode.replace(/[^0-9]/g, '').slice(0, 6); errorMessage = ''"
                            placeholder="• • • • • •"
                            class="w-full px-4 py-3.5 rounded-xl bg-[#0a1612] border border-white/20 text-center font-mono text-2xl font-extrabold tracking-[0.5em] text-[#F2A900] placeholder-gray-600 focus:border-[#F2A900] focus:ring-2 focus:ring-[#F2A900]/20 outline-none transition">
                        <div class="flex items-center justify-between text-[11px] pt-1">
                            <span class="text-gray-400">Didn't receive the email?</span>
                            <button type="button"
                                @click="sendOtp()"
                                :disabled="loading || resendCooldown > 0"
                                class="font-bold text-[#F2A900] hover:underline disabled:opacity-40 disabled:no-underline">
                                <span x-show="resendCooldown === 0">Resend Code</span>
                                <span x-show="resendCooldown > 0" x-text="`Resend in ${resendCooldown}s`"></span>
                            </button>
                        </div>
                    </div>

                    <button type="button"
                        @click="verifyOtpStep()"
                        :disabled="otpCode.length !== 6 || loading"
                        class="w-full py-3.5 px-4 rounded-xl text-sm font-extrabold bg-[#0E5E4A] hover:bg-[#0c4e3e] disabled:opacity-40 disabled:cursor-not-allowed text-white shadow-lg shadow-[#0E5E4A]/30 transition duration-200 flex items-center justify-center space-x-2 border border-emerald-400/30">
                        <span x-show="!loading">Verify Email Code</span>
                        <span x-show="loading" x-cloak>Verifying...</span>
                        <svg x-show="!loading" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>

                <!-- ==================================================== -->
                <!-- STEP 3: BASIC DETAILS & WORKSPACE CREATION           -->
                <!-- ==================================================== -->
                <form x-show="step === 3" x-cloak action="{{ route('admin.register') }}" method="POST" class="space-y-4">
                    @csrf

                    <input type="hidden" name="email" :value="email">
                    <input type="hidden" name="otp_code" :value="otpCode">
                    @if(request('plan') || old('plan'))
                        <input type="hidden" name="plan" value="{{ request('plan', old('plan')) }}">
                    @endif

                    <!-- Verified Email Badge -->
                    <div class="p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-between text-xs">
                        <div class="flex items-center space-x-2 truncate">
                            <span class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold shrink-0">✓</span>
                            <span class="text-emerald-200 font-semibold truncate" x-text="email"></span>
                        </div>
                        <span class="text-[10px] uppercase tracking-wider font-extrabold text-emerald-400 shrink-0 ml-2">Verified</span>
                    </div>

                    <!-- Business / Workspace Name -->
                    <div class="space-y-1.5">
                        <label for="company_name" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                            Business / Workspace Name
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <input id="company_name" name="company_name" type="text" :required="step === 3"
                                x-model="companyName"
                                placeholder="e.g. Apex Ventures"
                                class="w-full pl-10 pr-4 py-3 rounded-xl bg-[#0a1612] border border-white/15 text-sm text-white placeholder-gray-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
                        </div>
                    </div>

                    <!-- First Name & Last Name -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="space-y-1.5">
                            <label for="first_name" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                                First Name
                            </label>
                            <input id="first_name" name="first_name" type="text" :required="step === 3"
                                x-model="firstName"
                                placeholder="e.g. Chinedu"
                                class="w-full px-3.5 py-3 rounded-xl bg-[#0a1612] border border-white/15 text-sm text-white placeholder-gray-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
                        </div>
                        <div class="space-y-1.5">
                            <label for="last_name" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                                Last Name
                            </label>
                            <input id="last_name" name="last_name" type="text" :required="step === 3"
                                x-model="lastName"
                                placeholder="e.g. Okafor"
                                class="w-full px-3.5 py-3 rounded-xl bg-[#0a1612] border border-white/15 text-sm text-white placeholder-gray-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
                        </div>
                    </div>

                    <!-- Phone Number -->
                    <div class="space-y-1.5">
                        <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                            Phone Number
                        </label>
                        <div class="flex rounded-xl overflow-hidden border border-white/15 bg-[#0a1612] focus-within:border-emerald-500 focus-within:ring-2 focus-within:ring-emerald-500/20 transition">
                            <select name="phone_country_code" x-model="phoneCode"
                                style="background-image: none;"
                                class="pl-3.5 pr-3 py-3 bg-white/5 text-xs font-bold text-gray-200 border-0 border-r border-white/10 outline-none focus:ring-0 cursor-pointer">
                                <option value="+234" class="bg-[#0a1612] text-white">🇳🇬 +234</option>
                                <option value="+233" class="bg-[#0a1612] text-white">🇬🇭 +233</option>
                                <option value="+254" class="bg-[#0a1612] text-white">🇰🇪 +254</option>
                                <option value="+27" class="bg-[#0a1612] text-white">🇿🇦 +27</option>
                                <option value="+44" class="bg-[#0a1612] text-white">🇬🇧 +44</option>
                                <option value="+1" class="bg-[#0a1612] text-white">🇺🇸 +1</option>
                            </select>
                            <input id="phone" name="phone" type="tel" :required="step === 3"
                                x-model="phone"
                                placeholder="803 123 4567"
                                class="flex-1 px-4 py-3 bg-transparent border-0 text-sm text-white placeholder-gray-500 outline-none focus:ring-0">
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="space-y-1.5">
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                            Create Password
                        </label>
                        <div class="relative">
                            <input id="password" name="password" :type="showPassword ? 'text' : 'password'" :required="step === 3"
                                x-model="password"
                                placeholder="Min 8 characters"
                                class="w-full pl-4 pr-11 py-3 rounded-xl bg-[#0a1612] border border-white/15 text-sm text-white placeholder-gray-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-white">
                                <svg x-show="!showPassword" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showPassword" x-cloak class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Terms Checkbox -->
                    <div class="pt-1">
                        <label class="flex items-start space-x-2.5 text-xs text-gray-300 cursor-pointer">
                            <input type="checkbox" name="agree_terms" value="1" :required="step === 3"
                                {{ old('agree_terms') ? 'checked' : '' }}
                                class="mt-0.5 w-4 h-4 rounded border-white/20 bg-[#0a1612] text-[#0E5E4A] focus:ring-[#0E5E4A]">
                            <span class="leading-relaxed">
                                I agree to the
                                <a href="https://{{ config('app.domain', 'bizpaddi.com') }}/terms-conditions" target="_blank" class="text-[#F2A900] hover:underline font-semibold">Terms of Service</a>
                                and
                                <a href="https://{{ config('app.domain', 'bizpaddi.com') }}/privacy-policy" target="_blank" class="text-[#F2A900] hover:underline font-semibold">Privacy Policy</a>.
                            </span>
                        </label>
                    </div>

                    <!-- Launch Button -->
                    <button type="submit"
                        class="w-full py-3.5 px-4 rounded-xl text-sm font-extrabold bg-[#0E5E4A] hover:bg-[#0c4e3e] text-white shadow-lg shadow-[#0E5E4A]/30 transition duration-200 flex items-center justify-center space-x-2 border border-emerald-400/30 group">
                        <span>Launch Workspace</span>
                        <svg class="h-4 w-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>

                <!-- Footer Sign In Link -->
                <div class="mt-6 pt-5 border-t border-white/10 text-center text-xs text-gray-400">
                    Already have a business workspace?
                    <a href="{{ route('admin.login') }}" class="font-bold text-[#F2A900] hover:underline ml-1">Sign in here</a>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="py-6 text-center text-xs text-gray-500 border-t border-white/10">
        &copy; {{ date('Y') }} Bizpaddi Technologies Ltd. All rights reserved.
    </footer>

</body>
</html>
