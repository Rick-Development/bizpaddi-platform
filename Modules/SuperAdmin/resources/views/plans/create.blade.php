@extends('superadmin::layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('super-admin.plans.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-400 hover:text-emerald-300 transition mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Back to Plans</span>
            </a>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">Create Subscription Plan</h1>
            <p class="text-xs text-gray-400 mt-1">Set up pricing tiers, user seat thresholds, and module feature gates for Bizpaddi tenants.</p>
        </div>
    </div>

    {{-- Form Container --}}
    <form action="{{ route('super-admin.plans.store') }}" method="POST" 
          class="bg-[#0b1612] rounded-3xl p-6 md:p-8 shadow-2xl border border-white/10 space-y-6" 
          x-data="{ billingType: 'flat_tiered' }">
        @csrf

        {{-- Plan Name & Code --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-emerald-400 mb-2">Plan Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Growth Business"
                       class="w-full px-4 py-2.5 rounded-xl bg-[#08110e] border border-white/10 text-white placeholder-gray-500 text-xs focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                @error('name')
                    <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-emerald-400 mb-2">Unique Plan Code *</label>
                <input type="text" name="code" value="{{ old('code') }}" required placeholder="growth_monthly"
                       class="w-full px-4 py-2.5 rounded-xl bg-[#08110e] border border-white/10 text-white placeholder-gray-500 text-xs font-mono focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                @error('code')
                    <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Description --}}
        <div>
            <label class="block text-[10px] font-extrabold uppercase tracking-wider text-emerald-400 mb-2">Description</label>
            <textarea name="description" rows="2" placeholder="Brief summary of who this plan is designed for..."
                      class="w-full px-4 py-2.5 rounded-xl bg-[#08110e] border border-white/10 text-white placeholder-gray-500 text-xs focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition resize-none">{{ old('description') }}</textarea>
            @error('description')
                <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        {{-- Billing Model Selector --}}
        <div>
            <label class="block text-[10px] font-extrabold uppercase tracking-wider text-emerald-400 mb-2.5">Billing Model *</label>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <label class="border rounded-2xl p-4 flex items-start space-x-3 cursor-pointer transition-all"
                       :class="billingType === 'flat_tiered' ? 'border-emerald-500 bg-emerald-500/10 shadow-lg shadow-emerald-950/40' : 'border-white/10 bg-[#08110e] hover:border-white/20'">
                    <input type="radio" name="billing_type" value="flat_tiered" x-model="billingType" class="mt-0.5 text-emerald-500 focus:ring-emerald-500 bg-[#0b1612] border-white/20">
                    <div>
                        <span class="block text-xs font-bold text-white">Flat Tiered Package</span>
                        <span class="block text-[11px] text-gray-400 mt-0.5 leading-relaxed">Fixed subscription price for bundled features & user seats.</span>
                    </div>
                </label>

                <label class="border rounded-2xl p-4 flex items-start space-x-3 cursor-pointer transition-all"
                       :class="billingType === 'per_seat' ? 'border-[#F2A900] bg-[#F2A900]/10 shadow-lg shadow-amber-950/40' : 'border-white/10 bg-[#08110e] hover:border-white/20'">
                    <input type="radio" name="billing_type" value="per_seat" x-model="billingType" class="mt-0.5 text-[#F2A900] focus:ring-[#F2A900] bg-[#0b1612] border-white/20">
                    <div>
                        <span class="block text-xs font-bold text-white">Per-Seat / User</span>
                        <span class="block text-[11px] text-gray-400 mt-0.5 leading-relaxed">Base monthly fee plus charge per active team seat.</span>
                    </div>
                </label>

                <label class="border rounded-2xl p-4 flex items-start space-x-3 cursor-pointer transition-all"
                       :class="billingType === 'payg' ? 'border-amber-400 bg-amber-400/10 shadow-lg shadow-amber-950/40' : 'border-white/10 bg-[#08110e] hover:border-white/20'">
                    <input type="radio" name="billing_type" value="payg" x-model="billingType" class="mt-0.5 text-amber-400 focus:ring-amber-400 bg-[#0b1612] border-white/20">
                    <div>
                        <span class="block text-xs font-bold text-amber-300">⚡ Pay-As-You-Transact</span>
                        <span class="block text-[11px] text-gray-400 mt-0.5 leading-relaxed">Zero monthly fee. Operating wallet micro-fees per action.</span>
                    </div>
                </label>
            </div>
        </div>

        {{-- Prices & Interval --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-emerald-400 mb-2">Base Price (₦) *</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', '25000') }}" required
                       class="w-full px-4 py-2.5 rounded-xl bg-[#08110e] border border-white/10 text-white placeholder-gray-500 text-xs focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
            </div>

            <div x-show="billingType === 'per_seat'" x-cloak>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-[#F2A900] mb-2">Cost Per Extra Seat (₦)</label>
                <input type="number" step="0.01" name="price_per_user" value="{{ old('price_per_user', '3000') }}"
                       class="w-full px-4 py-2.5 rounded-xl bg-[#08110e] border border-white/10 text-white placeholder-gray-500 text-xs focus:outline-none focus:border-[#F2A900] focus:ring-1 focus:ring-[#F2A900] transition">
            </div>

            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-emerald-400 mb-2">Billing Interval *</label>
                <select name="billing_interval" 
                        class="w-full px-4 py-2.5 rounded-xl bg-[#08110e] border border-white/10 text-white text-xs focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                </select>
            </div>
        </div>

        {{-- User Seats Limits --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-emerald-400 mb-2">Included Seats *</label>
                <input type="number" name="included_users" value="{{ old('included_users', '5') }}" min="1" required
                       class="w-full px-4 py-2.5 rounded-xl bg-[#08110e] border border-white/10 text-white placeholder-gray-500 text-xs focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                <span class="text-[10px] text-gray-500 mt-1 block">Base team members covered by the base price.</span>
            </div>

            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-emerald-400 mb-2">Max Users Allowed</label>
                <input type="number" name="max_users" value="{{ old('max_users') }}" placeholder="Leave blank for unlimited"
                       class="w-full px-4 py-2.5 rounded-xl bg-[#08110e] border border-white/10 text-white placeholder-gray-500 text-xs focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                <span class="text-[10px] text-gray-500 mt-1 block">Leave empty for unlimited team size.</span>
            </div>
        </div>

        {{-- High-Tier Flags --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
            <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center space-x-3 transition hover:border-amber-500/40">
                <input type="checkbox" id="enable_ai" name="enable_ai" value="1" 
                       class="rounded text-amber-400 focus:ring-amber-400 bg-[#08110e] border-white/20 w-4 h-4">
                <label for="enable_ai" class="text-xs font-bold text-amber-300 cursor-pointer">
                    Enable AI Center & Autonomous Workforce ✨
                </label>
            </div>

            <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center space-x-3 transition hover:border-emerald-500/40">
                <input type="checkbox" id="enable_custom_domain" name="enable_custom_domain" value="1" 
                       class="rounded text-emerald-400 focus:ring-emerald-400 bg-[#08110e] border-white/20 w-4 h-4">
                <label for="enable_custom_domain" class="text-xs font-bold text-emerald-300 cursor-pointer">
                    Enable Custom Domain Mapping 🌐
                </label>
            </div>
        </div>

        {{-- Modular Feature Checkboxes --}}
        <div>
            <label class="block text-[10px] font-extrabold uppercase tracking-wider text-emerald-400 mb-2.5">Module Access Gate</label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-5 rounded-2xl bg-[#08110e] border border-white/10">
                @foreach($availableFeatures as $key => $label)
                <label class="flex items-center space-x-2.5 text-xs font-semibold text-gray-300 hover:text-white cursor-pointer transition">
                    <input type="checkbox" name="features[{{ $key }}]" value="1" checked 
                           class="rounded text-emerald-500 focus:ring-emerald-500 bg-[#0b1612] border-white/20 w-4 h-4">
                    <span>{{ $label }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Status --}}
        <div class="flex items-center space-x-3 pt-2">
            <input type="checkbox" id="is_active" name="is_active" value="1" checked
                   class="rounded text-emerald-500 focus:ring-emerald-500 bg-[#08110e] border-white/20 w-4 h-4">
            <label for="is_active" class="text-xs font-bold text-gray-300 cursor-pointer">
                Plan is active and immediately visible to businesses
            </label>
        </div>

        {{-- Submit & Cancel --}}
        <div class="pt-5 border-t border-white/10 flex items-center justify-end space-x-3">
            <a href="{{ route('super-admin.plans.index') }}" 
               class="px-5 py-2.5 rounded-xl text-xs font-semibold text-gray-400 hover:text-white border border-white/10 hover:bg-white/[0.04] transition">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-2.5 rounded-xl text-xs font-bold bg-gradient-to-r from-[#0E5E4A] to-[#10B981] hover:brightness-110 text-white shadow-lg shadow-emerald-950/50 active:scale-95 transition">
                Save & Activate Plan
            </button>
        </div>
    </form>
</div>
@endsection
