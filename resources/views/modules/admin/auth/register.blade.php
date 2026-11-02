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
          companyName: @js(old('company_name', '')),
          firstName: @js(old('first_name', '')),
          lastName: @js(old('last_name', '')),
          email: @js(old('email', '')),
          phoneCode: @js(old('phone_country_code', '+234')),
          phone: @js(old('phone', '')),
          password: '',
          showPassword: false,
          otpSent: {{ $errors->has('otp_code') || old('otp_code') ? 'true' : 'false' }},
          otpCode: @js(old('otp_code', '')),
          sendingOtp: false,
          otpStatusMessage: '',
          otpErrorMessage: '',
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
              this.otpErrorMessage = '';
              this.otpStatusMessage = '';
              if (!this.isEmailValid) {
                  this.otpErrorMessage = this.emailError || 'Please enter a valid email address first.';
                  return;
              }
              if (this.sendingOtp || this.resendCooldown > 0) return;

              this.sendingOtp = true;
              try {
                  const res = await fetch('{{ route('admin.register.send-otp') }}', {
                      method: 'POST',
                      headers: {
                          'Content-Type': 'application/json',
                          'Accept': 'application/json',
                          'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                      },
                      body: JSON.stringify({
                          email: this.email.trim(),
                          company_name: this.companyName.trim()
                      })
                  });
                  const data = await res.json();
                  if (!res.ok || !data.success) {
                      this.otpErrorMessage = data.message || 'Could not send verification code. Please check your email.';
                  } else {
                      this.otpSent = true;
                      this.otpStatusMessage = data.message;
                      this.startCooldown(60);
                  }
              } catch (e) {
                  this.otpErrorMessage = 'Network error while sending verification code. Please try again.';
              } finally {
                  this.sendingOtp = false;
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
        <div class="max-w-lg w-full space-y-6">

            <!-- Title -->
            <div class="text-center space-y-2">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-[#0E5E4A] shadow-xl border border-emerald-400/30 mb-1">
                    <img src="{{ asset('images/bizpaddi/icon.png') }}" alt="Bizpaddi" class="w-8 h-8 object-contain">
                </div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight">
                    Create Your Workspace
                </h1>
                <p class="text-gray-400 text-xs sm:text-sm">
                    Enter your basic details and verify your email to get started in seconds
                </p>
            </div>

            <!-- Registration Card -->
            <div class="bg-[#10231C]/95 backdrop-blur-xl rounded-3xl shadow-2xl p-7 sm:p-9 border border-white/10 text-white">
                <form action="{{ route('admin.register') }}" method="POST" class="space-y-4"
                      @submit="if (!otpSent) { $event.preventDefault(); sendOtp(); }">
                    @csrf

                    @if(request('plan') || old('plan'))
                        <input type="hidden" name="plan" value="{{ request('plan', old('plan')) }}">
                    @endif

                    @if ($errors->any())
                    <div class="bg-red-950/80 border border-red-500/40 p-4 rounded-xl text-xs text-red-200 space-y-1">
                        @foreach ($errors->all() as $error)
                            <p class="font-medium">• {{ $error }}</p>
                        @endforeach
                    </div>
                    @endif

                    <!-- Business / Company Name -->
                    <div class="space-y-1.5">
                        <label for="company_name" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                            Business / Workspace Name
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <input id="company_name" name="company_name" type="text" required
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
                            <input id="first_name" name="first_name" type="text" required
                                x-model="firstName"
                                placeholder="e.g. Chinedu"
                                class="w-full px-3.5 py-3 rounded-xl bg-[#0a1612] border border-white/15 text-sm text-white placeholder-gray-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
                        </div>
                        <div class="space-y-1.5">
                            <label for="last_name" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                                Last Name
                            </label>
                            <input id="last_name" name="last_name" type="text" required
                                x-model="lastName"
                                placeholder="e.g. Okafor"
                                class="w-full px-3.5 py-3 rounded-xl bg-[#0a1612] border border-white/15 text-sm text-white placeholder-gray-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
                        </div>
                    </div>

                    <!-- Email Address + Send OTP Button -->
                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                            Email Address
                        </label>
                        <div class="flex gap-2">
                            <div class="relative flex-1">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                                </div>
                                <input id="email" name="email" type="email" required
                                    x-model="email"
                                    @input="otpErrorMessage = ''"
                                    placeholder="you@gmail.com or name@domain.com"
                                    :class="emailError ? 'border-red-500 focus:border-red-500' : 'border-white/15 focus:border-emerald-500'"
                                    class="w-full pl-10 pr-4 py-3 rounded-xl bg-[#0a1612] border text-sm text-white placeholder-gray-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
                            </div>
                            <button type="button"
                                @click="sendOtp()"
                                :disabled="!isEmailValid || sendingOtp || resendCooldown > 0"
                                class="px-4 py-3 rounded-xl text-xs font-extrabold whitespace-nowrap transition border border-emerald-400/30 disabled:opacity-40 disabled:cursor-not-allowed bg-[#0E5E4A] hover:bg-[#0c4e3e] text-white">
                                <span x-show="!sendingOtp && resendCooldown === 0" x-text="otpSent ? 'Resend OTP' : 'Send OTP'">Send OTP</span>
                                <span x-show="sendingOtp" x-cloak>Sending...</span>
                                <span x-show="!sendingOtp && resendCooldown > 0" x-cloak x-text="`Resend (${resendCooldown}s)`"></span>
                            </button>
                        </div>

                        <!-- Real-time domain validation error -->
                        <p x-show="emailError" x-cloak class="text-xs text-red-400 font-medium pt-0.5" x-text="emailError"></p>
                        <p x-show="otpErrorMessage" x-cloak class="text-xs text-red-400 font-medium pt-0.5" x-text="otpErrorMessage"></p>
                        <p x-show="otpStatusMessage && !otpErrorMessage" x-cloak class="text-xs text-emerald-400 font-medium pt-0.5" x-text="otpStatusMessage"></p>
                    </div>

                    <!-- 6-Digit Email OTP Verification Box -->
                    <div x-show="otpSent" x-cloak class="p-4 rounded-2xl bg-[#0a1612] border border-[#F2A900]/40 space-y-2">
                        <div class="flex items-center justify-between">
                            <label for="otp_code" class="block text-xs font-extrabold uppercase tracking-wider text-[#F2A900]">
                                Enter 6-Digit Email Verification Code
                            </label>
                            <span class="text-[11px] text-emerald-400 font-semibold">Sent to your email</span>
                        </div>
                        <input id="otp_code" name="otp_code" type="text" inputmode="numeric" maxlength="6" pattern="[0-9]{6}"
                            :required="otpSent"
                            x-model="otpCode"
                            placeholder="• • • • • •"
                            class="w-full px-4 py-3 rounded-xl bg-[#10231C] border border-white/20 text-center font-mono text-lg font-extrabold tracking-[0.5em] text-[#F2A900] placeholder-gray-600 focus:border-[#F2A900] focus:ring-2 focus:ring-[#F2A900]/20 outline-none transition">
                        <p class="text-[11px] text-gray-400">
                            Check your inbox or spam folder for the 6-digit code.
                        </p>
                    </div>

                    <!-- Phone Number with Country Dialing Prefix -->
                    <div class="space-y-1.5">
                        <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                            Phone Number
                        </label>
                        <div class="flex rounded-xl overflow-hidden border border-white/15 bg-[#0a1612] focus-within:border-emerald-500 focus-within:ring-2 focus-within:ring-emerald-500/20 transition">
                            <select name="phone_country_code" x-model="phoneCode"
                                class="px-3 py-3 bg-white/5 text-xs font-bold text-gray-200 border-r border-white/10 outline-none">
                                <option value="+234">🇳🇬 +234</option>
                                <option value="+233">🇬🇭 +233</option>
                                <option value="+254">🇰🇪 +254</option>
                                <option value="+27">🇿🇦 +27</option>
                                <option value="+44">🇬🇧 +44</option>
                                <option value="+1">🇺🇸 +1</option>
                            </select>
                            <input id="phone" name="phone" type="tel" required
                                x-model="phone"
                                placeholder="803 123 4567"
                                class="flex-1 px-4 py-3 bg-transparent text-sm text-white placeholder-gray-500 outline-none">
                        </div>
                    </div>

                    <!-- Password with Show/Hide Toggle -->
                    <div class="space-y-1.5">
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                            Password
                        </label>
                        <div class="relative">
                            <input id="password" name="password" :type="showPassword ? 'text' : 'password'" required
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
                            <input type="checkbox" name="agree_terms" value="1" required
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

                    <!-- Submit / Verify Button -->
                    <button type="submit"
                        :disabled="!!emailError || sendingOtp"
                        class="w-full py-3.5 px-4 rounded-xl text-sm font-extrabold bg-[#0E5E4A] hover:bg-[#0c4e3e] disabled:opacity-50 text-white shadow-lg shadow-[#0E5E4A]/30 transition duration-200 flex items-center justify-center space-x-2 border border-emerald-400/30 group">
                        <span x-text="otpSent ? 'Verify OTP & Launch Workspace' : 'Send Email OTP to Continue'"></span>
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
