@extends('superadmin::layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <a href="{{ route('super-admin.tenants.show', $tenant) }}" class="text-xs font-bold text-[#0E5E4A] hover:underline">&larr; Back to {{ $tenant->name }}</a>
        <h1 class="text-2xl font-extrabold text-[#10231C] mt-2">Edit Business Profile</h1>
        <p class="text-sm text-[#10231C]/70">Update tenant metadata, domain routing, and quota limits.</p>
    </div>

    <form action="{{ route('super-admin.tenants.update', $tenant) }}" method="POST" class="bg-white rounded-2xl p-6 shadow-sm border border-[#10231C]/10 space-y-5">
        @csrf
        @method('PUT')

        <!-- Company Name -->
        <div>
            <label class="block text-xs font-bold uppercase text-gray-700 mb-1">Company / Business Name *</label>
            <input type="text" name="name" value="{{ old('name', $tenant->name) }}" required
                   class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0E5E4A] focus:outline-none text-sm">
        </div>

        <!-- Subdomain Slug & Custom Domain -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">Store / Workspace Slug *</label>
                <div class="flex rounded-xl shadow-sm">
                    <span class="inline-flex items-center px-2.5 rounded-l-xl border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-[11px] font-mono">
                        /store/
                    </span>
                    <input type="text" name="slug" value="{{ old('slug', $tenant->slug) }}" required
                           class="flex-1 min-w-0 block w-full px-3 py-2.5 rounded-none rounded-r-xl border border-gray-300 focus:ring-2 focus:ring-[#0E5E4A] text-sm">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">Custom Domain (Optional)</label>
                <input type="text" name="custom_domain" value="{{ old('custom_domain', $tenant->custom_domain) }}" placeholder="e.g. acme.com"
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0E5E4A] focus:outline-none text-sm">
            </div>
        </div>

        <!-- Contact & Currency -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">Business Email *</label>
                <input type="email" name="email" value="{{ old('email', $tenant->email) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0E5E4A] focus:outline-none text-sm">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">Currency *</label>
                <select name="currency" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0E5E4A] text-sm">
                    <option value="NGN" {{ $tenant->currency === 'NGN' ? 'selected' : '' }}>NGN (₦)</option>
                    <option value="USD" {{ $tenant->currency === 'USD' ? 'selected' : '' }}>USD ($)</option>
                    <option value="GBP" {{ $tenant->currency === 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                    <option value="EUR" {{ $tenant->currency === 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                </select>
            </div>
        </div>

        <!-- Status -->
        <div>
            <label class="block text-xs font-bold uppercase text-gray-700 mb-1">Account Status *</label>
            <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0E5E4A] text-sm">
                <option value="active" {{ $tenant->status === 'active' ? 'selected' : '' }}>Active</option>
                <option value="trialing" {{ $tenant->status === 'trialing' ? 'selected' : '' }}>Trialing</option>
                <option value="past_due" {{ $tenant->status === 'past_due' ? 'selected' : '' }}>Past Due (Grace)</option>
                <option value="suspended" {{ $tenant->status === 'suspended' ? 'selected' : '' }}>Suspended (Read-Only)</option>
                <option value="cancelled" {{ $tenant->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

        <!-- AI Token Quota -->
        <div class="p-4 rounded-xl bg-amber-50/60 border border-amber-200 space-y-3">
            <div class="flex items-center space-x-3">
                <input type="checkbox" id="ai_enabled" name="ai_enabled" value="1" {{ $tenant->ai_enabled ? 'checked' : '' }} class="rounded text-[#0E5E4A] focus:ring-[#0E5E4A] w-4 h-4">
                <label for="ai_enabled" class="text-xs font-bold text-gray-800 cursor-pointer">
                    Enable AI Center & Autonomous AI Workforce
                </label>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Monthly AI Token Quota</label>
                <input type="number" name="ai_monthly_token_quota" value="{{ old('ai_monthly_token_quota', $tenant->ai_monthly_token_quota) }}" min="0" step="1000"
                       class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0E5E4A] text-sm bg-white">
                <span class="text-[11px] text-gray-500">Tokens used this cycle: {{ number_format($tenant->ai_tokens_used) }}</span>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex items-center justify-end space-x-3">
            <a href="{{ route('super-admin.tenants.show', $tenant) }}" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-600 hover:text-gray-900">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold bg-[#0E5E4A] hover:bg-[#0c4e3e] text-white shadow-sm transition">
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
