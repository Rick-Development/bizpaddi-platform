<!DOCTYPE html>
<html lang="en" class="h-full dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
          customSlug: @js(old('workspace_slug', '')),
          slugEdited: {{ old('workspace_slug') ? 'true' : 'false' }},
          industry: @js(old('industry', 'E-Commerce & Retail')),
          companySize: @js(old('company_size', '2-10')),
          country: @js(old('country', 'NG')),
          currency: @js(old('currency', 'NGN')),
          phoneCode: @js(old('phone_country_code', '+234')),
          selectedPlan: @js(request('plan', old('plan', 'pay_as_you_go'))),
          password: '',
          showPassword: false,
          showConfirmPassword: false,
          get generatedSlug() {
              if (this.slugEdited && this.customSlug) {
                  return this.customSlug.toLowerCase().replace(/[^a-z0-9-]+/g, '-').replace(/^-|-$/g, '');
              }
              if (!this.companyName) return 'your-company';
              return this.companyName.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '') || 'your-company';
          },
          get passwordScore() {
              let score = 0;
              if (this.password.length >= 8) score++;
              if (/[A-Z]/.test(this.password) && /[a-z]/.test(this.password)) score++;
              if (/[0-9]/.test(this.password)) score++;
              if (/[^A-Za-z0-9]/.test(this.password) || this.password.length >= 12) score++;
              return score;
          },
          get passwordStrengthText() {
              if (!this.password) return 'Min. 8 characters';
              return ['Too Weak', 'Weak', 'Fair', 'Good', 'Strong'][this.passwordScore];
          },
          updateCountryDefaults() {
              const map = {
                  'NG': { currency: 'NGN', dial: '+234' },
                  'GH': { currency: 'GHS', dial: '+233' },
                  'KE': { currency: 'KES', dial: '+254' },
                  'ZA': { currency: 'ZAR', dial: '+27' },
                  'GB': { currency: 'GBP', dial: '+44' },
                  'US': { currency: 'USD', dial: '+1' }
              };
              if (map[this.country]) {
                  this.currency = map[this.country].currency;
                  this.phoneCode = map[this.country].dial;
              }
          }
      }">

    <!-- Top Navigation Bar -->
    <header class="py-5 px-6 sm:px-12 border-b border-white/10 bg-[#08110e]/80 backdrop-blur-md sticky top-0 z-30 flex items-center justify-between">
        <a href="https://{{ config('app.domain', 'bizpaddi.com') }}" class="flex items-center space-x-3">
            <img src="{{ asset('images/bizpaddi/logo.png') }}" alt="Bizpaddi" class="h-8 w-auto">
            <span class="hidden md:inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-widest bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                Workspace Onboarding
            </span>
        </a>
        <div class="flex items-center space-x-4 text-xs sm:text-sm">
            <span class="text-gray-400 hidden sm:inline">Already have a workspace?</span>
            <a href="{{ route('admin.login') }}" class="px-4 py-2 rounded-xl font-bold bg-white/5 hover:bg-white/10 text-[#F2A900] border border-white/10 transition flex items-center space-x-1.5">
                <span>Sign In</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </header>

    <!-- Main 2-Column Enterprise Onboarding Layout -->
    <main class="flex-1 py-10 px-4 sm:px-6 lg:px-12">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">

            <!-- LEFT COLUMN: Live Workspace Preview & Enterprise Value Proposition -->
            <div class="lg:col-span-5 space-y-6 lg:sticky lg:top-28">
                <div class="space-y-3">
                    <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-[#0E5E4A]/30 border border-emerald-400/30 text-emerald-300 text-xs font-bold">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>Instant Multi-Tenant Provisioning</span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight">
                        Launch Your Unified Business Operating System.
                    </h1>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Configure your company workspace, digital storefront, invoicing engine, and team portal in under 60 seconds.
                    </p>
                </div>

                <!-- Live Interactive Workspace Preview Card -->
                <div class="rounded-3xl bg-gradient-to-br from-[#10231C] to-[#0a1612] border border-emerald-500/30 p-6 shadow-2xl relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 w-36 h-36 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="flex items-center justify-between pb-4 border-b border-white/10">
                        <div class="flex items-center space-x-3">
                            <div class="w-11 h-11 rounded-2xl bg-[#0E5E4A] border border-emerald-400/40 flex items-center justify-center font-extrabold text-lg text-[#F2A900] shadow-inner"
                                 x-text="(companyName ? companyName.substring(0, 2) : 'BP').toUpperCase()">
                                BP
                            </div>
                            <div>
                                <p class="text-[10px] uppercase tracking-widest text-emerald-400 font-bold">Live Workspace Preview</p>
                                <h3 class="text-base font-extrabold text-white truncate max-w-[210px]" x-text="companyName || 'Your Company Name Ltd'">Your Company Name Ltd</h3>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-emerald-500/15 text-emerald-300 border border-emerald-500/30" x-text="currency">NGN</span>
                    </div>

                    <div class="py-4 space-y-2.5 text-xs">
                        <div class="flex items-center justify-between bg-[#08110e]/80 px-3.5 py-2.5 rounded-xl border border-white/5">
                            <span class="text-gray-400">Storefront URL</span>
                            <span class="font-mono text-emerald-400 font-semibold truncate max-w-[200px]">
                                {{ config('app.domain', 'bizpaddi.com') }}/store/<span x-text="generatedSlug" class="text-[#F2A900]">your-company</span>
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-2.5">
                            <div class="bg-[#08110e]/80 px-3.5 py-2.5 rounded-xl border border-white/5">
                                <span class="block text-[10px] text-gray-500 uppercase">Industry Sector</span>
                                <span class="font-bold text-gray-200 truncate block mt-0.5" x-text="industry">E-Commerce & Retail</span>
                            </div>
                            <div class="bg-[#08110e]/80 px-3.5 py-2.5 rounded-xl border border-white/5">
                                <span class="block text-[10px] text-gray-500 uppercase">Team Capacity</span>
                                <span class="font-bold text-gray-200 block mt-0.5" x-text="companySize + ' Members'">2-10 Members</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-white/10 flex items-center justify-between text-[11px] text-gray-400">
                        <span class="flex items-center space-x-1.5">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            <span>Isolated Tenant Database Scope</span>
                        </span>
                        <span class="text-[#F2A900] font-bold" x-text="selectedPlan === 'pay_as_you_go' ? 'Pay-As-You-Go (₦0/mo)' : selectedPlan.toUpperCase()"></span>
                    </div>
                </div>

                <!-- Included Modules Checklist -->
                <div class="rounded-3xl bg-[#10231C]/60 border border-white/10 p-6 space-y-4">
                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-gray-300">Everything Provisioned in Your Workspace</h4>
                    <div class="space-y-3 text-xs">
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 rounded-lg bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center text-emerald-400 shrink-0 mt-0.5">✓</div>
                            <div>
                                <p class="font-bold text-white">Smart Invoicing, Quotes & Digital Contracts</p>
                                <p class="text-gray-400 mt-0.5">Automated PDF invoices, multi-currency billing, and client e-signatures.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 rounded-lg bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center text-emerald-400 shrink-0 mt-0.5">✓</div>
                            <div>
                                <p class="font-bold text-white">Digital Storefront & Instant Virtual Accounts</p>
                                <p class="text-gray-400 mt-0.5">Publish your catalog and collect instant bank transfer settlements via Billway.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 rounded-lg bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center text-emerald-400 shrink-0 mt-0.5">✓</div>
                            <div>
                                <p class="font-bold text-white">Staff Attendance, Project Kanban & Payroll</p>
                                <p class="text-gray-400 mt-0.5">Dedicated `/dashboard/team` portal for staff clock-in, daily reports, and KPIs.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 rounded-lg bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center text-emerald-400 shrink-0 mt-0.5">✓</div>
                            <div>
                                <p class="font-bold text-white">Client Self-Service Portal & Support Desk</p>
                                <p class="text-gray-400 mt-0.5">Dedicated `/dashboard/client` portal for project approvals, tickets, and receipts.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Multi-Section Business Workspace Registration Form -->
            <div class="lg:col-span-7">
                <div class="bg-[#10231C]/95 backdrop-blur-xl rounded-3xl shadow-2xl p-6 sm:p-10 border border-white/10 text-white">
                    <form action="{{ route('admin.register') }}" method="POST" class="space-y-7">
                        @csrf

                        @if ($errors->any())
                        <div class="bg-red-950/80 border border-red-500/40 p-4 rounded-2xl text-xs text-red-200 space-y-1">
                            <p class="font-bold text-red-300 mb-1">Please fix the following to continue:</p>
                            @foreach ($errors->all() as $error)
                                <p class="font-medium">• {{ $error }}</p>
                            @endforeach
                        </div>
                        @endif

                        <!-- SECTION 1: Business & Workspace Profile -->
                        <div class="space-y-4">
                            <div class="flex items-center space-x-2.5 pb-2 border-b border-white/10">
                                <span class="w-6 h-6 rounded-full bg-[#0E5E4A] text-white text-xs font-extrabold flex items-center justify-center border border-emerald-400/40">1</span>
                                <h2 class="text-sm font-extrabold uppercase tracking-wider text-emerald-300">Business & Workspace Profile</h2>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Company Name -->
                                <div class="sm:col-span-2 space-y-1.5">
                                    <label for="company_name" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                                        Registered Company / Business Name <span class="text-amber-400">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        </div>
                                        <input id="company_name" name="company_name" type="text" required
                                            x-model="companyName"
                                            placeholder="e.g. Acme Innovations Ltd"
                                            class="w-full pl-10 pr-4 py-3 rounded-xl bg-[#0a1612] border border-white/15 text-sm text-white placeholder-gray-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
                                    </div>
                                </div>

                                <!-- Custom Workspace / Storefront Handle with Prefix -->
                                <div class="sm:col-span-2 space-y-1.5">
                                    <label for="workspace_slug" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                                        Storefront & Workspace URL Handle
                                    </label>
                                    <div class="flex rounded-xl overflow-hidden border border-white/15 bg-[#0a1612] focus-within:border-emerald-500 focus-within:ring-2 focus-within:ring-emerald-500/20 transition">
                                        <span class="inline-flex items-center px-3.5 bg-white/5 text-gray-400 text-xs font-mono border-r border-white/10 select-none">
                                            {{ config('app.domain', 'bizpaddi.com') }}/store/
                                        </span>
                                        <input id="workspace_slug" name="workspace_slug" type="text"
                                            :value="slugEdited ? customSlug : generatedSlug"
                                            @input="slugEdited = true; customSlug = $event.target.value"
                                            placeholder="your-company"
                                            class="flex-1 px-3.5 py-3 bg-transparent text-sm font-mono text-[#F2A900] placeholder-gray-600 outline-none">
                                    </div>
                                </div>

                                <!-- Industry / Business Sector -->
                                <div class="space-y-1.5">
                                    <label for="industry" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                                        Industry / Business Sector <span class="text-amber-400">*</span>
                                    </label>
                                    <select id="industry" name="industry" x-model="industry"
                                        class="w-full px-3.5 py-3 rounded-xl bg-[#0a1612] border border-white/15 text-sm text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
                                        <option value="E-Commerce & Retail">E-Commerce & Retail</option>
                                        <option value="Technology & Software">Technology & Software</option>
                                        <option value="Agency & Consulting">Agency & Consulting</option>
                                        <option value="Fintech & Financial Services">Fintech & Financial Services</option>
                                        <option value="Logistics & Supply Chain">Logistics & Supply Chain</option>
                                        <option value="Real Estate & Construction">Real Estate & Construction</option>
                                        <option value="Healthcare & Medical">Healthcare & Medical</option>
                                        <option value="Hospitality, Food & Events">Hospitality, Food & Events</option>
                                        <option value="Education & Training">Education & Training</option>
                                        <option value="Manufacturing & Distribution">Manufacturing & Distribution</option>
                                        <option value="General Business">Other / General Business</option>
                                    </select>
                                </div>

                                <!-- Company Size -->
                                <div class="space-y-1.5">
                                    <label for="company_size" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                                        Company / Team Size <span class="text-amber-400">*</span>
                                    </label>
                                    <select id="company_size" name="company_size" x-model="companySize"
                                        class="w-full px-3.5 py-3 rounded-xl bg-[#0a1612] border border-white/15 text-sm text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
                                        <option value="1 (Solo)">Solo Founder / Freelancer (1)</option>
                                        <option value="2-10">2 – 10 Employees</option>
                                        <option value="11-50">11 – 50 Employees</option>
                                        <option value="51-200">51 – 200 Employees</option>
                                        <option value="200+">200+ Enterprise</option>
                                    </select>
                                </div>

                                <!-- Country -->
                                <div class="space-y-1.5">
                                    <label for="country" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                                        Operating Country
                                    </label>
                                    <select id="country" name="country" x-model="country" @change="updateCountryDefaults()"
                                        class="w-full px-3.5 py-3 rounded-xl bg-[#0a1612] border border-white/15 text-sm text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
                                        <option value="NG">🇳🇬 Nigeria</option>
                                        <option value="GH">🇬🇭 Ghana</option>
                                        <option value="KE">🇰🇪 Kenya</option>
                                        <option value="ZA">🇿🇦 South Africa</option>
                                        <option value="GB">🇬🇧 United Kingdom</option>
                                        <option value="US">🇺🇸 United States</option>
                                    </select>
                                </div>

                                <!-- Default Currency & Optional RC Number -->
                                <div class="grid grid-cols-2 gap-2.5">
                                    <div class="space-y-1.5">
                                        <label for="currency" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                                            Currency
                                        </label>
                                        <select id="currency" name="currency" x-model="currency"
                                            class="w-full px-3 py-3 rounded-xl bg-[#0a1612] border border-white/15 text-sm text-white focus:border-emerald-500 outline-none transition">
                                            <option value="NGN">NGN (₦)</option>
                                            <option value="USD">USD ($)</option>
                                            <option value="GBP">GBP (£)</option>
                                            <option value="GHS">GHS (₵)</option>
                                            <option value="KES">KES (KSh)</option>
                                            <option value="ZAR">ZAR (R)</option>
                                        </select>
                                    </div>
                                    <div class="space-y-1.5">
                                        <label for="rc_number" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                                            RC / Tax ID <span class="text-[10px] text-gray-500 lowercase font-normal">(opt)</span>
                                        </label>
                                        <input id="rc_number" name="rc_number" type="text" value="{{ old('rc_number') }}"
                                            placeholder="RC-123456"
                                            class="w-full px-3 py-3 rounded-xl bg-[#0a1612] border border-white/15 text-sm text-white placeholder-gray-600 focus:border-emerald-500 outline-none transition">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: Primary Administrator Details -->
                        <div class="space-y-4">
                            <div class="flex items-center space-x-2.5 pb-2 border-b border-white/10">
                                <span class="w-6 h-6 rounded-full bg-[#0E5E4A] text-white text-xs font-extrabold flex items-center justify-center border border-emerald-400/40">2</span>
                                <h2 class="text-sm font-extrabold uppercase tracking-wider text-emerald-300">Primary Administrator Profile</h2>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label for="first_name" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                                        First Name <span class="text-amber-400">*</span>
                                    </label>
                                    <input id="first_name" name="first_name" type="text" required
                                        value="{{ old('first_name') }}"
                                        placeholder="e.g. Chinedu"
                                        class="w-full px-3.5 py-3 rounded-xl bg-[#0a1612] border border-white/15 text-sm text-white placeholder-gray-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
                                </div>
                                <div class="space-y-1.5">
                                    <label for="last_name" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                                        Last Name <span class="text-amber-400">*</span>
                                    </label>
                                    <input id="last_name" name="last_name" type="text" required
                                        value="{{ old('last_name') }}"
                                        placeholder="e.g. Okafor"
                                        class="w-full px-3.5 py-3 rounded-xl bg-[#0a1612] border border-white/15 text-sm text-white placeholder-gray-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
                                </div>

                                <!-- Official Work Email -->
                                <div class="space-y-1.5">
                                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                                        Work Email Address <span class="text-amber-400">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                                        </div>
                                        <input id="email" name="email" type="email" required
                                            value="{{ old('email') }}"
                                            placeholder="founder@company.com"
                                            class="w-full pl-10 pr-4 py-3 rounded-xl bg-[#0a1612] border border-white/15 text-sm text-white placeholder-gray-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
                                    </div>
                                </div>

                                <!-- Job Title / Role -->
                                <div class="space-y-1.5">
                                    <label for="job_title" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                                        Your Role / Job Title
                                    </label>
                                    <select id="job_title" name="job_title"
                                        class="w-full px-3.5 py-3 rounded-xl bg-[#0a1612] border border-white/15 text-sm text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
                                        <option value="Founder / CEO">Founder / CEO</option>
                                        <option value="Managing Director">Managing Director</option>
                                        <option value="Operations / General Manager">Operations / General Manager</option>
                                        <option value="Finance / Accounting Lead">Finance / Accounting Lead</option>
                                        <option value="IT / Technical Administrator">IT / Technical Administrator</option>
                                        <option value="Business Administrator">Other Administrator</option>
                                    </select>
                                </div>

                                <!-- Phone Number with Country Prefix -->
                                <div class="sm:col-span-2 space-y-1.5">
                                    <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                                        Business Phone / WhatsApp Number <span class="text-amber-400">*</span>
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
                                            value="{{ old('phone') }}"
                                            placeholder="803 123 4567"
                                            class="flex-1 px-4 py-3 bg-transparent text-sm text-white placeholder-gray-500 outline-none">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 3: Starting Plan & Billing Model -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-between pb-2 border-b border-white/10">
                                <div class="flex items-center space-x-2.5">
                                    <span class="w-6 h-6 rounded-full bg-[#0E5E4A] text-white text-xs font-extrabold flex items-center justify-center border border-emerald-400/40">3</span>
                                    <h2 class="text-sm font-extrabold uppercase tracking-wider text-emerald-300">Select Starting Billing Model</h2>
                                </div>
                                <span class="text-[11px] text-gray-400">Change anytime in Billing</span>
                            </div>

                            <input type="hidden" name="plan" :value="selectedPlan">

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <!-- Pay-As-You-Transact Option -->
                                <div @click="selectedPlan = 'pay_as_you_go'"
                                     :class="selectedPlan === 'pay_as_you_go' ? 'border-[#F2A900] bg-[#F2A900]/10 ring-1 ring-[#F2A900]' : 'border-white/10 bg-[#0a1612] hover:border-white/25'"
                                     class="p-3.5 rounded-2xl border cursor-pointer transition flex flex-col justify-between">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-extrabold text-white">Pay-As-You-Transact</span>
                                        <span class="text-xs font-extrabold text-[#F2A900]">₦0 / mo</span>
                                    </div>
                                    <p class="text-[11px] text-gray-400 mt-1">Free onboarding. Top up wallet & pay micro-fees only when you issue invoices or clock in.</p>
                                </div>

                                @foreach($plans->where('code', '!=', 'pay_as_you_go') as $p)
                                <div @click="selectedPlan = '{{ $p->code }}'"
                                     :class="selectedPlan === '{{ $p->code }}' ? 'border-emerald-400 bg-emerald-500/15 ring-1 ring-emerald-400' : 'border-white/10 bg-[#0a1612] hover:border-white/25'"
                                     class="p-3.5 rounded-2xl border cursor-pointer transition flex flex-col justify-between">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-extrabold text-white">{{ $p->name }}</span>
                                        <span class="text-xs font-extrabold text-emerald-400">
                                            ₦{{ number_format($p->billing_type === 'per_seat' && $p->price_per_user > 0 ? $p->price_per_user : $p->price, 0) }}{{ $p->billing_type === 'per_seat' ? '/seat' : '/mo' }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-gray-400 mt-1 line-clamp-2">{{ $p->description }}</p>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- SECTION 4: Workspace Security -->
                        <div class="space-y-4">
                            <div class="flex items-center justify-between pb-2 border-b border-white/10">
                                <div class="flex items-center space-x-2.5">
                                    <span class="w-6 h-6 rounded-full bg-[#0E5E4A] text-white text-xs font-extrabold flex items-center justify-center border border-emerald-400/40">4</span>
                                    <h2 class="text-sm font-extrabold uppercase tracking-wider text-emerald-300">Workspace Security</h2>
                                </div>
                                <span class="text-[11px] font-semibold text-gray-400" x-text="passwordStrengthText"></span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Password -->
                                <div class="space-y-1.5">
                                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                                        Master Password <span class="text-amber-400">*</span>
                                    </label>
                                    <div class="relative">
                                        <input id="password" name="password" :type="showPassword ? 'text' : 'password'" required
                                            x-model="password"
                                            placeholder="Min 8 characters"
                                            class="w-full pl-4 pr-10 py-3 rounded-xl bg-[#0a1612] border border-white/15 text-sm text-white placeholder-gray-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
                                        <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-white">
                                            <svg x-show="!showPassword" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <svg x-show="showPassword" x-cloak class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Confirm Password -->
                                <div class="space-y-1.5">
                                    <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                                        Confirm Password <span class="text-amber-400">*</span>
                                    </label>
                                    <div class="relative">
                                        <input id="password_confirmation" name="password_confirmation" :type="showConfirmPassword ? 'text' : 'password'" required
                                            placeholder="Re-enter password"
                                            class="w-full pl-4 pr-10 py-3 rounded-xl bg-[#0a1612] border border-white/15 text-sm text-white placeholder-gray-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
                                        <button type="button" @click="showConfirmPassword = !showConfirmPassword" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-white">
                                            <svg x-show="!showConfirmPassword" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <svg x-show="showConfirmPassword" x-cloak class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Password Strength Bar -->
                            <div class="grid grid-cols-4 gap-1.5 pt-1">
                                <div class="h-1.5 rounded-full transition-colors" :class="passwordScore >= 1 ? 'bg-red-400' : 'bg-white/10'"></div>
                                <div class="h-1.5 rounded-full transition-colors" :class="passwordScore >= 2 ? 'bg-amber-400' : 'bg-white/10'"></div>
                                <div class="h-1.5 rounded-full transition-colors" :class="passwordScore >= 3 ? 'bg-emerald-400' : 'bg-white/10'"></div>
                                <div class="h-1.5 rounded-full transition-colors" :class="passwordScore >= 4 ? 'bg-emerald-300' : 'bg-white/10'"></div>
                            </div>
                        </div>

                        <!-- Terms Agreement Checkbox -->
                        <div class="pt-1">
                            <label class="flex items-start space-x-2.5 text-xs text-gray-300 cursor-pointer">
                                <input type="checkbox" name="agree_terms" value="1" required
                                    {{ old('agree_terms') ? 'checked' : '' }}
                                    class="mt-0.5 w-4 h-4 rounded border-white/20 bg-[#0a1612] text-[#0E5E4A] focus:ring-[#0E5E4A]">
                                <span class="leading-relaxed">
                                    I confirm that I am authorized to create this workspace and agree to the
                                    <a href="https://{{ config('app.domain', 'bizpaddi.com') }}/terms-conditions" target="_blank" class="text-[#F2A900] hover:underline font-semibold">Terms of Service</a>
                                    and
                                    <a href="https://{{ config('app.domain', 'bizpaddi.com') }}/privacy-policy" target="_blank" class="text-[#F2A900] hover:underline font-semibold">Privacy Policy</a>.
                                </span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="w-full py-4 px-6 rounded-2xl text-sm font-extrabold bg-gradient-to-r from-[#0E5E4A] to-emerald-600 hover:from-[#0c4e3e] hover:to-emerald-500 text-white shadow-xl shadow-emerald-950/60 transition duration-200 flex items-center justify-center space-x-2 border border-emerald-400/30 group">
                            <span>Provision Business Workspace</span>
                            <svg class="h-4 w-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </form>

                    <!-- Footer Sign In & Portal Switcher -->
                    <div class="mt-6 pt-5 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-400">
                        <div>
                            Already have a business workspace?
                            <a href="{{ route('admin.login') }}" class="font-bold text-[#F2A900] hover:underline ml-1">Sign in to Workspace &rarr;</a>
                        </div>
                        <div class="flex items-center space-x-3 text-[11px] text-gray-500">
                            <a href="{{ route('team.login') }}" class="hover:text-gray-300 transition">Staff Portal</a>
                            <span>&bull;</span>
                            <a href="{{ route('client.login') }}" class="hover:text-gray-300 transition">Client Portal</a>
                        </div>
                    </div>
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
