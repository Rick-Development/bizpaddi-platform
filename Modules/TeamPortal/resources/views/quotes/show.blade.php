@extends('teamportal::layouts.app')

@section('title', 'Quote Details')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">Quote {{ $quote->reference }}</h1>
            <p class="mt-2 text-sm text-gray-700">
                For {{ $quote->client->company_name ?? $quote->client->name ?? 'Unknown Client' }}
            </p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="{{ route('team.quotes.index') }}" class="block rounded-md bg-gray-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-gray-500">Back</a>
        </div>
    </div>

    <div class="mt-6 border-t border-gray-100">
        <dl class="divide-y divide-gray-100">
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Status</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ ucfirst($quote->status) }}</dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Date Issued</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $quote->created_at->format('F d, Y') }}</dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Total Amount</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">${{ number_format($quote->total, 2) }}</dd>
            </div>
             <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Valid Until</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $quote->valid_until ? $quote->valid_until->format('F d, Y') : 'N/A' }}</dd>
            </div>
        </dl>
    </div>
</div>
@endsection
