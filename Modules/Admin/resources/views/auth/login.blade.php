<!DOCTYPE html>
<html lang="en" class="h-full dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Bizpaddi Business Workspace</title>
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
      x-data="{ showPassword: false }">

    <!-- Top Navigation Bar -->
    <header class="py-5 px-6 sm:px-12 border-b border-white/10 bg-[#08110e]/80 backdrop-blur-md sticky top-0 z-30 flex items-center justify-between">
        <a href="https://{{ config('app.domain', 'bizpaddi.com') }}" class="flex items-center space-x-3">
            <img src="{{ asset('images/bizpaddi/logo.png') }}" alt="Bizpaddi" class="h-8 w-auto">
            <span class="hidden md:inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-widest bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                Workspace Portal
            </span>
        </a>
        <div class="flex items-center space-x-4 text-xs sm:text-sm">
            <span class="text-gray-400 hidden sm:inline">New to Bizpaddi?</span>
            <a href="{{ route('admin.register') }}" class="px-4 py-2 rounded-xl font-bold bg-[#0E5E4A] hover:bg-[#0c4e3e] text-white border border-emerald-400/30 transition flex items-center space-x-1.5 shadow-lg">
                <span>Create Workspace</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </header>

    <!-- Main 2-Column Enterprise Sign-In Layout -->
    <main class="flex-1 flex items-center py-10 px-4 sm:px-6 lg:px-12">
        <div class="max-w-6xl w-full mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">

            <!-- LEFT COLUMN: Workspace Command Overview & Portal Switcher -->
            <div class="lg:col-span-6 space-y-6 order-2 lg:order-1">
                <div class="space-y-3">
                    <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-[#0E5E4A]/30 border border-emerald-400/30 text-emerald-300 text-xs font-bold">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>Bizpaddi Enterprise Cloud</span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight">
                        Command Your Business Operations in Real Time.
                    </h1>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Sign in to manage invoices, digital storefront orders, virtual account settlements, team attendance, and financial reports.
                    </p>
                </div>

                <!-- Portal Directory Switcher Cards -->
                <div class="space-y-3">
                    <p class="text-[11px] font-extrabold uppercase tracking-wider text-gray-400">Select Your Workspace Portal</p>

                    <!-- Active: Business Admin Workspace -->
                    <div class="p-4 rounded-2xl bg-[#10231C] border-2 border-emerald-500/60 shadow-lg flex items-center justify-between">
                        <div class="flex items-center space-x-3.5">
                            <div class="w-10 h-10 rounded-xl bg-[#0E5E4A] border border-emerald-400/40 flex items-center justify-center text-[#F2A900]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm font-extrabold text-white">Business Owner & Admin Workspace</span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-300">Active</span>
                                </div>
                                <p class="text-xs text-gray-400 mt-0.5">Full control over billing, accounting, storefront, CRM & team.</p>
                            </div>
                        </div>
                        <span class="text-xs font-mono text-emerald-400 hidden sm:inline">/dashboard/login</span>
                    </div>

                    <!-- Staff & Team Portal -->
                    <a href="{{ route('team.login') }}" class="block p-4 rounded-2xl bg-[#10231C]/60 hover:bg-[#10231C] border border-white/10 hover:border-emerald-500/40 transition flex items-center justify-between group">
                        <div class="flex items-center space-x-3.5">
                            <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-gray-300 group-hover:text-emerald-400 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <span class="text-sm font-bold text-white group-hover:text-emerald-300 transition">Staff & Team Member Portal &rarr;</span>
                                <p class="text-xs text-gray-400 mt-0.5">Clock-in attendance, project tasks, BDM leads, QA & work logs.</p>
                            </div>
                        </div>
                        <span class="text-xs font-mono text-gray-500 hidden sm:inline">/dashboard/team/login</span>
                    </a>

                    <!-- Client Portal -->
                    <a href="{{ route('client.login') }}" class="block p-4 rounded-2xl bg-[#10231C]/60 hover:bg-[#10231C] border border-white/10 hover:border-emerald-500/40 transition flex items-center justify-between group">
                        <div class="flex items-center space-x-3.5">
                            <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-gray-300 group-hover:text-[#F2A900] transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <span class="text-sm font-bold text-white group-hover:text-[#F2A900] transition">Client Self-Service Portal &rarr;</span>
                                <p class="text-xs text-gray-400 mt-0.5">Approve milestones, pay invoices, sign contracts & raise tickets.</p>
                            </div>
                        </div>
                        <span class="text-xs font-mono text-gray-500 hidden sm:inline">/dashboard/client/login</span>
                    </a>
                </div>
            </div>

            <!-- RIGHT COLUMN: Business Workspace Sign-In Card -->
            <div class="lg:col-span-6 order-1 lg:order-2">
                <div class="bg-[#10231C]/95 backdrop-blur-xl rounded-3xl shadow-2xl p-7 sm:p-10 border border-white/10 text-white">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-white/10">
                        <div>
                            <span class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-400">Business Workspace</span>
                            <h2 class="text-2xl font-extrabold text-white mt-0.5">Sign In to Dashboard</h2>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-[#0E5E4A] border border-emerald-400/30 flex items-center justify-center shadow-lg">
                            <img src="{{ asset('images/bizpaddi/icon.png') }}" alt="Bizpaddi" class="w-7 h-7 object-contain">
                        </div>
                    </div>

                    <form class="space-y-5" action="{{ route('admin.login') }}" method="POST">
                        @csrf

                        @if ($errors->any())
                        <div class="bg-red-950/80 border border-red-500/40 p-4 rounded-xl text-xs text-red-200 space-y-1">
                            @foreach ($errors->all() as $error)
                                <p class="font-medium">• {{ $error }}</p>
                            @endforeach
                        </div>
                        @endif

                        @if (session('success'))
                        <div class="bg-emerald-950/80 border border-emerald-500/40 p-4 rounded-xl text-xs text-emerald-200">
                            {{ session('success') }}
                        </div>
                        @endif

                        <!-- Email Field -->
                        <div class="space-y-1.5">
                            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                                Work Email Address
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                                </div>
                                <input id="email" name="email" type="email" autocomplete="email" required
                                    class="w-full pl-10 pr-4 py-3.5 rounded-xl bg-[#0a1612] border border-white/15 text-sm text-white placeholder-gray-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition"
                                    placeholder="founder@company.com" value="{{ old('email') }}">
                            </div>
                        </div>

                        <!-- Password Field with Show/Hide Toggle -->
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between">
                                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                                    Password
                                </label>
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                </div>
                                <input id="password" name="password" :type="showPassword ? 'text' : 'password'" autocomplete="current-password" required
                                    class="w-full pl-10 pr-11 py-3.5 rounded-xl bg-[#0a1612] border border-white/15 text-sm text-white placeholder-gray-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition"
                                    placeholder="••••••••••••">
                                <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-white">
                                    <svg x-show="!showPassword" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg x-show="showPassword" x-cloak class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Remember Me & Security Indicator -->
                        <div class="flex items-center justify-between text-xs">
                            <label class="flex items-center space-x-2 text-gray-300 cursor-pointer">
                                <input type="checkbox" name="remember" class="w-4 h-4 rounded border-white/20 bg-[#0a1612] text-[#0E5E4A] focus:ring-[#0E5E4A]">
                                <span>Keep my session active</span>
                            </label>
                            <span class="text-emerald-400/80 flex items-center space-x-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                <span>256-Bit TLS</span>
                            </span>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="w-full py-4 px-6 rounded-2xl text-sm font-extrabold bg-gradient-to-r from-[#0E5E4A] to-emerald-600 hover:from-[#0c4e3e] hover:to-emerald-500 text-white shadow-xl shadow-emerald-950/60 transition duration-200 flex items-center justify-center space-x-2 border border-emerald-400/30 group">
                            <span>Sign In to Business Workspace</span>
                            <svg class="h-4 w-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </form>

                    <!-- Create New Workspace Box -->
                    <div class="mt-6 pt-5 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                        <span class="text-gray-400">Don't have a company workspace yet?</span>
                        <a href="{{ route('admin.register') }}" class="px-4 py-2 rounded-xl font-bold bg-[#F2A900]/15 text-[#F2A900] hover:bg-[#F2A900] hover:text-[#08110e] border border-[#F2A900]/30 transition">
                            Create Business Workspace &rarr;
                        </a>
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
