@extends('superadmin::layouts.app')

@section('title', 'Security & Rate Limiting')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-white/10">
        <div>
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-emerald-500/10 border border-emerald-500/20 rounded-xl text-emerald-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.249-8.25-3.286zm0 13.036h.008v.008H12v-.008z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">Rate Limiting & Traffic Security</h1>
                    <p class="mt-1 text-xs sm:text-sm text-gray-400">Centralized control for application API throttling, anti-abuse, and route protection.</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('super-admin.rate-limits.sync') }}" class="inline-flex items-center gap-x-2 rounded-xl bg-gradient-to-r from-[#0E5E4A] to-[#10B981] px-4 py-2.5 text-xs sm:text-sm font-bold text-white shadow-lg shadow-emerald-950/40 hover:opacity-95 border border-emerald-400/30 transition-all duration-200">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                Sync Route Registry
            </a>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="relative overflow-hidden rounded-2xl bg-[#10231C]/90 backdrop-blur-md p-5 border border-white/10 shadow-xl group hover:border-emerald-500/40 transition-all duration-300">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Total System Routes</p>
                <div class="p-2.5 rounded-xl bg-white/5 border border-white/10 text-emerald-400 group-hover:scale-110 transition-transform duration-300">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline">
                <p class="text-3xl font-black text-white tracking-tight">{{ $stats['total'] }}</p>
                <span class="ml-2 text-xs font-semibold text-gray-400">endpoints</span>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-2xl bg-[#10231C]/90 backdrop-blur-md p-5 border border-white/10 shadow-xl group hover:border-emerald-500/40 transition-all duration-300">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-emerald-400">Active Protections</p>
                <div class="p-2.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 group-hover:scale-110 transition-transform duration-300">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline">
                <p class="text-3xl font-black text-emerald-400 tracking-tight">{{ $stats['active'] }}</p>
                <span class="ml-2 text-xs font-semibold text-emerald-500/80">enforced</span>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-2xl bg-[#10231C]/90 backdrop-blur-md p-5 border border-white/10 shadow-xl group hover:border-amber-500/40 transition-all duration-300">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-amber-400">Unprotected Routes</p>
                <div class="p-2.5 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 group-hover:scale-110 transition-transform duration-300">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline">
                <p class="text-3xl font-black text-amber-300 tracking-tight">{{ $stats['disabled'] }}</p>
                <span class="ml-2 text-xs font-semibold text-amber-500/80">bypassed</span>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-2xl bg-[#10231C]/90 backdrop-blur-md p-5 border border-white/10 shadow-xl group hover:border-emerald-500/40 transition-all duration-300">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Route Categories</p>
                <div class="p-2.5 rounded-xl bg-white/5 border border-white/10 text-[#F2A900] group-hover:scale-110 transition-transform duration-300">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H18a2.25 2.25 0 01-2.25-2.25v-2.25z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline">
                <p class="text-3xl font-black text-white tracking-tight">{{ count($stats['categories']) }}</p>
                <span class="ml-2 text-xs font-semibold text-gray-400">groups</span>
            </div>
        </div>
    </div>

    <!-- Filter Tabs & Route Registry -->
    <div x-data="{ 
        activeTab: 'all',
        search: '',
        categories: {{ json_encode($stats['categories']->keys()) }}
    }" class="space-y-4">
        
        <!-- Filter Tabs & Search Bar -->
        <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4 bg-[#10231C]/90 backdrop-blur-md p-4 rounded-2xl border border-white/10 shadow-xl">
            <nav class="flex space-x-2 overflow-x-auto pb-1 md:pb-0 sidebar-scroll" aria-label="Tabs">
                <button @click="activeTab = 'all'" 
                        :class="activeTab === 'all' ? 'bg-[#0E5E4A] text-white shadow-md border border-emerald-400/30' : 'text-gray-400 hover:text-white hover:bg-white/5 border border-transparent'" 
                        class="rounded-xl px-4 py-2 text-xs font-bold transition-all duration-200 whitespace-nowrap">
                    All Routes ({{ $stats['total'] }})
                </button>
                <template x-for="cat in categories" :key="cat">
                    <button @click="activeTab = cat" 
                            :class="activeTab === cat ? 'bg-[#0E5E4A] text-white shadow-md border border-emerald-400/30' : 'text-gray-400 hover:text-white hover:bg-white/5 border border-transparent'" 
                            class="rounded-xl px-4 py-2 text-xs font-bold transition-all duration-200 whitespace-nowrap" 
                            x-text="cat"></button>
                </template>
            </nav>
            
            <div class="relative w-full md:w-72">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="text" x-model="search" placeholder="Search route name or URI..." 
                       class="block w-full rounded-xl border border-white/10 py-2 pl-9 pr-3 text-xs sm:text-sm text-white placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white/5 transition-all duration-200">
            </div>
        </div>

        <!-- Main Table -->
        <div class="bg-[#10231C]/90 backdrop-blur-md rounded-2xl shadow-xl border border-white/10 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/5">
                    <thead>
                        <tr class="bg-white/[0.02]">
                            <th scope="col" class="py-4 pl-6 pr-3 text-left text-xs font-bold uppercase tracking-wider text-gray-400">Category</th>
                            <th scope="col" class="px-3 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-400">Route & Endpoint URI</th>
                            <th scope="col" class="px-3 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-400">Rate Ceiling</th>
                            <th scope="col" class="px-3 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-400">Shield Status</th>
                            <th scope="col" class="relative py-4 pl-3 pr-6 text-right text-xs font-bold uppercase tracking-wider text-gray-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 bg-transparent">
                        @forelse($rateLimits as $limit)
                        <tr x-show="(activeTab === 'all' || activeTab === '{{ $limit->category }}') && ('{{ strtolower($limit->route_name) }}'.includes(search.toLowerCase()) || '{{ strtolower($limit->route_path) }}'.includes(search.toLowerCase()))" 
                            class="hover:bg-white/[0.04] transition-colors duration-150 group">
                            <td class="whitespace-nowrap py-4 pl-6 pr-3">
                                <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-bold {{ 
                                    $limit->category === 'Admin' ? 'bg-purple-500/20 text-purple-300 border border-purple-500/30' : 
                                    ($limit->category === 'Frontend' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 
                                    ($limit->category === 'API' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 
                                    'bg-cyan-500/20 text-cyan-300 border border-cyan-500/30'))
                                }}">
                                    {{ $limit->category }}
                                </span>
                            </td>
                            <td class="px-3 py-4">
                                <div class="flex flex-col max-w-md">
                                    <div class="text-sm font-bold text-white group-hover:text-emerald-300 transition-colors truncate">
                                        {{ $limit->route_name }}
                                    </div>
                                    <div class="text-xs text-gray-400 font-mono truncate mt-1 bg-white/5 border border-white/10 px-2 py-0.5 rounded-md w-fit">
                                        {{ $limit->route_path }}
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm">
                                <div class="flex items-center gap-x-1.5">
                                    <span class="font-black text-white">{{ $limit->max_attempts }} req</span>
                                    <span class="text-gray-500">/</span>
                                    <span class="text-gray-400 text-xs">{{ $limit->decay_minutes }} min</span>
                                </div>
                                <div class="mt-1.5 h-1.5 w-20 bg-white/10 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-emerald-500 to-[#F2A900] rounded-full" style="width: {{ min(100, ($limit->max_attempts / 120) * 100) }}%"></div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4">
                                @if($limit->is_enabled)
                                    <span class="inline-flex items-center gap-x-1.5 rounded-full bg-emerald-500/15 px-3 py-1 text-xs font-bold text-emerald-300 border border-emerald-500/30 shadow-sm">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                        Shielded
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-x-1.5 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-gray-400 border border-white/10">
                                        <span class="h-1.5 w-1.5 rounded-full bg-gray-500"></span>
                                        Bypassed
                                    </span>
                                @endif
                            </td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-6 text-right">
                                <div class="flex items-center justify-end gap-x-2">
                                    <a href="{{ route('super-admin.rate-limits.edit', $limit) }}" 
                                       class="inline-flex items-center rounded-xl bg-white/5 px-3 py-1.5 text-xs font-bold text-emerald-300 border border-white/10 hover:bg-emerald-500/20 hover:border-emerald-500/30 transition-all duration-200">
                                        Configure
                                    </a>
                                    <form action="{{ route('super-admin.rate-limits.destroy', $limit) }}" method="POST" class="inline" onsubmit="return confirm('Remove route from rate limiting registry?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Remove route" class="p-1.5 text-gray-500 hover:text-rose-400 hover:bg-rose-500/10 rounded-xl transition-all duration-200">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-16">
                                <div class="flex flex-col items-center justify-center text-center">
                                    <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl mb-4 text-emerald-400">
                                        <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                        </svg>
                                    </div>
                                    <h3 class="text-base font-bold text-white">No Routes Found</h3>
                                    <p class="mt-1 text-xs text-gray-400 max-w-sm">Synchronize all available application routes to scan routes and enable rate limit controls.</p>
                                    <a href="{{ route('super-admin.rate-limits.sync') }}" class="mt-4 inline-flex items-center rounded-xl bg-gradient-to-r from-[#0E5E4A] to-[#10B981] px-4 py-2 text-xs font-bold text-white shadow-lg shadow-emerald-950/40">
                                        Sync Routes Now
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
