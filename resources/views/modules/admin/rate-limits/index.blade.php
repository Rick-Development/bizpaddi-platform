@extends('admin::layouts.app')

@section('title', 'Security & Rate Limiting')

@section('content')
<div class="mb-8 sm:flex sm:items-center sm:justify-between">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900 flex items-center gap-x-3">
            <div class="p-2 bg-indigo-100 rounded-lg">
                <svg class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.249-8.25-3.286zm0 13.036h.008v.008H12v-.008z" />
                </svg>
            </div>
            Rate Limiting Dashboard
        </h1>
        <p class="mt-2 text-sm text-gray-600">Centralized control for application traffic security and protection.</p>
    </div>
    <div class="mt-4 sm:ml-16 sm:mt-0">
        <a href="{{ route('admin.rate-limits.sync') }}" class="inline-flex items-center gap-x-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all duration-200">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
            </svg>
            Sync All Routes
        </a>
    </div>
</div>

<!-- Stats Section -->
<div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-10">
    <div class="relative overflow-hidden rounded-2xl bg-white px-4 pb-12 pt-5 shadow sm:px-6 sm:pt-6 group hover:shadow-lg transition-shadow duration-300">
        <dt>
            <div class="absolute rounded-xl bg-indigo-500 p-3 group-hover:scale-110 transition-transform duration-300">
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                </svg>
            </div>
            <p class="ml-16 truncate text-sm font-medium text-gray-500">Total System Routes</p>
        </dt>
        <dd class="ml-16 flex items-baseline pb-6 sm:pb-7">
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </dd>
    </div>

    <div class="relative overflow-hidden rounded-2xl bg-white px-4 pb-12 pt-5 shadow sm:px-6 sm:pt-6 group hover:shadow-lg transition-shadow duration-300">
        <dt>
            <div class="absolute rounded-xl bg-green-500 p-3 group-hover:scale-110 transition-transform duration-300">
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="ml-16 truncate text-sm font-medium text-gray-500">Active Protections</p>
        </dt>
        <dd class="ml-16 flex items-baseline pb-6 sm:pb-7">
            <p class="text-2xl font-bold text-gray-900">{{ $stats['active'] }}</p>
        </dd>
    </div>

    <div class="relative overflow-hidden rounded-2xl bg-white px-4 pb-12 pt-5 shadow sm:px-6 sm:pt-6 group hover:shadow-lg transition-shadow duration-300">
        <dt>
            <div class="absolute rounded-xl bg-amber-500 p-3 group-hover:scale-110 transition-transform duration-300">
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>
            <p class="ml-16 truncate text-sm font-medium text-gray-500">Unprotected Routes</p>
        </dt>
        <dd class="ml-16 flex items-baseline pb-6 sm:pb-7">
            <p class="text-2xl font-bold text-gray-900">{{ $stats['disabled'] }}</p>
        </dd>
    </div>

    <div class="relative overflow-hidden rounded-2xl bg-white px-4 pb-12 pt-5 shadow sm:px-6 sm:pt-6 group hover:shadow-lg transition-shadow duration-300">
        <dt>
            <div class="absolute rounded-xl bg-blue-500 p-3 group-hover:scale-110 transition-transform duration-300">
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H18a2.25 2.25 0 01-2.25-2.25v-2.25z" />
                </svg>
            </div>
            <p class="ml-16 truncate text-sm font-medium text-gray-500">Categories</p>
        </dt>
        <dd class="ml-16 flex items-baseline pb-6 sm:pb-7">
            <p class="text-2xl font-bold text-gray-900">{{ count($stats['categories']) }} Groups</p>
        </dd>
    </div>
</div>

