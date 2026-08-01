@extends('clientportal::layouts.app')

@section('title', 'Pay Quote ' . $quote->quote_number)

@section('content')
<div class="mb-6">
    <a href="{{ route('client.quotes.show', $quote->id) }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">
        &larr; Back to Quote
    </a>
</div>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-8 border-b border-gray-100 bg-gray-50">
            <h2 class="text-2xl font-bold text-gray-900 text-center">Payment Instructions</h2>
            <p class="mt-2 text-center text-gray-600">Please make a transfer to the account below to pay for your quote.</p>
        </div>
        
        <div class="p-8">
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-6 mb-8 text-center">
                <p class="text-sm text-blue-600 font-medium uppercase tracking-wide mb-1">Total Amount Due</p>
                <p class="text-4xl font-bold text-blue-900">
                    {{ $quote->currency->symbol ?? '$' }}{{ number_format($quote->total_amount, 2) }}
                </p>
            </div>

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Bank Name</label>
                    <div class="mt-1 text-lg font-semibold text-gray-900">{{ $virtualAccount->bank_name }}</div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Account Number</label>
                    <div class="mt-1 flex items-center gap-3">
                        <span class="text-2xl font-mono font-bold text-gray-900">{{ $virtualAccount->account_number }}</span>
                        <!-- You could add a copy button here -->
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Account Name</label>
                    <div class="mt-1 text-lg font-semibold text-gray-900">{{ $virtualAccount->account_name }}</div>
                </div>

                @if($virtualAccount->reference)
                <div>
                    <label class="block text-sm font-medium text-gray-500">Payment Reference</label>
                    <div class="mt-1 text-base font-mono text-gray-700">{{ $virtualAccount->reference }}</div>
                    <p class="text-xs text-gray-400 mt-1">Please include this reference in your transfer narration if possible.</p>
                </div>
                @endif
            </div>

            <div class="mt-8 pt-8 border-t border-gray-100 text-center">
                <p class="text-sm text-gray-500 mb-4">
                    Once you have made the transfer, your quote status will be updated automatically upon confirmation.
                </p>
                <form action="{{ route('client.quotes.show', $quote->id) }}" method="GET">
                    <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-brand-primary py-2 px-6 text-sm font-medium text-white shadow-sm hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2">
                        I have made the payment
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
