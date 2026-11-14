<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#08110e]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Bizpaddi') }} - @yield('title', 'Team Dashboard')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/bizpaddi/icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/bizpaddi/icon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/bizpaddi/icon.png') }}">
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        body { font-family: 'Outfit', 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="h-full bg-[#08110e] text-white antialiased overflow-x-hidden w-full max-w-[100vw]" x-data="{ sidebarOpen: false }">
    @if(session('admin_impersonator_id') || auth('admin')->check())
    <div class="bg-gradient-to-r from-gray-950 via-emerald-950 to-gray-950 text-white px-4 py-2.5 flex items-center justify-between text-xs sm:text-sm font-medium z-[100] sticky top-0 shadow-lg border-b border-emerald-700/40">
        <div class="flex items-center gap-2.5">
            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-400/20 text-amber-300">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
            </span>
            <span>
                Logged into <strong>{{ auth()->user()?->name }}</strong>'s account ({{ ucfirst(str_replace('_', ' ', auth()->user()?->role ?? 'Team Member')) }}) &bull; <span class="text-emerald-400 font-semibold">Admin Impersonation</span>
            </span>
        </div>
        <a href="{{ route('admin.impersonate.stop') }}" class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/10 hover:bg-white/20 rounded-lg text-white font-semibold transition-colors text-xs border border-white/20 shadow-sm">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Return to Admin Panel
        </a>
    </div>
    @endif

    <div class="min-h-full bg-[#08110e] w-full max-w-[100vw] overflow-x-hidden">
        <!-- Mobile Sidebar -->
        @if(auth()->check() && auth()->user()->status === 'active')
        <div x-show="sidebarOpen" class="relative z-50 lg:hidden" role="dialog" aria-modal="true" style="display: none;">
            <div x-show="sidebarOpen" 
                 x-transition:enter="transition-opacity ease-linear duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="transition-opacity ease-linear duration-300" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-black/80 backdrop-blur-sm"></div>

            <div class="fixed inset-0 flex">
                <div x-show="sidebarOpen" 
                     x-transition:enter="transition ease-in-out duration-300 transform" 
                     x-transition:enter-start="-translate-x-full" 
                     x-transition:enter-end="translate-x-0" 
                     x-transition:leave="transition ease-in-out duration-300 transform" 
                     x-transition:leave-start="translate-x-0" 
                     x-transition:leave-end="-translate-x-full" 
                     class="relative mr-16 flex w-full max-w-xs flex-1">
                    <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                        <button type="button" class="-m-2.5 p-2.5 text-white" @click="sidebarOpen = false">
                            <span class="sr-only">Close sidebar</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Mobile Sidebar Content -->
                    <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-[#10231C] px-6 pb-4 ring-1 ring-white/10">
                         @include('teamportal::layouts.partials.sidebar')
                    </div>
                </div>
            </div>
        </div>

        <!-- Desktop Sidebar -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col border-r border-white/10">
            <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-[#10231C] px-6 pb-4">
               @include('teamportal::layouts.partials.sidebar')
            </div>
        </div>
        @endif

        <!-- Main Content -->
        <div class="{{ auth()->check() && auth()->user()->status === 'active' ? 'lg:pl-72' : '' }} flex flex-col min-h-screen bg-[#08110e] w-full max-w-full overflow-x-hidden min-w-0">
            @if(auth()->check() && auth()->user()->status === 'active')
            <!-- Mobile Header -->
            <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center justify-between gap-x-4 border-b border-white/10 bg-[#10231C]/90 backdrop-blur-md px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8 lg:hidden">
                <button type="button" class="-m-2.5 p-2.5 text-gray-300 hover:text-white" @click="sidebarOpen = true">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/bizpaddi/icon.png') }}" alt="Bizpaddi" class="h-7 w-auto">
                    <span class="text-base font-bold text-white tracking-tight">Team Sentinel</span>
                </div>
                <div class="flex items-center gap-x-3">
                    <x-bell-notification />
                </div>
            </div>
            
            <!-- Desktop Header -->
            <div class="hidden lg:flex sticky top-0 z-40 h-16 shrink-0 items-center justify-between gap-x-4 border-b border-white/10 bg-[#10231C]/90 backdrop-blur-md px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3">
                    <h2 class="text-lg font-bold text-white tracking-tight">@yield('title', 'Team Dashboard')</h2>
                </div>
                <div class="flex items-center gap-x-4 lg:gap-x-6">
                    <x-bell-notification />
                    <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-white/10" aria-hidden="true"></div>
                    <div class="flex items-center gap-x-3">
                        <div class="w-9 h-9 rounded-full bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center text-emerald-400 font-bold text-xs uppercase shadow-inner">
                            {{ substr(auth()->user()->name, 0, 2) }}
                        </div>
                        <div class="flex flex-col text-left">
                            <span class="text-sm font-semibold leading-none text-white">{{ auth()->user()->name }}</span>
                            <span class="text-[10px] text-gray-400 uppercase tracking-wider mt-1">{{ ucfirst(str_replace('_', ' ', auth()->user()->role ?? 'Team Member')) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <main class="py-8 flex-1">
                <div class="mx-auto max-w-7xl 2xl:max-w-[1600px] px-4 sm:px-6 lg:px-8">
                    @if(session('success'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="mb-6 rounded-2xl bg-emerald-950/40 p-4 border border-emerald-500/40 backdrop-blur-md shadow-lg shadow-emerald-950/30 flex items-center gap-3">
                            <div class="flex-shrink-0 text-emerald-400">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-emerald-200">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    <x-notification-prompt />
    @stack('scripts')
</body>
</html>
