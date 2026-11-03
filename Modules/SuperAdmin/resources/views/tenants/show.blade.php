@extends('superadmin::layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb & Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-white/10">
        <div>
            <div class="flex items-center space-x-2 text-xs text-gray-400 mb-1">
                <a href="{{ route('super-admin.tenants.index') }}" class="hover:text-emerald-400 transition">Businesses</a>
                <span>/</span>
                <span class="text-white font-medium">{{ $tenant->name }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">{{ $tenant->name }}</h1>
            <p class="text-xs text-gray-400 mt-1">
                Storefront: <a href="{{ url('/store/' . $tenant->slug) }}" target="_blank" class="font-mono font-bold text-emerald-400 hover:text-emerald-300 hover:underline">{{ url('/store/' . $tenant->slug) }}</a>
            </p>
        </div>

        <div class="flex items-center space-x-3">
            <form action="{{ route('super-admin.tenants.impersonate', $tenant) }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold bg-gradient-to-r from-[#0E5E4A] to-[#10B981] text-white shadow-lg shadow-emerald-950/40 hover:opacity-95 border border-emerald-400/30 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    <span>Impersonate Workspace</span>
                </button>
            </form>

            <a href="{{ route('super-admin.tenants.edit', $tenant) }}" class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold bg-white/5 border border-white/10 text-gray-200 hover:bg-white/10 hover:text-white transition">
                <span>Edit Profile</span>
            </a>
        </div>
    </div>

    <!-- Quick Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Status Card -->
        <div class="bg-[#10231C]/90 backdrop-blur-md p-5 rounded-2xl border border-white/10 shadow-xl flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Account Status</span>
                <form action="{{ route('super-admin.tenants.status', $tenant) }}" method="POST">
                    @csrf
                    <button type="submit" class="text-xs font-bold text-emerald-400 hover:text-emerald-300 transition">
                        {{ $tenant->status === 'active' ? 'Suspend' : 'Activate' }}
                    </button>
                </form>
            </div>
            <div class="mt-4">
                <div class="flex items-center space-x-2">
                    <span class="h-2 w-2 rounded-full {{ $tenant->status === 'active' ? 'bg-emerald-400 animate-pulse' : ($tenant->status === 'trialing' ? 'bg-amber-400' : 'bg-rose-400') }}"></span>
                    <span class="text-2xl font-black text-white capitalize tracking-tight">{{ $tenant->status }}</span>
                </div>
                @if($tenant->isOnTrial())
                <span class="text-xs text-amber-300 block mt-1 font-semibold">Trial ends {{ $tenant->trial_ends_at->format('M d, Y') }}</span>
                @endif
            </div>
        </div>

        <!-- Plan Card -->
        <div class="bg-[#10231C]/90 backdrop-blur-md p-5 rounded-2xl border border-white/10 shadow-xl flex flex-col justify-between">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Subscription Tier</span>
            <div class="mt-4">
                <span class="text-2xl font-black text-white tracking-tight">{{ $subscription->plan->name ?? 'Free Trial' }}</span>
                <span class="block text-xs text-emerald-400 font-semibold mt-1">
                    ₦{{ number_format($subscription->amount_paid ?? 0, 2) }}/{{ $subscription->plan->billing_interval ?? 'month' }}
                </span>
            </div>
        </div>

        <!-- Team Seats Card -->
        <div class="bg-[#10231C]/90 backdrop-blur-md p-5 rounded-2xl border border-white/10 shadow-xl flex flex-col justify-between">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Team Seats Used</span>
            <div class="mt-4">
                <div class="flex items-baseline space-x-2">
                    <span class="text-2xl font-black text-white tracking-tight">{{ $usersCount }}</span>
                    <span class="text-xs text-gray-400 font-medium">/ {{ $subscription->seats_purchased ?? 5 }} allocated</span>
                </div>
                <div class="mt-2 h-1.5 w-full bg-white/10 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500 rounded-full" style="width: {{ min(100, ($usersCount / max(1, $subscription->seats_purchased ?? 5)) * 100) }}%"></div>
                </div>
            </div>
        </div>

        <!-- AI Suite Card -->
        <div class="bg-[#10231C]/90 backdrop-blur-md p-5 rounded-2xl border border-white/10 shadow-xl flex flex-col justify-between">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">AI Workforce</span>
            <div class="mt-4">
                <span class="text-2xl font-black {{ $tenant->ai_enabled ? 'text-amber-300' : 'text-gray-400' }} tracking-tight">
                    {{ $tenant->ai_enabled ? 'Enabled' : 'Disabled' }}
                </span>
                <span class="block text-xs text-gray-400 mt-1">Quota: {{ number_format($tenant->ai_monthly_token_quota) }} tokens</span>
            </div>
        </div>
    </div>

    <!-- Storefront & Public Access Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Storefront & Extension -->
        <div class="bg-[#10231C]/90 backdrop-blur-md p-6 rounded-2xl border border-white/10 shadow-xl lg:col-span-2 space-y-6">
            <div>
                <h2 class="text-base font-bold text-white tracking-tight">Digital Storefront & Public Access</h2>
                <p class="text-xs text-gray-400 mt-0.5">Public storefront routing, checkout configuration, and custom domains.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="p-4 rounded-xl bg-white/5 border border-white/10">
                    <span class="text-[10px] font-bold text-gray-400 block uppercase tracking-wider">Storefront URL</span>
                    <a href="{{ url('/store/' . $tenant->slug) }}" target="_blank" class="text-sm font-mono font-bold text-emerald-400 hover:underline mt-1 block truncate">
                        {{ url('/store/' . $tenant->slug) }}
                    </a>
                    <span class="text-[11px] text-gray-400 mt-1 block">Live storefront catalog and order routing.</span>
                </div>

                <div class="p-4 rounded-xl bg-white/5 border border-white/10">
                    <span class="text-[10px] font-bold text-gray-400 block uppercase tracking-wider">Custom Domain</span>
                    @if($tenant->custom_domain)
                    <span class="text-sm font-mono font-bold text-emerald-300 mt-1 block">
                        https://{{ $tenant->custom_domain }}
                    </span>
                    <span class="text-[11px] text-emerald-400 mt-1 block">Point CNAME / A-Record to server IP</span>
                    @else
                    <span class="text-sm text-gray-400 mt-1 block">None configured</span>
                    <a href="{{ route('super-admin.tenants.edit', $tenant) }}" class="text-[11px] text-emerald-400 font-bold hover:underline">Add Custom Domain</a>
                    @endif
                </div>
            </div>

            <!-- Extend Subscription Form -->
            <div class="pt-5 border-t border-white/10">
                <h3 class="text-sm font-bold text-white mb-1">Grant Subscription Extension</h3>
                <p class="text-xs text-gray-400 mb-3">Add complimentary access days to this tenant's current trial or subscription cycle.</p>
                <form action="{{ route('super-admin.tenants.extend-trial', $tenant) }}" method="POST" class="flex items-center space-x-3">
                    @csrf
                    <select name="days" class="text-xs sm:text-sm rounded-xl border border-white/10 px-3.5 py-2.5 bg-[#0c1a15] text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="7">+7 Days</option>
                        <option value="14" selected>+14 Days</option>
                        <option value="30">+30 Days</option>
                        <option value="60">+60 Days</option>
                    </select>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold bg-[#0E5E4A] hover:bg-[#0c4e3e] text-white border border-emerald-400/30 transition shadow-md">
                        Apply Extension
                    </button>
                </form>
            </div>
        </div>

        <!-- Right: Business Profile Info -->
        <div class="bg-[#10231C]/90 backdrop-blur-md p-6 rounded-2xl border border-white/10 shadow-xl space-y-4">
            <h2 class="text-base font-bold text-white tracking-tight">Business Profile</h2>
            <div class="text-xs space-y-3 divide-y divide-white/5">
                <div class="pt-2 first:pt-0">
                    <span class="text-gray-400 block font-semibold">Primary Contact Email</span>
                    <span class="text-white font-mono font-bold">{{ $tenant->email }}</span>
                </div>
                <div class="pt-2">
                    <span class="text-gray-400 block font-semibold">Phone</span>
                    <span class="text-white">{{ $tenant->phone ?? 'Not provided' }}</span>
                </div>
                <div class="pt-2">
                    <span class="text-gray-400 block font-semibold">Base Currency</span>
                    <span class="text-emerald-400 font-bold">{{ $tenant->currency }}</span>
                </div>
                <div class="pt-2">
                    <span class="text-gray-400 block font-semibold">Registered On</span>
                    <span class="text-gray-300">{{ $tenant->created_at->format('M d, Y h:ia') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
