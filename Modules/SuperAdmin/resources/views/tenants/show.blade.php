@extends('superadmin::layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb & Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('super-admin.tenants.index') }}" class="hover:underline">Tenants</a>
                <span>/</span>
                <span>{{ $tenant->name }}</span>
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight">{{ $tenant->name }}</h1>
            <p class="text-xs text-gray-400">Storefront: <a href="{{ url('/store/' . $tenant->slug) }}" target="_blank" class="font-mono font-bold text-emerald-400 hover:underline">{{ url('/store/' . $tenant->slug) }}</a></p>
        </div>

        <div class="flex items-center space-x-3">
            <form action="{{ route('super-admin.tenants.impersonate', $tenant) }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl text-sm font-bold bg-[#0E5E4A] hover:bg-[#0c4e3e] text-white shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    <span>Impersonate Workspace</span>
                </button>
            </form>

            <a href="{{ route('super-admin.tenants.edit', $tenant) }}" class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl text-sm font-semibold border border-gray-300 text-gray-700 hover:bg-white transition">
                <span>Edit Details</span>
            </a>
        </div>
    </div>

    <!-- Quick Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-[#10231C]/10 shadow-sm">
            <span class="text-xs font-bold text-gray-400 uppercase">Status</span>
            <div class="mt-2 flex items-center justify-between">
                <span class="text-xl font-extrabold text-[#10231C] capitalize">{{ $tenant->status }}</span>
                <form action="{{ route('super-admin.tenants.status', $tenant) }}" method="POST">
                    @csrf
                    <button type="submit" class="text-xs font-bold text-[#0E5E4A] hover:underline">
                        {{ $tenant->status === 'active' ? 'Suspend' : 'Activate' }}
                    </button>
                </form>
            </div>
            @if($tenant->isOnTrial())
            <span class="text-xs text-amber-700 block mt-1">Trial ends {{ $tenant->trial_ends_at->format('M d, Y') }}</span>
            @endif
        </div>

        <div class="bg-white p-5 rounded-2xl border border-[#10231C]/10 shadow-sm">
            <span class="text-xs font-bold text-gray-400 uppercase">Subscription Plan</span>
            <div class="mt-2">
                <span class="text-xl font-extrabold text-[#10231C]">{{ $subscription->plan->name ?? 'Free Trial' }}</span>
                <span class="block text-xs text-gray-500 mt-1">
                    ₦{{ number_format($subscription->amount_paid ?? 0, 2) }}/{{ $subscription->plan->billing_interval ?? 'month' }}
                </span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-[#10231C]/10 shadow-sm">
            <span class="text-xs font-bold text-gray-400 uppercase">Active Team Seats</span>
            <div class="mt-2">
                <span class="text-xl font-extrabold text-[#10231C]">{{ $usersCount }}</span>
                <span class="text-xs text-gray-400">/ {{ $subscription->seats_purchased ?? 5 }} purchased</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-[#10231C]/10 shadow-sm">
            <span class="text-xs font-bold text-gray-400 uppercase">AI Workforce Suite</span>
            <div class="mt-2">
                <span class="text-xl font-extrabold {{ $tenant->ai_enabled ? 'text-amber-700' : 'text-gray-400' }}">
                    {{ $tenant->ai_enabled ? 'Enabled' : 'Disabled' }}
                </span>
                <span class="block text-xs text-gray-500 mt-1">Quota: {{ number_format($tenant->ai_monthly_token_quota) }} tokens</span>
            </div>
        </div>
    </div>

    <!-- Trial Actions & Storefront Information -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Storefront & Custom Domain Details -->
        <div class="bg-white p-6 rounded-2xl border border-[#10231C]/10 shadow-sm lg:col-span-2 space-y-4">
            <h2 class="text-base font-bold text-[#10231C]">Digital Storefront & Public Access</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="p-4 rounded-xl bg-white/5 border border-white/10">
                    <span class="text-xs font-bold text-gray-400 block uppercase">Storefront URL</span>
                    <a href="{{ url('/store/' . $tenant->slug) }}" target="_blank" class="text-sm font-mono font-bold text-emerald-400 hover:underline mt-1 block">
                        {{ url('/store/' . $tenant->slug) }}
                    </a>
                    <span class="text-[11px] text-gray-400 mt-1 block">Live storefront catalog and order routing.</span>
                </div>

                <div class="p-4 rounded-xl bg-white/5 border border-white/10">
                    <span class="text-xs font-bold text-gray-400 block uppercase">Custom Domain</span>
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
            <div class="pt-4 border-t border-white/10">
                <h3 class="text-sm font-bold text-white mb-2">Extend Subscription Period</h3>
                <form action="{{ route('super-admin.tenants.extend-trial', $tenant) }}" method="POST" class="flex items-center space-x-3">
                    @csrf
                    <select name="days" class="text-sm rounded-xl border border-gray-300 px-3 py-2 bg-[#F6F4EC]/40">
                        <option value="7">+7 Days</option>
                        <option value="14" selected>+14 Days</option>
                        <option value="30">+30 Days</option>
                        <option value="60">+60 Days</option>
                    </select>
                    <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold bg-[#10231C] text-white hover:bg-[#1f3f33]">
                        Apply Extension
                    </button>
                </form>
            </div>
        </div>

        <!-- Tenant Contact & Quick Details -->
        <div class="bg-white p-6 rounded-2xl border border-[#10231C]/10 shadow-sm space-y-3">
            <h2 class="text-base font-bold text-[#10231C]">Business Profile</h2>
            <div class="text-xs space-y-2">
                <div>
                    <span class="text-gray-400 block font-semibold">Primary Contact Email</span>
                    <span class="text-gray-800 font-bold">{{ $tenant->email }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block font-semibold">Phone</span>
                    <span class="text-gray-800">{{ $tenant->phone ?? 'Not provided' }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block font-semibold">Base Currency</span>
                    <span class="text-gray-800 font-bold">{{ $tenant->currency }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block font-semibold">Registered On</span>
                    <span class="text-gray-800">{{ $tenant->created_at->format('M d, Y h:ia') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
