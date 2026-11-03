@extends('superadmin::layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between pb-4 border-b border-white/10">
        <div>
            <a href="{{ route('super-admin.tenants.show', $tenant) }}" class="text-xs font-bold text-emerald-400 hover:text-emerald-300 transition">&larr; Back to {{ $tenant->name }}</a>
            <h1 class="text-2xl font-black text-white mt-1 tracking-tight">Edit Business Profile</h1>
            <p class="text-xs sm:text-sm text-gray-400">Update business metadata, domain routing, currency, and AI quotas.</p>
        </div>
    </div>

    <form action="{{ route('super-admin.tenants.update', $tenant) }}" method="POST" class="bg-[#10231C]/90 backdrop-blur-md rounded-2xl p-6 sm:p-8 shadow-xl border border-white/10 space-y-5">
        @csrf
        @method('PUT')

        <!-- Company Name -->
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">Company / Business Name <span class="text-rose-400">*</span></label>
            <input type="text" name="name" value="{{ old('name', $tenant->name) }}" required
                   class="w-full px-4 py-2.5 rounded-xl border border-white/10 bg-white/5 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm transition">
            @error('name') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
        </div>

        <!-- Subdomain Slug & Custom Domain -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">Store / Workspace Slug <span class="text-rose-400">*</span></label>
                <div class="flex rounded-xl shadow-sm overflow-hidden border border-white/10 focus-within:ring-2 focus-within:ring-emerald-500">
                    <span class="inline-flex items-center px-3 bg-white/10 text-emerald-400 text-xs font-mono border-r border-white/10">
                        /store/
                    </span>
                    <input type="text" name="slug" value="{{ old('slug', $tenant->slug) }}" required
                           class="flex-1 min-w-0 block w-full px-3 py-2.5 bg-white/5 text-white placeholder-gray-500 text-sm focus:outline-none font-mono">
                </div>
                @error('slug') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">Custom Domain (Optional)</label>
                <input type="text" name="custom_domain" value="{{ old('custom_domain', $tenant->custom_domain) }}" placeholder="e.g. acme.com"
                       class="w-full px-4 py-2.5 rounded-xl border border-white/10 bg-white/5 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm transition font-mono">
            </div>
        </div>

        <!-- Contact & Currency -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">Business Email <span class="text-rose-400">*</span></label>
                <input type="email" name="email" value="{{ old('email', $tenant->email) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-white/10 bg-white/5 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm transition">
                @error('email') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">Base Currency <span class="text-rose-400">*</span></label>
                <select name="currency" class="w-full px-4 py-2.5 rounded-xl border border-white/10 bg-[#0c1a15] text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                    <option value="NGN" {{ $tenant->currency === 'NGN' ? 'selected' : '' }}>NGN (₦)</option>
                    <option value="USD" {{ $tenant->currency === 'USD' ? 'selected' : '' }}>USD ($)</option>
                    <option value="GBP" {{ $tenant->currency === 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                    <option value="EUR" {{ $tenant->currency === 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                </select>
            </div>
        </div>

        <!-- Status -->
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">Account Status <span class="text-rose-400">*</span></label>
            <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-white/10 bg-[#0c1a15] text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                <option value="active" {{ $tenant->status === 'active' ? 'selected' : '' }}>Active (Full Access)</option>
                <option value="trialing" {{ $tenant->status === 'trialing' ? 'selected' : '' }}>Trialing</option>
                <option value="past_due" {{ $tenant->status === 'past_due' ? 'selected' : '' }}>Past Due (Grace Period)</option>
                <option value="suspended" {{ $tenant->status === 'suspended' ? 'selected' : '' }}>Suspended (Read-Only)</option>
                <option value="cancelled" {{ $tenant->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

        <!-- AI Token Quota -->
        <div class="p-4 rounded-xl bg-amber-500/10 border border-amber-500/20 space-y-3">
            <div class="flex items-center space-x-3">
                <input type="checkbox" id="ai_enabled" name="ai_enabled" value="1" {{ $tenant->ai_enabled ? 'checked' : '' }} class="rounded text-[#0E5E4A] focus:ring-[#0E5E4A] w-4 h-4 bg-white/10 border-white/20">
                <label for="ai_enabled" class="text-xs font-bold text-amber-200 cursor-pointer">
                    Enable AI Center & Autonomous AI Workforce
                </label>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-1.5">Monthly AI Token Quota</label>
                <input type="number" name="ai_monthly_token_quota" value="{{ old('ai_monthly_token_quota', $tenant->ai_monthly_token_quota) }}" min="0" step="1000"
                       class="w-full px-4 py-2 rounded-xl border border-white/10 bg-white/5 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                <span class="text-[11px] text-gray-400 mt-1 block">Tokens used this billing cycle: {{ number_format($tenant->ai_tokens_used) }}</span>
            </div>
        </div>

        <div class="pt-4 border-t border-white/10 flex items-center justify-end space-x-3">
            <a href="{{ route('super-admin.tenants.show', $tenant) }}" class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-gray-400 hover:text-white transition">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl text-xs sm:text-sm font-bold bg-gradient-to-r from-[#0E5E4A] to-[#10B981] text-white shadow-lg shadow-emerald-950/40 hover:opacity-95 border border-emerald-400/30 transition">
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
