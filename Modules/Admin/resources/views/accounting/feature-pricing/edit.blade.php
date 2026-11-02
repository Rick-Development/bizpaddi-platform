@extends('admin::layouts.app')

@section('title', 'Edit Feature Pricing')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-x-4">
        <a href="{{ route('admin.accounting.feature-pricing.index') }}" class="text-gray-400 hover:text-gray-400">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-white">Edit Feature Pricing</h1>
            <p class="mt-1 text-sm text-gray-400">Update pricing for {{ $featurePricing->feature_name }}</p>
        </div>
    </div>
</div>

<form action="{{ route('admin.accounting.feature-pricing.update', $featurePricing) }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')

    <div class="bg-[#10231C]/60 rounded-2xl shadow-sm border border-white/10 p-6">
        <h2 class="text-lg font-semibold text-white mb-4">Feature Information</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Feature Name <span class="text-red-500">*</span></label>
                <input type="text" name="feature_name" value="{{ old('feature_name', $featurePricing->feature_name) }}" required class="w-full px-3 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                @error('feature_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Feature Code <span class="text-red-500">*</span></label>
                <input type="text" name="feature_code" value="{{ old('feature_code', $featurePricing->feature_code) }}" required class="w-full px-3 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                @error('feature_code')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-300 mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-[#0a1612] text-white border-white/10">{{ old('description', $featurePricing->description) }}</textarea>
            </div>
        </div>
    </div>

    <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl p-6">
        <h2 class="text-lg font-semibold text-white mb-4">Pricing Details</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Price <span class="text-red-500">*</span></label>
                <input type="number" name="price" value="{{ old('price', $featurePricing->price) }}" step="0.01" min="0" required class="w-full px-3 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                @error('price')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Currency <span class="text-red-500">*</span></label>
                <select name="currency" required class="w-full px-3 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-[#0a1612] text-white border-white/10">
                    <option value="NGN" {{ old('currency', $featurePricing->currency) == 'NGN' ? 'selected' : '' }}>NGN (₦)</option>
                    <option value="USD" {{ old('currency', $featurePricing->currency) == 'USD' ? 'selected' : '' }}>USD ($)</option>
                    <option value="EUR" {{ old('currency', $featurePricing->currency) == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                    <option value="GBP" {{ old('currency', $featurePricing->currency) == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                </select>
                @error('currency')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Billing Type <span class="text-red-500">*</span></label>
                <select name="billing_type" required class="w-full px-3 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-[#0a1612] text-white border-white/10">
                    <option value="one_time" {{ old('billing_type', $featurePricing->billing_type) == 'one_time' ? 'selected' : '' }}>One Time</option>
                    <option value="monthly" {{ old('billing_type', $featurePricing->billing_type) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="yearly" {{ old('billing_type', $featurePricing->billing_type) == 'yearly' ? 'selected' : '' }}>Yearly</option>
                </select>
                @error('billing_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $featurePricing->is_active) ? 'checked' : '' }} class="h-4 w-4 text-brand-primary focus:ring-brand-primary border-white/10 rounded">
                <label class="ml-2 block text-sm text-gray-300">Active</label>
            </div>
        </div>
    </div>

    <div class="flex justify-end gap-x-3">
        <a href="{{ route('admin.accounting.feature-pricing.index') }}" class="px-4 py-2 border border-white/10 rounded-md text-sm font-medium text-gray-300 hover:bg-white/[0.03]">
            Cancel
        </a>
        <button type="submit" class="px-4 py-2 bg-brand-primary text-white rounded-md text-sm font-medium hover:bg-brand-primary/90">
            Update Feature Pricing
        </button>
    </div>
</form>
@endsection
