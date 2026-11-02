@extends('superadmin::layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">Businesses & Tenants</h1>
            <p class="text-sm text-gray-400">All companies onboarded onto the Bizpaddi platform.</p>
        </div>
        <a href="{{ route('super-admin.tenants.create') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl text-sm font-bold bg-[#0E5E4A] hover:bg-[#0c4e3e] text-white shadow-lg shadow-emerald-950/40 border border-emerald-400/30 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Onboard Business</span>
        </a>
    </div>

    <!-- Filters & Search -->
    <div class="bg-[#10231C]/90 backdrop-blur-md rounded-2xl p-4 shadow-xl border border-white/10 flex flex-col md:flex-row gap-3 items-center justify-between">
        <form method="GET" action="{{ route('super-admin.tenants.index') }}" class="flex-1 flex flex-wrap gap-3 w-full">
            <div class="relative flex-1 min-w-[240px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, or subdomain slug..."
                       class="w-full pl-10 pr-4 py-2 text-sm rounded-xl border border-white/10 focus:outline-none focus:ring-2 focus:ring-[#0E5E4A] bg-white/5 text-white placeholder-gray-500">
                <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <select name="status" class="text-sm rounded-xl border border-white/10 px-3 py-2 bg-[#10231C] text-white focus:ring-2 focus:ring-[#0E5E4A]">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="trialing" {{ request('status') === 'trialing' ? 'selected' : '' }}>Trialing</option>
                <option value="past_due" {{ request('status') === 'past_due' ? 'selected' : '' }}>Past Due</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
            </select>

            <button type="submit" class="px-4 py-2 rounded-xl text-sm font-semibold bg-[#0E5E4A] text-white hover:bg-[#0c4e3e] border border-emerald-400/30 transition">
                Filter
            </button>
            @if(request()->hasAny(['search', 'status']))
            <a href="{{ route('super-admin.tenants.index') }}" class="px-3 py-2 rounded-xl text-sm font-medium text-gray-400 hover:text-white flex items-center">
                Clear
            </a>
            @endif
        </form>
    </div>

    <!-- Tenants Table -->
    <div class="bg-[#10231C]/90 backdrop-blur-md rounded-2xl shadow-xl border border-white/10 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-white/5 border-b border-white/10 text-xs font-bold text-gray-400 uppercase">
                    <tr>
                        <th class="py-3 px-6">Business</th>
                        <th class="py-3 px-6">Subdomain / Storefront</th>
                        <th class="py-3 px-6">Subscription Plan</th>
                        <th class="py-3 px-6">Active Seats</th>
                        <th class="py-3 px-6">AI Suite</th>
                        <th class="py-3 px-6">Status</th>
                        <th class="py-3 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($tenants as $tenant)
                    <tr class="hover:bg-white/[0.03] transition">
                        <td class="py-4 px-6">
                            <a href="{{ route('super-admin.tenants.show', $tenant) }}" class="font-bold text-white hover:text-emerald-400">
                                {{ $tenant->name }}
                            </a>
                            <span class="text-xs text-gray-400 block">{{ $tenant->email }}</span>
                        </td>
                        <td class="py-4 px-6">
                            @if($tenant->custom_domain)
                            <a href="https://{{ $tenant->custom_domain }}" target="_blank" class="text-xs font-mono font-semibold px-2 py-0.5 rounded bg-emerald-500/10 text-emerald-300 border border-emerald-500/20 hover:underline inline-block">
                                🌐 {{ $tenant->custom_domain }}
                            </a>
                            @else
                            <a href="{{ url('/store/' . $tenant->slug) }}" target="_blank" class="text-xs font-mono font-semibold px-2 py-0.5 rounded bg-white/10 text-emerald-300 border border-white/10 hover:underline inline-block">
                                /store/{{ $tenant->slug }}
                            </a>
                            @endif
                            <span class="block text-[10px] text-gray-400 mt-1">
                                {{ $tenant->hasCustomDomainAccess() ? 'Subdomain Allowed' : 'Plan: Standard Path' }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="font-semibold text-gray-200">
                                {{ $tenant->activeSubscription->plan->name ?? 'Standard Plan' }}
                            </span>
                            @if($tenant->activeSubscription)
                            <span class="text-xs text-gray-400 block">
                                Expires {{ $tenant->activeSubscription->current_period_end ? $tenant->activeSubscription->current_period_end->format('M d, Y') : 'N/A' }}
                            </span>
                            @endif
                        </td>
                        <td class="py-4 px-6">
                            <span class="font-bold text-white">{{ $tenant->users->count() }}</span>
                            <span class="text-xs text-gray-400">/ {{ $tenant->activeSubscription->seats_purchased ?? 5 }} seats</span>
                        </td>
                        <td class="py-4 px-6">
                            @if($tenant->ai_enabled)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                Enabled
                            </span>
                            @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-white/5 text-gray-400 border border-white/10">
                                Disabled
                            </span>
                            @endif
                        </td>
                        <td class="py-4 px-6">
                            @if($tenant->status === 'active')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Active</span>
                            @elseif($tenant->status === 'trialing')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">Pending</span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-500/20 text-red-300 border border-red-500/30">{{ ucfirst($tenant->status) }}</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right space-x-2">
                            <!-- Impersonate Workspace -->
                            <form action="{{ route('super-admin.tenants.impersonate', $tenant) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-[#0E5E4A] text-white hover:bg-[#0c4e3e] shadow-sm border border-emerald-400/20 transition" title="Log into this business portal">
                                    Manage
                                </button>
                            </form>

                            <a href="{{ route('super-admin.tenants.show', $tenant) }}" class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold border border-white/10 text-gray-300 hover:text-white hover:bg-white/5">
                                View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-500">No businesses match the search criteria.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tenants->hasPages())
        <div class="px-6 py-4 border-t border-white/10">
            {{ $tenants->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
