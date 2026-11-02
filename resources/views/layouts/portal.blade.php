<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal') - Bizpaddi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
               class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-brand-secondary to-purple-900 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0">
            <div class="flex items-center justify-between h-16 px-6 bg-black/20">
                <h2 class="text-xl font-bold text-white">@yield('portal-name', 'Portal')</h2>
                <button @click="sidebarOpen = false" class="lg:hidden text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <nav class="px-4 py-6 space-y-2">
                @yield('sidebar-nav')
            </nav>

            <!-- User Info -->
            <div class="absolute bottom-0 w-full p-4 bg-black/20">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-brand-primary flex items-center justify-center text-white font-bold">
                        {{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'G' }}
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-white">{{ auth()->user()->name ?? 'Guest' }}</p>
                        <p class="text-xs text-gray-300">{{ auth()->user()->email ?? 'Not logged in' }}</p>
                    </div>
                </div>
                @auth
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="w-full text-left text-sm text-gray-300 hover:text-white transition">
                        Logout
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}" class="block mt-3 text-sm text-gray-300 hover:text-white transition">
                    Login
                </a>
                @endauth
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between h-16 px-6">
                    <button @click="sidebarOpen = true" class="lg:hidden text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    
                    <h1 class="text-2xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                    
                    <div class="flex items-center space-x-4">
                        <a href="/" class="text-sm text-gray-600 hover:text-brand-primary transition">
                            ← Back to Website
                        </a>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden"
         style="display: none;">
    </div>
</body>
</html>
