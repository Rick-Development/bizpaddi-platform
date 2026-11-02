<!DOCTYPE html>
<html lang="en" class="h-full bg-[#08110e]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/bizpaddi/icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/bizpaddi/icon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/bizpaddi/icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            primary: '#0E5E4A',
                            secondary: '#10231C',
                            amber: '#F2A900',
                            highlight: '#F2A900',
                            surface: '#10231C',
                            dark: '#08110e',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.15); border-radius: 4px; }
    </style>
</head>
<body class="h-full font-sans antialiased text-white bg-[#08110e]" x-data="{ mobileSidebarOpen: false }">
    <div class="min-h-full flex">
        <!-- ================= SIDEBAR ================= -->
        <!-- Mobile Backdrop -->
        <div x-show="mobileSidebarOpen" 
             @click="mobileSidebarOpen = false" 
             x-cloak 
             class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm lg:hidden transition-opacity"></div>

        <!-- Sidebar Container -->
        <aside :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed inset-y-0 left-0 z-50 w-72 bg-[#10231C] text-white flex flex-col justify-between border-r border-white/10 transition-transform duration-300 ease-in-out">
            
            <!-- Sidebar Top: Brand Logo -->
            <div class="p-6 border-b border-white/10 flex items-center justify-between">
                <a href="{{ auth('admin')->user() && auth('admin')->user()->role === 'super_admin' ? route('super-admin.dashboard') : url('/admin') }}" class="flex items-center space-x-3">
                    <img src="{{ asset('images/bizpaddi/logo.png') }}" alt="Bizpaddi" class="h-8 w-auto">
                </a>
                <span class="text-[10px] uppercase tracking-widest font-black px-2 py-0.5 rounded bg-[#F2A900] text-[#10231C] shadow-sm">
                    Super Admin
                </span>
            </div>

            <!-- Sidebar Navigation Links -->
            <div class="flex-1 overflow-y-auto py-6 px-4 space-y-6 sidebar-scroll">
                <div>
                    <span class="px-3 text-[10px] font-extrabold tracking-widest uppercase text-gray-400 block mb-2">Platform Management</span>
                    <nav class="space-y-1">
                        <!-- Dashboard -->
                        <a href="{{ route('super-admin.dashboard') }}" 
                           class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition {{ request()->routeIs('super-admin.dashboard') || request()->is('super-admin') ? 'bg-[#0E5E4A] text-white shadow-md border border-emerald-400/30 font-bold' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('super-admin.dashboard') || request()->is('super-admin') ? 'text-[#F2A900]' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            <span>Dashboard</span>
                        </a>

                        <!-- Businesses -->
                        <a href="{{ route('super-admin.tenants.index') }}" 
                           class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition {{ request()->is('*tenants*') ? 'bg-[#0E5E4A] text-white shadow-md border border-emerald-400/30 font-bold' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                            <svg class="w-5 h-5 {{ request()->is('*tenants*') ? 'text-[#F2A900]' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span>Businesses</span>
                        </a>

                        <!-- Subscription Plans -->
                        <a href="{{ route('super-admin.plans.index') }}" 
                           class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition {{ request()->is('*plans*') ? 'bg-[#0E5E4A] text-white shadow-md border border-emerald-400/30 font-bold' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                            <svg class="w-5 h-5 {{ request()->is('*plans*') ? 'text-[#F2A900]' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            <span>Subscription Plans</span>
                        </a>

                        <!-- Billway Transactions -->
                        <a href="{{ route('super-admin.transactions.index') }}" 
                           class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition {{ request()->is('*transactions*') ? 'bg-[#0E5E4A] text-white shadow-md border border-emerald-400/30 font-bold' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                            <svg class="w-5 h-5 {{ request()->is('*transactions*') ? 'text-[#F2A900]' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            <span>Payment Transactions</span>
                        </a>

                        <!-- Platform Team -->
                        <a href="{{ route('super-admin.administrators.index') }}" 
                           class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition {{ request()->is('*administrators*') ? 'bg-[#0E5E4A] text-white shadow-md border border-emerald-400/30 font-bold' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                            <svg class="w-5 h-5 {{ request()->is('*administrators*') ? 'text-[#F2A900]' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <span>Platform Team</span>
                        </a>
                    </nav>
                </div>

                <div>
                    <span class="px-3 text-[10px] font-extrabold tracking-widest uppercase text-gray-400 block mb-2">Content & Configuration</span>
                    <nav class="space-y-1">
                        <!-- Website CMS -->
                        <a href="{{ route('super-admin.website-content.index') }}" 
                           class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition {{ request()->is('*website-content*') ? 'bg-[#0E5E4A] text-white shadow-md border border-emerald-400/30 font-bold' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                            <svg class="w-5 h-5 {{ request()->is('*website-content*') ? 'text-[#F2A900]' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            <span>Website </span>
                        </a>

                        <!-- Settings -->
                        @if(auth('admin')->user() && auth('admin')->user()->role === 'super_admin')
                        <a href="{{ route('super-admin.settings.index') }}" 
                           class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition {{ request()->routeIs('super-admin.settings.*') ? 'bg-[#0E5E4A] text-white shadow-md border border-emerald-400/30 font-bold' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('super-admin.settings.*') ? 'text-[#F2A900]' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Platform Settings</span>
                        </a>

                        <!-- Rate Limiting -->
                        <a href="{{ route('admin.rate-limits.index') }}" 
                           class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition {{ request()->routeIs('admin.rate-limits.*') ? 'bg-[#0E5E4A] text-white shadow-md border border-emerald-400/30 font-bold' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.rate-limits.*') ? 'text-[#F2A900]' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.249-8.25-3.286zm0 13.036h.008v.008H12v-.008z"/></svg>
                            <span>Rate Limiting</span>
                        </a>

                        <!-- System Status & Logs -->
                        <a href="{{ route('admin.system.index') }}" 
                           class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition {{ request()->routeIs('admin.system.*') ? 'bg-[#0E5E4A] text-white shadow-md border border-emerald-400/30 font-bold' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.system.*') ? 'text-[#F2A900]' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25"/></svg>
                            <span>System Status</span>
                        </a>
                        @endif
                    </nav>
                </div>

                <div>
                    <span class="px-3 text-[10px] font-extrabold tracking-widest uppercase text-gray-400 block mb-2">Shortcuts</span>
                    <nav class="space-y-1">
                        <!-- Switch to Business Workspace -->
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-bold text-amber-300 hover:text-white bg-amber-400/10 hover:bg-amber-400/20 border border-amber-400/20 transition">
                            <span class="flex items-center space-x-2">
                                <span>🏢</span>
                                <span>Business Workspace</span>
                            </span>
                            <span>&rarr;</span>
                        </a>

                        <!-- Public Website -->
                        <a href="{{ url('/') }}" target="_blank"
                           class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-gray-400 hover:text-white hover:bg-white/5 transition">
                            <span class="flex items-center space-x-2">
                                <span>🌐</span>
                                <span>Live Homepage</span>
                            </span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Sidebar Bottom: Super Admin Profile Card & Logout -->
            <div class="p-4 border-t border-white/10 bg-[#0a1612]">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3 overflow-hidden">
                        <div class="w-9 h-9 rounded-xl bg-[#0E5E4A] border border-emerald-400/30 flex items-center justify-center font-black text-xs text-white">
                            {{ strtoupper(substr(auth('admin')->user()->name ?? 'SA', 0, 2)) }}
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-xs font-bold text-white truncate">{{ auth('admin')->user()->name ?? 'Super Admin' }}</p>
                            <p class="text-[10px] text-gray-400 truncate">Platform Owner HQ</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ auth('admin')->user() && auth('admin')->user()->role === 'super_admin' ? route('super-admin.logout') : route('platform-admin.logout') }}">
                        @csrf
                        <button type="submit" class="p-2 text-gray-400 hover:text-red-400 rounded-lg hover:bg-white/10 transition" title="Log Out">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- ================= MAIN CONTENT AREA ================= -->
        <div class="flex-1 lg:pl-72 flex flex-col min-h-screen bg-[#08110e]">
            <!-- Top Mobile Bar & Global Header -->
            <header class="bg-[#10231C]/90 backdrop-blur-md border-b border-white/10 sticky top-0 z-30 shadow-md">
                <div class="px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                    <!-- Mobile Hamburger Toggle -->
                    <div class="flex items-center space-x-3">
                        <button type="button" 
                                @click="mobileSidebarOpen = !mobileSidebarOpen" 
                                class="p-2 text-gray-300 hover:text-white lg:hidden rounded-lg hover:bg-white/10 transition focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                        <div class="hidden sm:flex items-center space-x-2 text-xs font-semibold text-gray-400">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span class="text-gray-300">Platform Engine Online</span>
                            <span class="text-white/20">•</span>
                            <span class="text-white font-bold">Multi-Tenant HQ</span>
                        </div>
                    </div>

                    <!-- Right Quick Actions -->
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('super-admin.plans.create') }}" 
                           class="hidden sm:inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-[#F2A900] hover:bg-amber-400 text-[#10231C] shadow-sm transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            <span>New Plan</span>
                        </a>
                        <a href="{{ route('admin.dashboard') }}" 
                           class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-[#0E5E4A] hover:bg-[#0c4e3e] text-white shadow-sm border border-emerald-400/20 transition">
                            <span>Business Workspace</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>
            </header>

            <!-- Flash Alerts -->
            <div class="px-4 sm:px-6 lg:px-8 pt-4">
                @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl text-xs sm:text-sm font-medium flex items-center justify-between shadow-xs mb-4">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold ml-2">&times;</button>
                </div>
                @endif

                @if(session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl text-xs sm:text-sm font-medium flex items-center justify-between shadow-xs mb-4">
                    <span>{{ session('error') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 font-bold ml-2">&times;</button>
                </div>
                @endif
            </div>

            <!-- Page Content -->
            <main class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="border-t border-white/10 py-6 px-4 sm:px-6 lg:px-8 text-center text-xs text-gray-400 bg-[#08110e]">
                &copy; {{ date('Y') }} Bizpaddi Platform HQ. Multi-Tenant SaaS Architecture.
            </footer>
        </div>
    </div>
</body>
</html>
