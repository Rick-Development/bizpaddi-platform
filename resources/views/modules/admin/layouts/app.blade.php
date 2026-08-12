<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Rick Development</title>
    @php
        $favicon = \Modules\Settings\Models\Setting::get('favicon');
    @endphp
    @if($favicon)
        <link rel="icon" href="{{ asset('storage/' . $favicon) }}" type="image/x-icon">
    @else
        <link rel="icon" href="/logo.png" type="image/png">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', system-ui, -apple-system, sans-serif; }

        .sidebar-gradient {
            background: linear-gradient(180deg, #191C47 0%, #0F1229 100%);
        }

        .stat-card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stat-card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* ── Critical layout overrides (supplement compiled Tailwind) ── */
        @media (min-width: 1024px) {
            .lg\:pl-72 { padding-left: 18rem !important; }
            .lg\:fixed  { position: fixed !important; }
            .lg\:inset-y-0 { top: 0; bottom: 0; }
            .lg\:z-50  { z-index: 50 !important; }
            .lg\:flex  { display: flex !important; }
            .lg\:w-72  { width: 18rem !important; }
            .lg\:flex-col { flex-direction: column !important; }
            .lg\:hidden { display: none !important; }
        }
        .grid-cols-7 { grid-template-columns: repeat(7, minmax(0, 1fr)); }
        .grid-cols-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        .grid-cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        @media (min-width: 640px) {
            .sm\:grid-cols-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        }
        @media (min-width: 1024px) {
            .lg\:grid-cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            .lg\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .lg\:col-span-2  { grid-column: span 2 / span 2; }
            .lg\:flex-row    { flex-direction: row !important; }
            .lg\:w-1\/3      { width: 33.333333% !important; }
            .lg\:w-2\/3      { width: 66.666667% !important; }
        }
    </style>
    @stack('styles')
</head>
<body class="h-full bg-gray-50" x-data="{ sidebarOpen: false }">
    <div class="min-h-full">
        <!-- Mobile sidebar -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="relative z-50 lg:hidden" 
             role="dialog" 
             aria-modal="true"
             style="display: none;">
            <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm"></div>
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
                        <button type="button" @click="sidebarOpen = false" class="-m-2.5 p-2.5">
                            <span class="sr-only">Close sidebar</span>
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    @include('admin::layouts.partials.sidebar')
                </div>
            </div>
        </div>

        <!-- Desktop sidebar -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
            @include('admin::layouts.partials.sidebar')
        </div>

        <!-- Main content -->
        <div class="lg:pl-72">
            <!-- Top bar -->
            <div class="sticky top-0 z-40 border-b border-gray-200 bg-white/80 backdrop-blur-md shadow-sm">
                <div class="flex h-16 shrink-0 items-center gap-x-4 px-4 sm:gap-x-6 sm:px-6 lg:px-8">
                    <button type="button" @click="sidebarOpen = true" class="-m-2.5 p-2.5 text-gray-700 lg:hidden">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <div class="h-6 w-px bg-gray-200 lg:hidden"></div>

                    <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                        <div class="flex flex-1 items-center">
                            <h2 class="text-lg font-semibold text-gray-900">@yield('title', 'Dashboard')</h2>
                        </div>
                        <div class="flex items-center gap-x-4 lg:gap-x-6">
                            <!-- User menu -->
                            <div class="flex items-center gap-x-4">
                                <div class="hidden sm:block">
                                    <p class="text-sm font-semibold text-gray-900">{{ auth('admin')->user()->name }}</p>
                                    <p class="text-xs text-gray-500">Administrator</p>
                                </div>
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-x-2 rounded-lg px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100 transition-colors">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                        </svg>
                                        <span class="hidden sm:inline">Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page content -->
            <main class="py-8">
                <div class="px-4 sm:px-6 lg:px-8 w-full">
                    @if(session('success'))
                    <div class="mb-6 rounded-xl bg-green-50 p-4 border-l-4 border-green-500">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="mb-6 rounded-xl bg-red-50 p-4 border-l-4 border-red-500">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
