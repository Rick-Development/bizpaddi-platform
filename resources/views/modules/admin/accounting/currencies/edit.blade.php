@extends('admin::layouts.app')

@section('title', 'Edit Currency')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-x-4">
        <a href="{{ route('admin.accounting.currencies.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Currency</h1>
            <p class="mt-1 text-sm text-gray-600">Update currency details</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 max-w-2xl">
    <form action="{{ route('admin.accounting.currencies.update', $currency) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="space-y-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Currency Name</label>
                <input type="text" name="name" id="name" required value="{{ old('name', $currency->name) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Code (ISO)</label>
                    <input type="text" name="code" id="code" required value="{{ old('code', $currency->code) }}" maxlength="3"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent uppercase">
                </div>
                <div>
                    <label for="symbol" class="block text-sm font-medium text-gray-700 mb-1">Symbol</label>
                    <input type="text" name="symbol" id="symbol" required value="{{ old('symbol', $currency->symbol) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                </div>
            </div>

            <div>
                <label for="exchange_rate" class="block text-sm font-medium text-gray-700 mb-1">Exchange Rate (vs Default)</label>
                <input type="number" name="exchange_rate" id="exchange_rate" required step="0.00000001" min="0" value="{{ old('exchange_rate', $currency->exchange_rate) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
            </div>

            <div class="flex items-center gap-x-6">
                <div class="flex items-center">
                    <input type="checkbox" name="is_default" id="is_default" {{ old('is_default', $currency->is_default) ? 'checked' : '' }} class="h-4 w-4 text-brand-primary focus:ring-brand-primary border-gray-300 rounded">
                    <label for="is_default" class="ml-2 block text-sm text-gray-900">Set as Default</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" {{ old('is_active', $currency->is_active) ? 'checked' : '' }} class="h-4 w-4 text-[#FB4C4B] focus:ring-[#FB4C4B] border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">Active</label>
                </div>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors shadow-sm">
                    Update Currency
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
