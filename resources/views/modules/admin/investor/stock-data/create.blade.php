@extends('admin::layouts.app')

@section('title', 'Add Stock Data')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Add Stock Data</h1>
    <p class="mt-2 text-sm text-gray-700">Record daily stock price and market information</p>
</div>

<form action="{{ route('admin.investor.stock-data.store') }}" method="POST" class="space-y-6">
    @csrf
    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
        <div class="px-4 py-6 sm:p-8">
            <div class="grid max-w-4xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <label for="date" class="block text-sm font-medium text-gray-900">Date *</label>
                    <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" required
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('date')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="volume" class="block text-sm font-medium text-gray-900">Volume</label>
                    <input type="number" name="volume" id="volume" value="{{ old('volume') }}" min="0"
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    <p class="mt-1 text-sm text-gray-500">Number of shares traded</p>
                    @error('volume')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6 border-t border-gray-200 pt-6">
                    <h3 class="text-base font-semibold text-gray-900 mb-4">Price Information</h3>
                </div>

                <div class="sm:col-span-3">
                    <label for="opening_price" class="block text-sm font-medium text-gray-900">Opening Price</label>
                    <div class="relative mt-2 rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" step="0.01" name="opening_price" id="opening_price" value="{{ old('opening_price') }}" min="0"
                               class="block w-full rounded-md border-0 py-1.5 pl-7 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    </div>
                    @error('opening_price')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="closing_price" class="block text-sm font-medium text-gray-900">Closing Price *</label>
                    <div class="relative mt-2 rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" step="0.01" name="closing_price" id="closing_price" value="{{ old('closing_price') }}" min="0" required
                               class="block w-full rounded-md border-0 py-1.5 pl-7 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    </div>
                    @error('closing_price')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="high_price" class="block text-sm font-medium text-gray-900">High Price</label>
                    <div class="relative mt-2 rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" step="0.01" name="high_price" id="high_price" value="{{ old('high_price') }}" min="0"
                               class="block w-full rounded-md border-0 py-1.5 pl-7 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    </div>
                    @error('high_price')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="low_price" class="block text-sm font-medium text-gray-900">Low Price</label>
                    <div class="relative mt-2 rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" step="0.01" name="low_price" id="low_price" value="{{ old('low_price') }}" min="0"
                               class="block w-full rounded-md border-0 py-1.5 pl-7 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    </div>
                    @error('low_price')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6">
                    <label for="market_cap" class="block text-sm font-medium text-gray-900">Market Cap</label>
                    <div class="relative mt-2 rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" step="0.01" name="market_cap" id="market_cap" value="{{ old('market_cap') }}" min="0"
                               class="block w-full rounded-md border-0 py-1.5 pl-7 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Total market capitalization</p>
                    @error('market_cap')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
            <a href="{{ route('admin.investor.stock-data.index') }}" class="text-sm font-semibold text-gray-900">Cancel</a>
            <button type="submit" class="rounded-md bg-brand-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700">Add Stock Data</button>
        </div>
    </div>
</form>
@endsection
