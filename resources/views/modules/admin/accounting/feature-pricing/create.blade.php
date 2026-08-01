@extends('admin::layouts.app')

@section('title', 'Add Feature Pricing')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-x-4">
        <a href="{{ route('admin.accounting.feature-pricing.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Add Feature Pricing</h1>
            <p class="mt-1 text-sm text-gray-600">Define pricing for a software feature or module</p>
        </div>
    </div>
</div>

<div class="max-w-2xl">
    <form action="{{ route('admin.accounting.feature-pricing.store') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-200">
        @csrf
        
        <div class="p-6 space-y-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="feature_name" class="block text-sm font-medium text-gray-700 mb-2">Feature Name *</label>
                    <input type="text" name="feature_name" id="feature_name" value="{{ old('feature_name') }}" required placeholder="e.g., Auth Module"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-colors @error('feature_name') border-red-500 @enderror">
                    @error('feature_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="feature_code" class="block text-sm font-medium text-gray-700 mb-2">Feature Code *</label>
                    <input type="text" name="feature_code" id="feature_code" value="{{ old('feature_code') }}" required placeholder="e.g., auth_module"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent transition-colors @error('feature_code') border-red-500 @enderror">
                    @error('feature_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" id="description" rows="3" placeholder="Feature description and parameters"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent transition-colors @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price *</label>
                    <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0" required placeholder="200000"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent transition-colors @error('price') border-red-500 @enderror">
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">Currency *</label>
                    <select name="currency" id="currency" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent transition-colors @error('currency') border-red-500 @enderror">
                        <option value="NGN" {{ old('currency') == 'NGN' ? 'selected' : '' }}>NGN</option>
                        <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD</option>
                        <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                        <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>GBP</option>
                    </select>
                    @error('currency')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="billing_type" class="block text-sm font-medium text-gray-700 mb-2">Billing Type *</label>
                    <select name="billing_type" id="billing_type" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent transition-colors @error('billing_type') border-red-500 @enderror">
                        <option value="one_time" {{ old('billing_type') == 'one_time' ? 'selected' : '' }}>One Time</option>
                        <option value="monthly" {{ old('billing_type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="yearly" {{ old('billing_type') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                    </select>
                    @error('billing_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                    class="h-4 w-4 text-brand-primary focus:ring-brand-primary border-gray-300 rounded">
                <label for="is_active" class="ml-2 block text-sm text-gray-700">
                    Active (available for quoting)
                </label>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-2xl flex items-center justify-end gap-x-3">
            <a href="{{ route('admin.accounting.feature-pricing.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-brand-primary rounded-lg hover:bg-brand-primary/90 transition-colors">
                Add Feature
            </button>
        </div>
    </form>
</div>
@endsection
