<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Sign In - Bizpaddi HQ</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
<body class="min-h-full bg-gradient-to-b from-[#10231C] via-[#08110e] to-[#10231C] text-white flex flex-col justify-between selection:bg-emerald-500 selection:text-white"
      x-data="{
          email: '{{ old('email', '') }}',
          password: '',
          showPassword: false
      }">

    <!-- Top Bar -->
    <header class="py-6 px-6 sm:px-12 border-b border-white/10 flex items-center justify-between">
        <a href="/" class="flex items-center space-x-3">
            <img src="{{ asset('images/bizpaddi/logo.png') }}" alt="Bizpaddi" class="h-8 w-auto">
        </a>
        <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-[#F2A900] text-[#10231C]">
            Platform Owner HQ
        </span>
    </header>

    <div class="flex-1 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-6">
            <!-- Header -->
            <div class="text-center space-y-2">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-[#0E5E4A] shadow-xl border border-amber-400/40 mb-2">
                    <img src="{{ asset('images/bizpaddi/icon.png') }}" alt="Bizpaddi" class="w-8 h-8 object-contain">
                </div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight">
                    Super Admin Portal
                </h1>
                <p class="text-gray-400 text-xs sm:text-sm">
                    Platform owner authentication & system controls
                </p>
            </div>

            <!-- Dark Form Card -->
            <div class="bg-[#10231C]/95 backdrop-blur-xl rounded-3xl shadow-2xl p-8 sm:p-10 border border-amber-300/25 text-white">
                <form class="space-y-5" action="{{ route('super-admin.login') }}" method="POST">
                    @csrf

                    @if ($errors->any())
                    <div class="bg-red-950/70 border border-red-500/40 p-4 rounded-xl text-xs text-red-200 space-y-1">
                        @foreach ($errors->all() as $error)
                            <p class="font-medium">• {{ $error }}</p>
                        @endforeach
                    </div>
                    @endif

                    @if (session('success'))
                    <div class="bg-emerald-950/70 border border-emerald-500/40 p-4 rounded-xl text-xs text-emerald-200">
                        {{ session('success') }}
                    </div>
                    @endif

                    <!-- Email Field -->
                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                            Super Admin Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="email" required 
                                x-model="email"
                                class="w-full pl-10 pr-4 py-3 rounded-xl bg-[#0a1612] border border-white/15 text-sm text-white placeholder-gray-500 focus:border-amber-400 focus:ring-2 focus:ring-amber-400/20 outline-none transition" 
                                placeholder="Enter your email">
                        </div>
                    </div>

                    <!-- Password Field with View Toggle -->
                    <div class="space-y-1.5">
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                            Master Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <input id="password" name="password" :type="showPassword ? 'text' : 'password'" type="password" autocomplete="current-password" required 
                                x-model="password"
                                class="w-full pl-10 pr-12 py-3 rounded-xl bg-[#0a1612] border border-white/15 text-sm text-white placeholder-gray-500 focus:border-amber-400 focus:ring-2 focus:ring-amber-400/20 outline-none transition" 
                                placeholder="••••••••">
                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-amber-400 transition" title="Toggle password visibility">
                                <svg x-show="!showPassword" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showPassword" x-cloak class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between text-xs">
                        <label class="flex items-center space-x-2 text-gray-400 cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-white/20 bg-[#0a1612] text-[#F2A900] focus:ring-[#F2A900]">
                            <span>Remember terminal</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                        class="w-full py-3.5 px-4 rounded-xl text-sm font-bold bg-[#F2A900] hover:bg-[#d99700] text-[#10231C] shadow-lg shadow-amber-500/20 transition duration-200 flex items-center justify-center space-x-2">
                        <span>Authenticate Super Admin</span>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="py-6 text-center text-xs text-gray-500 border-t border-white/10">
        &copy; {{ date('Y') }} Bizpaddi SaaS Platform Owner Engine. Restricted Area.
    </footer>

</body>
</html>
