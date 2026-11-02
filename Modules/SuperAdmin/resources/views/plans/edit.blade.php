@extends('superadmin::layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <a href="{{ route('super-admin.plans.index') }}" class="text-xs font-bold text-[#0E5E4A] hover:underline">&larr; Back to Plans</a>
        <h1 class="text-2xl font-extrabold text-[#10231C] mt-2">Edit Plan: {{ $plan->name }}</h1>
        <p class="text-sm text-[#10231C]/70">Update pricing thresholds, included seats, and feature access.</p>
    </div>

    <form action="{{ route('super-admin.plans.update', $plan) }}" method="POST" class="bg-white rounded-2xl p-6 shadow-sm border border-[#10231C]/10 space-y-6" x-data="{ billingType: '{{ $plan->billing_type }}' }">
        @csrf
        @method('PUT')

        <!-- Plan Name & Code -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">Plan Name *</label>
                <input type="text" name="name" value="{{ old('name', $plan->name) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0E5E4A] focus:outline-none text-sm">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">Unique Plan Code *</label>
                <input type="text" name="code" value="{{ old('code', $plan->code) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0E5E4A] focus:outline-none text-sm font-mono">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="2" class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0E5E4A] focus:outline-none text-sm">{{ old('description', $plan->description) }}</textarea>
        </div>

        <!-- Pricing Model Selector -->
        <div>
            <label class="block text-xs font-bold uppercase text-gray-700 mb-2">Billing Model *</label>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <label class="border rounded-xl p-4 flex items-start space-x-3 cursor-pointer transition"
                       :class="billingType === 'flat_tiered' ? 'border-[#0E5E4A] bg-[#0E5E4A]/5' : 'border-gray-200 hover:border-gray-300'">
                    <input type="radio" name="billing_type" value="flat_tiered" x-model="billingType" class="mt-0.5 text-[#0E5E4A] focus:ring-[#0E5E4A]">
                    <div>
                        <span class="block text-sm font-bold text-[#10231C]">Flat Tiered Package</span>
                        <span class="block text-xs text-gray-500">Fixed price for a bundle of features and user seats.</span>
                    </div>
                </label>

                <label class="border rounded-xl p-4 flex items-start space-x-3 cursor-pointer transition"
                       :class="billingType === 'per_seat' ? 'border-[#0E5E4A] bg-[#0E5E4A]/5' : 'border-gray-200 hover:border-gray-300'">
                    <input type="radio" name="billing_type" value="per_seat" x-model="billingType" class="mt-0.5 text-[#0E5E4A] focus:ring-[#0E5E4A]">
                    <div>
                        <span class="block text-sm font-bold text-[#10231C]">Per-Seat / User</span>
                        <span class="block text-xs text-gray-500">Base access fee + charge per active team seat.</span>
                    </div>
                </label>

                <label class="border rounded-xl p-4 flex items-start space-x-3 cursor-pointer transition"
                       :class="billingType === 'payg' ? 'border-[#0E5E4A] bg-[#0E5E4A]/5' : 'border-gray-200 hover:border-gray-300'">
                    <input type="radio" name="billing_type" value="payg" x-model="billingType" class="mt-0.5 text-[#0E5E4A] focus:ring-[#0E5E4A]">
                    <div>
                        <span class="block text-sm font-bold text-[#10231C]">⚡ Pay-As-You-Transact</span>
                        <span class="block text-xs text-gray-500">Zero monthly fee. Operating wallet micro-fees per action.</span>
                    </div>
                </label>
            </div>
        </div>

        <!-- Prices -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">Base Price (₦) *</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $plan->price) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0E5E4A] text-sm">
            </div>

            <div x-show="billingType === 'per_seat'">
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">Cost Per Extra Seat (₦)</label>
                <input type="number" step="0.01" name="price_per_user" value="{{ old('price_per_user', $plan->price_per_user) }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0E5E4A] text-sm">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">Billing Interval *</label>
                <select name="billing_interval" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0E5E4A] text-sm">
                    <option value="monthly" {{ $plan->billing_interval === 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="yearly" {{ $plan->billing_interval === 'yearly' ? 'selected' : '' }}>Yearly</option>
                </select>
            </div>
        </div>

        <!-- User Seats Limits -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">Included Seats *</label>
                <input type="number" name="included_users" value="{{ old('included_users', $plan->included_users) }}" min="1" required
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0E5E4A] text-sm">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">Max Users Allowed</label>
                <input type="number" name="max_users" value="{{ old('max_users', $plan->max_users) }}" placeholder="Leave blank for unlimited"
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0E5E4A] text-sm">
            </div>
        </div>

        <!-- High-Tier Flags -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
            <div class="p-3.5 rounded-xl bg-amber-50/70 border border-amber-200 flex items-center space-x-3">
                <input type="checkbox" id="enable_ai" name="enable_ai" value="1" {{ $plan->enable_ai ? 'checked' : '' }} class="rounded text-[#0E5E4A] focus:ring-[#0E5E4A] w-4 h-4">
                <label for="enable_ai" class="text-xs font-bold text-gray-800 cursor-pointer">
                    Enable AI Center & Workforce ✨
                </label>
            </div>

            <div class="p-3.5 rounded-xl bg-emerald-50/70 border border-emerald-200 flex items-center space-x-3">
                <input type="checkbox" id="enable_custom_domain" name="enable_custom_domain" value="1" {{ $plan->enable_custom_domain ? 'checked' : '' }} class="rounded text-[#0E5E4A] focus:ring-[#0E5E4A] w-4 h-4">
                <label for="enable_custom_domain" class="text-xs font-bold text-gray-800 cursor-pointer">
                    Enable Custom Domain Mapping 🌐
                </label>
            </div>
        </div>

        <!-- Module Features -->
        <div>
            <label class="block text-xs font-bold uppercase text-gray-700 mb-2">Module Access Gate</label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 p-4 rounded-xl bg-[#F6F4EC]/60 border border-gray-200">
                @foreach($availableFeatures as $key => $label)
                <label class="flex items-center space-x-2 text-xs font-medium text-gray-700 cursor-pointer">
                    <input type="checkbox" name="features[{{ $key }}]" value="1" {{ !empty($plan->features[$key]) ? 'checked' : '' }} class="rounded text-[#0E5E4A] focus:ring-[#0E5E4A]">
                    <span>{{ $label }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <!-- Status -->
        <div class="flex items-center space-x-3">
            <input type="checkbox" id="is_active" name="is_active" value="1" {{ $plan->is_active ? 'checked' : '' }} class="rounded text-[#0E5E4A] focus:ring-[#0E5E4A] w-4 h-4">
            <label for="is_active" class="text-xs font-bold text-gray-800 cursor-pointer">
                Plan is active and available for subscriptions
            </label>
        </div>

        <div class="pt-4 border-t border-gray-100 flex items-center justify-end space-x-3">
            <a href="{{ route('super-admin.plans.index') }}" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-600 hover:text-gray-900">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold bg-[#0E5E4A] hover:bg-[#0c4e3e] text-white shadow-sm transition">
                Update Plan
            </button>
        </div>
    </form>
</div>
@endsection
