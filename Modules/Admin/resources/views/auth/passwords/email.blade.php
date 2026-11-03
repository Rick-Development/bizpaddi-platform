<!DOCTYPE html>
<html lang="en" class="h-full bg-[#08110e]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password — Bizpaddi Business Workspace</title>
    <link rel="icon" type="image/png" href="{{ asset('images/bizpaddi/icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        brand: {
                            primary: '#0E5E4A',
                            secondary: '#10231C',
                            amber: '#F2A900',
                            dark: '#08110e',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-full flex flex-col justify-between text-white bg-[#08110e] antialiased">
    <!-- Navbar -->
    <header class="w-full border-b border-white/10 px-4 sm:px-8 py-5">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="{{ route('admin.login') }}" class="flex items-center space-x-3">
                <img src="{{ asset('images/bizpaddi/logo.png') }}" alt="Bizpaddi" class="h-8 sm:h-9 w-auto">
                <span class="text-[10px] font-extrabold uppercase tracking-widest px-2.5 py-1 rounded-full bg-emerald-500/15 text-emerald-300 border border-emerald-500/30">
                    Workspace Portal
                </span>
            </a>
            <a href="{{ route('admin.login') }}" class="inline-flex items-center space-x-2 text-xs font-bold text-gray-300 hover:text-white transition">
                <span>&larr; Back to Sign In</span>
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 flex items-center justify-center p-4 py-12">
        <div class="w-full max-w-md">
            <div class="bg-[#10231C]/95 backdrop-blur-xl rounded-3xl shadow-2xl p-7 sm:p-10 border border-white/10 text-white space-y-6">
                <div class="text-center space-y-2 pb-4 border-b border-white/10">
                    <div class="w-12 h-12 rounded-2xl bg-[#0E5E4A] border border-emerald-400/30 flex items-center justify-center mx-auto shadow-lg text-[#F2A900]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                    </div>
                    <h2 class="text-2xl font-black text-white tracking-tight">Forgot Password?</h2>
                    <p class="text-xs text-gray-400">
                        Enter your work email address and we'll send you a secure link to reset your workspace access.
                    </p>
                </div>

                @if (session('status'))
                <div class="bg-emerald-950/80 border border-emerald-500/40 p-4 rounded-xl text-xs text-emerald-200 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>{{ session('status') }}</span>
                </div>
                @endif

                @if ($errors->any())
                <div class="bg-red-950/80 border border-red-500/40 p-4 rounded-xl text-xs text-red-200 space-y-1">
                    @foreach ($errors->all() as $error)
                        <p class="font-medium">• {{ $error }}</p>
                    @endforeach
                </div>
                @endif

                <form method="POST" action="{{ route('admin.password.email') }}" class="space-y-5">
                    @csrf

                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-300">
                            Work Email Address
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="email" required autofocus
                                class="w-full pl-10 pr-4 py-3.5 rounded-xl bg-[#0a1612] border border-white/15 text-sm text-white placeholder-gray-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition"
                                placeholder="founder@company.com" value="{{ old('email') }}">
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full py-4 px-6 rounded-2xl text-sm font-extrabold bg-gradient-to-r from-[#0E5E4A] to-emerald-600 hover:from-[#0c4e3e] hover:to-emerald-500 text-white shadow-xl shadow-emerald-950/60 transition duration-200 flex items-center justify-center space-x-2 border border-emerald-400/30">
                        <span>Send Password Reset Link</span>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>

                <div class="pt-4 border-t border-white/10 text-center">
                    <p class="text-xs text-gray-400">
                        Remember your password?
                        <a href="{{ route('admin.login') }}" class="font-bold text-emerald-400 hover:underline ml-1">Sign in here</a>
                    </p>
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

