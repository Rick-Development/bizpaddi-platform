@extends('superadmin::layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between pb-4 border-b border-white/10">
        <div>
            <a href="{{ route('super-admin.tenants.index') }}" class="text-xs font-bold text-emerald-400 hover:text-emerald-300 transition">&larr; Back to Businesses</a>
            <h1 class="text-2xl font-black text-white mt-1 tracking-tight">Onboard New Business</h1>
            <p class="text-xs sm:text-sm text-gray-400">Register a company and provision their Bizpaddi workspace & digital storefront.</p>
        </div>
    </div>

    <form action="{{ route('super-admin.tenants.store') }}" method="POST" class="bg-[#10231C]/90 backdrop-blur-md rounded-2xl p-6 sm:p-8 shadow-xl border border-white/10 space-y-5">
        @csrf

        <!-- Company Name -->
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">Company / Business Name <span class="text-rose-400">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Acme Corporation"
                   class="w-full px-4 py-2.5 rounded-xl border border-white/10 bg-white/5 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm transition">
            @error('name') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
        </div>

        <!-- Subdomain Slug -->
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">Store / Workspace Slug <span class="text-rose-400">*</span></label>
            <div class="flex rounded-xl shadow-sm overflow-hidden border border-white/10 focus-within:ring-2 focus-within:ring-emerald-500">
                <span class="inline-flex items-center px-3.5 bg-white/10 text-emerald-400 text-xs font-mono border-r border-white/10">
                    /store/
                </span>
                <input type="text" name="slug" value="{{ old('slug') }}" required placeholder="acmecorp"
                       class="flex-1 min-w-0 block w-full px-4 py-2.5 bg-white/5 text-white placeholder-gray-500 text-sm focus:outline-none font-mono">
            </div>
            @error('slug') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
        </div>

        <!-- Contact Email & Phone -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">Business Email <span class="text-rose-400">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="admin@acmecorp.com"
                       class="w-full px-4 py-2.5 rounded-xl border border-white/10 bg-white/5 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm transition">
                @error('email') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+234..."
                       class="w-full px-4 py-2.5 rounded-xl border border-white/10 bg-white/5 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm transition">
            </div>
        </div>

        <!-- Initial Plan Selection -->
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">Initial Subscription Plan <span class="text-rose-400">*</span></label>
            <select name="plan_id" required class="w-full px-4 py-2.5 rounded-xl border border-white/10 bg-[#0c1a15] text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                @foreach($plans as $plan)
                <option value="{{ $plan->id }}">
                    {{ $plan->name }} (₦{{ number_format($plan->price, 0) }}/{{ $plan->billing_interval }}) — {{ $plan->billing_type === 'payg' ? 'PAYG Micro-Fees' : ($plan->billing_type === 'per_seat' ? 'Per-Seat Model' : 'Flat Tiered') }}
                </option>
                @endforeach
            </select>
        </div>

        <!-- Free Trial Days & Token Quota -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">Free Trial Duration</label>
                <input type="number" name="trial_days" value="14" min="0" max="365"
                       class="w-full px-4 py-2.5 rounded-xl border border-white/10 bg-white/5 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm transition">
                <span class="text-[11px] text-gray-400 mt-1 block">Set to 0 to activate immediately.</span>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">AI Monthly Token Quota</label>
                <input type="number" name="ai_monthly_token_quota" value="0" min="0" step="1000"
                       class="w-full px-4 py-2.5 rounded-xl border border-white/10 bg-white/5 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm transition">
                <span class="text-[11px] text-gray-400 mt-1 block">0 for unlimited / plan default.</span>
            </div>
        </div>

        <!-- AI Capability Toggle -->
        <div class="flex items-center space-x-3 p-4 rounded-xl bg-amber-500/10 border border-amber-500/20">
            <input type="checkbox" id="ai_enabled" name="ai_enabled" value="1" class="rounded text-[#0E5E4A] focus:ring-[#0E5E4A] w-4 h-4 bg-white/10 border-white/20">
            <label for="ai_enabled" class="text-xs font-semibold text-amber-200 cursor-pointer">
                Enable AI Center & Autonomous AI Workforce for this business
            </label>
        </div>

        <div class="pt-4 border-t border-white/10 flex items-center justify-end space-x-3">
            <a href="{{ route('super-admin.tenants.index') }}" class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-gray-400 hover:text-white transition">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl text-xs sm:text-sm font-bold bg-gradient-to-r from-[#0E5E4A] to-[#10B981] text-white shadow-lg shadow-emerald-950/40 hover:opacity-95 border border-emerald-400/30 transition">
                Create & Provision Business
            </button>
        </div>
    </form>
</div>
@endsection
