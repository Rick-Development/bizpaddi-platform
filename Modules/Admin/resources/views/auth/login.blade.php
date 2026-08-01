<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal - Rick Development LTD</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }
        
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(251, 76, 75, 0.3); }
            50% { box-shadow: 0 0 40px rgba(251, 76, 75, 0.6); }
        }
        
        .animated-bg {
            background: linear-gradient(-45deg, #191C47, #2A2E5F, #FB4C4B, #D93837);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .input-glow:focus {
            box-shadow: 0 0 0 3px rgba(251, 76, 75, 0.1);
        }
        
        .particle {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }
        
        .btn-shine {
            position: relative;
            overflow: hidden;
        }
        
        .btn-shine::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }
        
        .btn-shine:hover::before {
            left: 100%;
        }
    </style>
</head>
<body class="h-full overflow-hidden animated-bg">
    <!-- Floating Particles -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="particle w-2 h-2 bg-white/20 top-[10%] left-[10%] animate-float" style="animation-duration: 3s;"></div>
        <div class="particle w-3 h-3 bg-white/10 top-[20%] left-[80%] animate-float" style="animation-duration: 4s; animation-delay: 1s;"></div>
        <div class="particle w-2 h-2 bg-white/15 top-[60%] left-[15%] animate-float" style="animation-duration: 5s; animation-delay: 2s;"></div>
        <div class="particle w-4 h-4 bg-white/10 top-[80%] left-[70%] animate-float" style="animation-duration: 6s;"></div>
        <div class="particle w-2 h-2 bg-white/20 top-[40%] left-[90%] animate-float" style="animation-duration: 4s; animation-delay: 1.5s;"></div>
    </div>

    <div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-md w-full space-y-8">
            <!-- Logo & Header -->
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-gradient-to-br from-[#FB4C4B] to-[#D93837] shadow-2xl mb-6 transform hover:scale-110 transition-all duration-300" style="animation: pulse-glow 3s ease-in-out infinite;">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-white mb-2 tracking-tight">
                    Rick Development
                </h1>
                <p class="text-white/70 text-sm font-medium">
                    Admin Portal Access
                </p>
            </div>

            <!-- Login Card -->
            <div class="glass-card rounded-3xl shadow-2xl p-8 sm:p-10 transform hover:scale-[1.02] transition-all duration-300">
                <form class="space-y-6" action="{{ route('admin.login') }}" method="POST">
                    @csrf

                    @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-[#FB4C4B] p-4 rounded-xl animate-shake">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-[#FB4C4B]" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                @foreach ($errors->all() as $error)
                                    <p class="text-sm text-red-700 font-medium">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Email Field -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-gray-700">
                            Email Address
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-[#FB4C4B] transition-colors duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="email" required 
                                class="input-glow block w-full pl-12 pr-4 py-3.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent transition-all duration-200 text-sm font-medium bg-gray-50 hover:bg-white" 
                                placeholder="" value="{{ old('email') }}">
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-semibold text-gray-700">
                            Password
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-[#FB4C4B] transition-colors duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <input id="password" name="password" type="password" autocomplete="current-password" required 
                                class="input-glow block w-full pl-12 pr-4 py-3.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent transition-all duration-200 text-sm font-medium bg-gray-50 hover:bg-white" 
                                placeholder="">
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox" 
                                class="h-4 w-4 text-[#FB4C4B] focus:ring-[#FB4C4B] border-gray-300 rounded transition-all">
                            <label for="remember" class="ml-2 block text-sm font-medium text-gray-700">
                                Keep me signed in
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" 
                            style="background: linear-gradient(to right, #FB4C4B, #D93837);"
                            class="btn-shine w-full flex items-center justify-center py-4 px-6 border-0 rounded-xl shadow-lg text-base font-bold text-white hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transform hover:-translate-y-0.5 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            Sign In to Dashboard
                        </button>
                    </div>
                </form>

                <!-- Divider -->
                <div class="mt-8">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-xs">
                            <span class="px-3 bg-white text-gray-500 font-medium">
                                🔒 Secure Authentication
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Security Notice -->
                <div class="mt-6 text-center">
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Protected by enterprise-grade security.<br>
                        All activities are monitored and logged.
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center">
                <p class="text-sm text-white/60 font-medium">
                    &copy; {{ date('Y') }} Rick Development LTD
                </p>
            </div>
        </div>
    </div>
</body>
</html>
