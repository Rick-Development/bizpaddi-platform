<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Your Business Workspace - Bizpaddi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/bizpaddi/icon.png') }}" type="image/png">
    <style>
        * { font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif; }
    </style>
</head>
<body class="min-h-full bg-gradient-to-b from-[#10231C] via-[#08110e] to-[#10231C] text-white flex flex-col justify-between selection:bg-emerald-500 selection:text-white">

    <!-- Top Navigation Minimal Bar -->
    <header class="py-6 px-6 sm:px-12 border-b border-white/10 flex items-center justify-between">
        <a href="/" class="flex items-center space-x-3">
            <img src="{{ asset('images/bizpaddi/logo.png') }}" alt="Bizpaddi" class="h-8 w-auto">
        </a>
        <div class="flex items-center space-x-3 text-sm">
            <span class="text-gray-400 hidden sm:inline">Already registered?</span>
            <a href="{{ route('admin.login') }}" class="font-bold text-[#F2A900] hover:text-amber-300 transition">
                Sign In &rarr;
            </a>
        </div>
    </header>

    <!-- Main Content Container: Clean Centered Form, No Distracting Side Copy -->
    <main class="flex-1 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-6">

            <!-- Title & Badge -->
            <div class="text-center space-y-2">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-[#0E5E4A] shadow-xl border border-emerald-400/30 mb-2">
                    <img src="{{ asset('images/bizpaddi/icon.png') }}" alt="Bizpaddi" class="w-8 h-8 object-contain">
                </div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight">
                    Create Your Business Workspace
                </h1>
                <p class="text-gray-400 text-xs sm:text-sm">
                    Automate your payments, deliverables, and team in one unified platform
                </p>
            </div>

            <!-- Dark Form Card (NO White Box) -->
            <div class="bg-[#10231C]/95 backdrop-blur-xl rounded-3xl shadow-2xl p-7 sm:p-9 border border-white/10 text-white">
                <form action="{{ route('register') }}" method="POST" class="space-y-4">
                    @csrf
                    @if(request('plan') || old('plan'))
                        <input type="hidden" name="plan" value="{{ request('plan', old('plan')) }}">
                    @endif

                    @if ($errors->any())
                    <div class="bg-red-950/70 border border-red-500/40 p-4 rounded-xl text-xs text-red-200 space-y-1">
                        @foreach ($errors->all() as $error)
                            <p class="font-medium">• {{ $error }}</p>
                        @endforeach
                    </div>
                    @endif

                    <!-- Company Name -->
                    <div class="space-y-1.5">
                        <label for="company_name" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                            Company / Business Name
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <input id="company_name" name="company_name" type="text" required 
                                value="{{ old('company_name') }}"
                                placeholder="e.g. Acme Innovations Ltd" 
                                class="w-full pl-10 pr-4 py-3 rounded-xl bg-[#0a1612] border border-white/15 text-sm text-white placeholder-gray-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
                        </div>
                    </div>

                    <!-- Name Grid: First Name & Last Name -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="space-y-1.5">
                            <label for="first_name" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                                First Name
                            </label>
                            <input id="first_name" name="first_name" type="text" required 
                                value="{{ old('first_name') }}"
                                placeholder="e.g. John" 
                                class="w-full px-3.5 py-3 rounded-xl bg-[#0a1612] border border-white/15 text-sm text-white placeholder-gray-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
                        </div>
                        <div class="space-y-1.5">
                            <label for="last_name" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                                Last Name
                            </label>
                            <input id="last_name" name="last_name" type="text" required 
                                value="{{ old('last_name') }}"
                                placeholder="e.g. Doe" 
                                class="w-full px-3.5 py-3 rounded-xl bg-[#0a1612] border border-white/15 text-sm text-white placeholder-gray-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
                        </div>
                    </div>

                    <!-- Work Email -->
                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                            Work Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                            </div>
                            <input id="email" name="email" type="email" required 
                                value="{{ old('email') }}"
                                placeholder="name@company.com" 
                                class="w-full pl-10 pr-4 py-3 rounded-xl bg-[#0a1612] border border-white/15 text-sm text-white placeholder-gray-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
                        </div>
                    </div>

                    <!-- Phone Number (Optional) -->
                    <div class="space-y-1.5">
                        <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                            Phone Number <span class="text-gray-500 font-normal lowercase">(optional)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <input id="phone" name="phone" type="tel" 
                                value="{{ old('phone') }}"
                                placeholder="080 1234 5678" 
                                class="w-full pl-10 pr-4 py-3 rounded-xl bg-[#0a1612] border border-white/15 text-sm text-white placeholder-gray-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
                        </div>
                    </div>

                    <!-- Password Field (Full Width) -->
                    <div class="space-y-1.5">
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                            Password
                        </label>
                        <input id="password" name="password" type="password" required 
                            placeholder="Min 8 characters" 
                            class="w-full px-4 py-3 rounded-xl bg-[#0a1612] border border-white/15 text-sm text-white placeholder-gray-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
                    </div>

                    <!-- Confirm Password Field (Full Width) -->
                    <div class="space-y-1.5">
                        <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                            Confirm Password
                        </label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required 
                            placeholder="Re-enter password" 
                            class="w-full px-4 py-3 rounded-xl bg-[#0a1612] border border-white/15 text-sm text-white placeholder-gray-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
                    </div>

                    <!-- Terms Agreement Checkbox -->
                    <div class="pt-1">
                        <label class="flex items-start space-x-2.5 text-xs text-gray-300 cursor-pointer">
                            <input type="checkbox" name="agree_terms" value="1" required 
                                {{ old('agree_terms') ? 'checked' : '' }}
                                class="mt-0.5 w-4 h-4 rounded border-white/20 bg-[#0a1612] text-[#0E5E4A] focus:ring-[#0E5E4A]">
                            <span class="leading-relaxed">
                                I agree to the <a href="/terms-conditions" target="_blank" class="text-amber-300 hover:underline">Terms of Service</a> and <a href="/privacy-policy" target="_blank" class="text-amber-300 hover:underline">Privacy Policy</a>.
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                        class="w-full py-3.5 px-4 rounded-xl text-sm font-bold bg-[#0E5E4A] hover:bg-[#0c4e3e] text-white shadow-lg shadow-[#0E5E4A]/30 transition duration-200 flex items-center justify-center space-x-2 border border-emerald-400/30 group">
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
