@extends('superadmin::layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <a href="{{ route('super-admin.tenants.index') }}" class="text-xs font-bold text-[#0E5E4A] hover:underline">&larr; Back to Businesses</a>
        <h1 class="text-2xl font-extrabold text-[#10231C] mt-2">Onboard New Business</h1>
        <p class="text-sm text-[#10231C]/70">Register a company and provision their Bizpaddi workspace & storefront.</p>
    </div>

    <form action="{{ route('super-admin.tenants.store') }}" method="POST" class="bg-white rounded-2xl p-6 shadow-sm border border-[#10231C]/10 space-y-5">
        @csrf

        <!-- Company Name -->
        <div>
            <label class="block text-xs font-bold uppercase text-gray-700 mb-1">Company / Business Name *</label>
            <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Acme Corporation"
                   class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0E5E4A] focus:outline-none text-sm">
            @error('name') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
        </div>

        <!-- Subdomain Slug -->
        <div>
            <label class="block text-xs font-bold uppercase text-gray-700 mb-1">Store / Workspace Slug *</label>
            <div class="flex rounded-xl shadow-sm">
                <span class="inline-flex items-center px-3 rounded-l-xl border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-xs font-mono">
                    /store/
                </span>
                <input type="text" name="slug" value="{{ old('slug') }}" required placeholder="acmecorp"
                       class="flex-1 min-w-0 block w-full px-4 py-2.5 rounded-none rounded-r-xl border border-gray-300 focus:ring-2 focus:ring-[#0E5E4A] focus:outline-none text-sm">
            </div>
            @error('slug') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
        </div>

        <!-- Contact Email -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">Business Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="admin@acmecorp.com"
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0E5E4A] focus:outline-none text-sm">
                @error('email') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+234..."
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0E5E4A] focus:outline-none text-sm">
            </div>
        </div>

        <!-- Initial Plan Selection -->
        <div>
            <label class="block text-xs font-bold uppercase text-gray-700 mb-1">Initial Subscription Plan *</label>
            <select name="plan_id" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0E5E4A] focus:outline-none text-sm">
                @foreach($plans as $plan)
                <option value="{{ $plan->id }}">
                    {{ $plan->name }} (₦{{ number_format($plan->price, 0) }}/{{ $plan->billing_interval }}) — {{ $plan->billing_type === 'per_seat' ? 'Per-Seat Model' : 'Flat Tiered' }}
                </option>
                @endforeach
            </select>
        </div>

        <!-- Free Trial Days -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">Free Trial Duration</label>
                <input type="number" name="trial_days" value="14" min="0" max="365"
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0E5E4A] focus:outline-none text-sm">
                <span class="text-[11px] text-gray-400">Set to 0 to activate immediately.</span>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">AI Monthly Token Quota</label>
                <input type="number" name="ai_monthly_token_quota" value="0" min="0" step="1000"
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0E5E4A] focus:outline-none text-sm">
                <span class="text-[11px] text-gray-400">0 for unlimited / plan default.</span>
            </div>
        </div>

        <!-- AI Capability Toggle -->
        <div class="flex items-center space-x-3 p-4 rounded-xl bg-amber-50/60 border border-amber-200">
            <input type="checkbox" id="ai_enabled" name="ai_enabled" value="1" class="rounded text-[#0E5E4A] focus:ring-[#0E5E4A] w-4 h-4">
            <label for="ai_enabled" class="text-xs font-semibold text-gray-800 cursor-pointer">
                Enable AI Center & Autonomous AI Workforce for this business
            </label>
        </div>

        <div class="pt-4 border-t border-gray-100 flex items-center justify-end space-x-3">
            <a href="{{ route('super-admin.tenants.index') }}" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-600 hover:text-gray-900">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold bg-[#0E5E4A] hover:bg-[#0c4e3e] text-white shadow-sm transition">
                Create & Provision Business
            </button>
        </div>
    </form>
</div>
@endsection
