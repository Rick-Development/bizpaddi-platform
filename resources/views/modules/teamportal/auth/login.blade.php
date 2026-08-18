<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Team Portal Login - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Outfit', sans-serif; 
        }
        .brand-gradient {
            background: linear-gradient(135deg, #FB4C4B 0%, #D93837 100%);
        }
        .brand-glow:hover {
            box-shadow: 0 10px 25px -5px rgba(251, 76, 75, 0.4);
        }
        .glass-panel {
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
    </style>
</head>
<body class="h-full bg-slate-950 text-slate-100 overflow-hidden">
    <div class="flex min-h-full">
        <!-- Left Side: Visual Pane (gorgeous tech visual) -->
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-slate-900">
            <!-- Background Image -->
            <img src="{{ asset('images/login_bg.png') }}" alt="Portal Background" class="absolute inset-0 w-full h-full object-cover opacity-85">
            <div class="absolute inset-0 bg-gradient-to-tr from-slate-950 via-slate-950/60 to-transparent"></div>
            
            <!-- Floating Decorative Glow -->
            <div class="absolute top-1/4 left-1/4 w-96 h-96 rounded-full bg-[#FB4C4B]/10 blur-[120px] animate-pulse"></div>

            <!-- Content overlay -->
            <div class="relative z-10 flex flex-col justify-between p-16 h-full w-full">
                <!-- Branding Header -->
                <div class="flex items-center gap-3">
                    <img src="{{ asset('logo.png') }}" alt="Rick Development Logo" class="h-10 w-auto object-contain">
                </div>

                <!-- Central Pitch -->
                <div class="space-y-6 max-w-lg">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-red-500/10 text-[#FB4C4B] border border-red-500/20 uppercase tracking-wider">
                        <span class="h-2 w-2 rounded-full bg-[#FB4C4B]"></span>
                        Internal Team Portal
                    </span>
                    <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight text-white leading-tight">
                        Fast Delivery is <br>the Ultimate Key.
                    </h1>
                    <p class="text-lg text-slate-400 font-medium leading-relaxed">
                        Sign in to track sessions, coordinate tasks, manage priorities, and output high-quality code.
                    </p>
                </div>

                <!-- Footer note -->
                <div class="text-xs text-slate-500 font-medium">
                    &copy; {{ date('Y') }} Rick Development. All rights reserved. Registered staff only.
                </div>
            </div>
        </div>

        <!-- Right Side: Login Form Pane -->
        <div class="flex flex-1 flex-col justify-center px-6 py-12 lg:w-1/2 lg:px-8 bg-slate-950 relative">
            
            <!-- Mobile Branding Header (visible only on smaller screens) -->
            <div class="lg:hidden absolute top-8 left-8 flex items-center gap-2">
                <img src="{{ asset('logo.png') }}" alt="Rick Development Logo" class="h-8 w-auto object-contain">
            </div>

            <!-- Soft decorative backdrops behind login block on mobile -->
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none overflow-hidden lg:hidden">
                <div class="w-80 h-80 rounded-full bg-[#FB4C4B]/5 blur-[80px]"></div>
            </div>

            <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10">
                <h2 class="text-3xl font-extrabold text-white tracking-tight">Welcome Back</h2>
                <p class="mt-2 text-sm text-slate-400">Sign in to your account to resume work.</p>
            </div>

            <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md relative z-10">
                <div class="bg-slate-900/50 border border-slate-800/80 p-8 sm:p-10 rounded-3xl shadow-2xl backdrop-blur-md">
                    <form class="space-y-6" action="{{ route('team.login') }}" method="POST">
                        @csrf
                        
                        <!-- Email Input -->
                        <div class="space-y-2">
                            <label for="email" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Email Address</label>
                            <div class="relative rounded-2xl shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                    <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                </div>
                                <input id="email" name="email" type="email" autocomplete="email" required 
                                       class="block w-full rounded-2xl border-0 bg-slate-950/60 py-3.5 pl-11 pr-4 text-white shadow-sm ring-1 ring-inset ring-slate-800 placeholder:text-slate-600 focus:ring-2 focus:ring-inset focus:ring-[#FB4C4B] sm:text-sm transition-all focus:bg-slate-950" 
                                       placeholder="you@rickdevelopment.com">
                            </div>
                            @error('email')
                                <p class="mt-1.5 text-xs text-red-500 font-semibold flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Password Input -->
                        <div class="space-y-2">
                            <label for="password" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Password</label>
                            <div class="relative rounded-2xl shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                    <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input id="password" name="password" type="password" autocomplete="current-password" required 
                                       class="block w-full rounded-2xl border-0 bg-slate-950/60 py-3.5 pl-11 pr-11 text-white shadow-sm ring-1 ring-inset ring-slate-800 placeholder:text-slate-600 focus:ring-2 focus:ring-inset focus:ring-[#FB4C4B] sm:text-sm transition-all focus:bg-slate-950" 
                                       placeholder="••••••••">
                                <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 flex items-center pr-4 cursor-pointer text-slate-500 hover:text-slate-300 focus:outline-none">
                                    <svg id="eye-icon-show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <svg id="eye-icon-hide" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 hidden">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1.5 text-xs text-red-500 font-semibold flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-2.5 cursor-pointer text-xs font-semibold text-slate-400 select-none">
                                <input id="remember" name="remember" type="checkbox" 
                                       class="rounded text-[#FB4C4B] bg-slate-950/60 border-slate-800 focus:ring-[#FB4C4B] focus:ring-offset-slate-900">
                                <span>Remember my session</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button type="submit" 
                                    class="flex w-full justify-center items-center py-3 px-4 rounded-2xl brand-gradient text-white text-sm font-bold shadow-lg shadow-red-500/10 hover:shadow-red-500/25 transition-all brand-glow cursor-pointer">
                                Sign in to Portal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const showIcon = document.getElementById('eye-icon-show');
            const hideIcon = document.getElementById('eye-icon-hide');
    
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                showIcon.classList.add('hidden');
                hideIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                showIcon.classList.remove('hidden');
                hideIcon.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