<div x-data="{ 
    activeTab: 'all',
    search: '',
    categories: {{ json_encode($stats['categories']->keys()) }}
}" class="space-y-6">
    
    <!-- Filter Tabs & Search -->
    <div class="sm:flex sm:items-center sm:justify-between bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
        <nav class="flex space-x-2 overflow-x-auto pb-2 sm:pb-0" aria-label="Tabs">
            <button @click="activeTab = 'all'" :class="activeTab === 'all' ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100'" class="rounded-xl px-4 py-2 text-sm font-medium transition-all duration-200">
                All Routes
            </button>
            <template x-for="cat in categories" :key="cat">
                <button @click="activeTab = cat" :class="activeTab === cat ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100'" class="rounded-xl px-4 py-2 text-sm font-medium transition-all duration-200" x-text="cat"></button>
            </template>
        </nav>
        
        <div class="mt-4 sm:ml-4 sm:mt-0 relative flex-1 max-w-xs">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                </svg>
            </div>
            <input type="text" x-model="search" placeholder="Search routes..." class="block w-full rounded-xl border-0 py-2 pl-10 text-gray-900 ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all duration-200 bg-gray-50 focus:bg-white">
        </div>
    </div>

    <!-- Main Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th scope="col" class="py-4 pl-6 pr-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Categorization</th>
                        <th scope="col" class="px-3 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Registry & Path</th>
                        <th scope="col" class="px-3 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Security Limit</th>
                        <th scope="col" class="px-3 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Status</th>
                        <th scope="col" class="relative py-4 pl-3 pr-6">
                            <span class="sr-only">Control</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($rateLimits as $limit)
                    <tr x-show="(activeTab === 'all' || activeTab === '{{ $limit->category }}') && ('{{ strtolower($limit->route_name) }}'.includes(search.toLowerCase()) || '{{ strtolower($limit->route_path) }}'.includes(search.toLowerCase()))" 
                        class="hover:bg-indigo-50/30 transition-colors duration-150 group">
                        <td class="whitespace-nowrap py-5 pl-6 pr-3">
                            <div class="flex flex-col">
                                <span class="inline-flex w-fit items-center rounded-lg px-2.5 py-1 text-xs font-bold ring-1 ring-inset {{ 
                                    $limit->category === 'Admin' ? 'bg-purple-50 text-purple-700 ring-purple-600/20' : 
                                    ($limit->category === 'Frontend' ? 'bg-green-50 text-green-700 ring-green-600/20' : 
                                    ($limit->category === 'API' ? 'bg-yellow-50 text-yellow-700 ring-yellow-600/20' : 
                                    'bg-blue-50 text-blue-700 ring-blue-600/20'))
                                }}">
                                    {{ $limit->category }}
                                </span>
                            </div>
                        </td>
                        <td class="px-3 py-5">
                            <div class="flex flex-col max-w-xs">
                                <div class="text-sm font-semibold text-gray-900 truncate group-hover:text-indigo-600 transition-colors">{{ $limit->route_name }}</div>
                                <div class="text-xs text-gray-500 font-mono truncate mt-1 bg-gray-50 border border-gray-100 px-1.5 py-0.5 rounded shadow-sm w-fit group-hover:bg-white transition-colors">{{ $limit->route_path }}</div>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-600">
                            <div class="flex items-center gap-x-1.5">
                                <span class="font-bold text-gray-900">{{ $limit->max_attempts }}</span>
                                <span class="text-gray-400">/</span>
                                <span class="text-gray-600">{{ $limit->decay_minutes }} min</span>
                            </div>
                            <div class="mt-1 h-1.5 w-16 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-indigo-400 rounded-full" style="width: {{ min(100, ($limit->max_attempts / 100) * 100) }}%"></div>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-5">
                            @if($limit->is_enabled)
                                <span class="inline-flex items-center gap-x-1.5 rounded-full bg-green-100 px-2.5 py-1 text-xs font-bold text-green-700 shadow-sm border border-green-200">
                                    <span class="h-1.5 w-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                    Shielded
                                </span>
                            @else
                                <span class="inline-flex items-center gap-x-1.5 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-bold text-gray-600 shadow-sm border border-gray-200">
                                    <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                    Bypassed
                                </span>
                            @endif
                        </td>
                        <td class="relative whitespace-nowrap py-5 pl-3 pr-6 text-right text-sm font-semibold">
                            <div class="flex items-center justify-end gap-x-3">
                                <a href="{{ route('admin.rate-limits.edit', $limit) }}" class="inline-flex items-center rounded-xl bg-white px-3 py-1.5 text-xs font-bold text-indigo-600 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-indigo-50 hover:ring-indigo-300 transition-all duration-200">
                                    Manage
                                </a>
                                <form action="{{ route('admin.rate-limits.destroy', $limit) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to remove this route registry? It will be re-synced if you click Sync Routes later.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all duration-200">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-20">
                            <div class="flex flex-col items-center justify-center text-center">
                                <div class="p-4 bg-indigo-50 rounded-full mb-4">
                                    <svg class="h-12 w-12 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">No Routes Found</h3>
                                <p class="mt-1 text-sm text-gray-500 max-w-xs mx-auto">Click the button below to synchronize all available application routes and start protecting your system.</p>
                                <a href="{{ route('admin.rate-limits.sync') }}" class="mt-6 inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
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

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
