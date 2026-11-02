@extends('superadmin::layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Welcome Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">Platform Overview</h1>
            <p class="text-sm text-gray-400">Monitor businesses, recurring revenue, and subscription health across Bizpaddi.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('super-admin.tenants.create') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl text-sm font-bold bg-[#0E5E4A] hover:bg-[#0c4e3e] text-white shadow-lg shadow-emerald-950/40 border border-emerald-400/30 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Onboard Business</span>
            </a>
            <a href="{{ route('super-admin.plans.create') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl text-sm font-bold bg-[#F2A900] hover:bg-amber-400 text-[#10231C] shadow-lg shadow-amber-950/40 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                <span>New Pricing Plan</span>
            </a>
        </div>
    </div>

    <!-- Core Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- MRR -->
        <div class="bg-[#10231C]/90 backdrop-blur-md rounded-2xl p-6 shadow-xl border border-white/10 flex flex-col justify-between hover:border-emerald-400/30 transition group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Monthly Recurring Revenue</span>
                <span class="p-2 rounded-xl bg-[#0E5E4A]/30 text-emerald-400 border border-emerald-400/20 group-hover:scale-110 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <div class="mt-4">
                <span class="text-3xl font-extrabold text-white tracking-tight">₦{{ number_format($mrr, 2) }}</span>
                <span class="block text-xs text-gray-400 mt-1">Calculated from active subscriptions</span>
            </div>
        </div>

        <!-- Total Tenants -->
        <div class="bg-[#10231C]/90 backdrop-blur-md rounded-2xl p-6 shadow-xl border border-white/10 flex flex-col justify-between hover:border-amber-400/30 transition group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Registered Businesses</span>
                <span class="p-2 rounded-xl bg-[#F2A900]/20 text-[#F2A900] border border-[#F2A900]/30 group-hover:scale-110 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </span>
            </div>
            <div class="mt-4">
                <div class="flex items-baseline space-x-2">
                    <span class="text-3xl font-extrabold text-white tracking-tight">{{ $totalTenants }}</span>
                    <span class="text-xs font-bold text-emerald-400">({{ $activeTenants }} active)</span>
                </div>
                <span class="block text-xs text-gray-400 mt-1">{{ $trialingTenants }} on trial, {{ $suspendedTenants }} suspended</span>
            </div>
        </div>

        <!-- Total Users (Seats) -->
        <div class="bg-[#10231C]/90 backdrop-blur-md rounded-2xl p-6 shadow-xl border border-white/10 flex flex-col justify-between hover:border-blue-400/30 transition group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Total Active Seats</span>
                <span class="p-2 rounded-xl bg-blue-500/20 text-blue-400 border border-blue-400/30 group-hover:scale-110 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </span>
            </div>
            <div class="mt-4">
                <span class="text-3xl font-extrabold text-white tracking-tight">{{ $totalUsers }}</span>
                <span class="block text-xs text-gray-400 mt-1">Total business employees & staff</span>
            </div>
        </div>

        <!-- Lifetime Collections -->
        <div class="bg-[#10231C]/90 backdrop-blur-md rounded-2xl p-6 shadow-xl border border-white/10 flex flex-col justify-between hover:border-purple-400/30 transition group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Billway Collections</span>
                <span class="p-2 rounded-xl bg-purple-500/20 text-purple-400 border border-purple-400/30 group-hover:scale-110 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <div class="mt-4">
                <span class="text-3xl font-extrabold text-white tracking-tight">₦{{ number_format($totalPayments, 2) }}</span>
                <span class="block text-xs text-gray-400 mt-1">Processed platform revenue</span>
            </div>
        </div>
    </div>

    <!-- Active Plans Quick Overview -->
    <div class="bg-[#10231C]/90 backdrop-blur-md rounded-2xl p-6 shadow-xl border border-white/10">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-lg font-bold text-white tracking-tight">SaaS Pricing Plans & Subscriptions</h2>
                <p class="text-xs text-gray-400">Active tier configurations available to businesses</p>
            </div>
            <a href="{{ route('super-admin.plans.index') }}" class="text-xs font-bold text-emerald-400 hover:text-emerald-300 transition flex items-center space-x-1">
                <span>Manage All Plans</span>
                <span>&rarr;</span>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($plans as $plan)
            <div class="border border-white/10 rounded-xl p-4 flex flex-col justify-between bg-white/[0.03] hover:bg-white/[0.06] hover:border-emerald-500/40 transition">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold px-2 py-0.5 rounded {{ $plan->billing_type === 'per_seat' ? 'bg-[#F2A900]/20 text-[#F2A900] border border-[#F2A900]/30' : 'bg-[#0E5E4A]/30 text-emerald-300 border border-emerald-400/30' }}">
                            {{ $plan->billing_type === 'per_seat' ? 'Per-Seat Model' : 'Flat Tiered' }}
                        </span>
                        <span class="text-xs text-gray-400 font-semibold">{{ $plan->subscriptions_count }} businesses</span>
                    </div>
                    <h3 class="text-base font-bold text-white mt-3">{{ $plan->name }}</h3>
                    <p class="text-xs text-gray-400 line-clamp-2 mt-1">{{ $plan->description }}</p>
                </div>
                <div class="mt-4 pt-3 border-t border-white/10 flex items-baseline justify-between">
                    <div>
                        <span class="text-lg font-extrabold text-white">₦{{ number_format($plan->price, 0) }}</span>
                        <span class="text-xs text-gray-400">/{{ $plan->billing_interval }}</span>
                        @if($plan->billing_type === 'per_seat' && $plan->price_per_user > 0)
                        <span class="block text-[11px] text-emerald-400 font-semibold">+ ₦{{ number_format($plan->price_per_user, 0) }}/seat</span>
                        @endif
                    </div>
                    @if($plan->enable_ai)
                    <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded bg-amber-400/20 text-amber-300 border border-amber-400/30">AI Workforce ✨</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Two-Column Section: Recent Businesses & Billway Payments -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Tenants -->
        <div class="lg:col-span-2 bg-[#10231C]/90 backdrop-blur-md rounded-2xl p-6 shadow-xl border border-white/10">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-white tracking-tight">Recent Businesses</h2>
                <a href="{{ route('super-admin.tenants.index') }}" class="text-xs font-bold text-emerald-400 hover:text-emerald-300 transition">View All &rarr;</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-white/10 text-xs font-bold text-gray-400 uppercase">
                            <th class="pb-3">Business</th>
                            <th class="pb-3">Storefront Slug</th>
                            <th class="pb-3">Plan</th>
                            <th class="pb-3">Status</th>
                            <th class="pb-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($recentTenants as $tenant)
                        <tr class="hover:bg-white/[0.04] transition">
                            <td class="py-3">
                                <span class="font-bold text-white block">{{ $tenant->name }}</span>
                                <span class="text-xs text-gray-400">{{ $tenant->email }}</span>
                            </td>
                            <td class="py-3">
                                @if($tenant->custom_domain)
                                <a href="https://{{ $tenant->custom_domain }}" target="_blank" class="text-xs font-mono font-semibold px-2 py-0.5 rounded bg-emerald-500/10 text-emerald-300 border border-emerald-500/20 hover:underline">
                                    🌐 {{ $tenant->custom_domain }}
                                </a>
                                @else
                                <a href="{{ url('/store/' . $tenant->slug) }}" target="_blank" class="text-xs font-mono font-semibold px-2 py-0.5 rounded bg-white/10 text-emerald-300 border border-white/10 hover:underline">
                                    /store/{{ $tenant->slug }}
                                </a>
                                @endif
                            </td>
                            <td class="py-3 text-xs font-semibold text-gray-300">
                                {{ $tenant->activeSubscription->plan->name ?? 'Standard Plan' }}
                            </td>
                            <td class="py-3">
                                @if($tenant->status === 'active')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Active</span>
                                @elseif($tenant->status === 'trialing')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">Pending</span>
                                @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-red-500/20 text-red-300 border border-red-500/30">{{ ucfirst($tenant->status) }}</span>
                                @endif
                            </td>
                            <td class="py-3 text-right space-x-2">
                                <form action="{{ route('super-admin.tenants.impersonate', $tenant) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs font-bold text-emerald-400 hover:text-emerald-300 transition" title="Open and manage this workspace">
                                        Open Workspace
                                    </button>
                                </form>
                                <a href="{{ route('super-admin.tenants.show', $tenant) }}" class="text-xs font-bold text-gray-400 hover:text-white transition">
                                    Details
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-gray-500 text-xs">No businesses registered yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Billway Transactions -->
        <div class="bg-[#10231C]/90 backdrop-blur-md rounded-2xl p-6 shadow-xl border border-white/10">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-white tracking-tight">Billway Feed</h2>
                <a href="{{ route('super-admin.transactions.index') }}" class="text-xs font-bold text-emerald-400 hover:text-emerald-300 transition">All &rarr;</a>
            </div>

            <div class="space-y-3">
                @forelse($recentPayments as $payment)
                <div class="flex items-center justify-between p-3 rounded-xl bg-white/[0.03] border border-white/10 hover:bg-white/[0.06] transition">
                    <div class="flex items-center space-x-3">
                        <span class="p-2 rounded-lg {{ $payment->status === 'successful' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-red-500/20 text-red-400 border border-red-500/30' }}">
                            @if($payment->status === 'successful')
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            @else
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            @endif
                        </span>
                        <div>
                            <span class="text-xs font-bold text-white block">{{ $payment->tenant->name ?? 'Business' }}</span>
                            <span class="text-[11px] text-gray-400 font-mono">{{ Str::limit($payment->reference, 14) }}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-extrabold text-white block">₦{{ number_format($payment->amount, 2) }}</span>
                        <span class="text-[10px] text-gray-400">{{ $payment->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <p class="text-xs text-center text-gray-500 py-6">No recent subscription payments.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
