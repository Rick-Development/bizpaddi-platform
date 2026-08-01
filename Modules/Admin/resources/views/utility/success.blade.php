@extends('admin::layouts.app')

@section('title', 'Purchase Successful')

@section('content')
<div class="min-h-[80vh] flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl overflow-hidden">
        <div class="p-8 text-center">
            <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-green-100 mb-8">
                <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Purchase Successful!</h2>
            <p class="text-gray-500 mb-8">Your transaction has been processed successfully.</p>

            <div class="bg-gray-50 rounded-2xl p-6 mb-8 text-left">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-gray-500 text-sm">Transaction Ref</span>
                    <span class="font-mono text-gray-900 font-medium">{{ $transaction->transaction_number }}</span>
                </div>
                <div class="flex justify-between items-center mb-4">
                    <span class="text-gray-500 text-sm">Amount</span>
                    <span class="text-gray-900 font-bold text-lg">₦{{ number_format($transaction->amount, 2) }}</span>
                </div>
                <div class="flex justify-between items-center mb-4">
                    <span class="text-gray-500 text-sm">Beneficiary</span>
                    <span class="text-gray-900 font-medium">{{ $transaction->metadata['phone_number'] ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between items-center mb-4">
                    <span class="text-gray-500 text-sm">Service</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 uppercase">
                        {{ $transaction->metadata['service_type'] ?? 'VAS' }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 text-sm">Date</span>
                    <span class="text-gray-900 font-medium">{{ $transaction->created_at->format('M d, Y h:i A') }}</span>
                </div>
            </div>

            <div class="space-y-4">
                <a href="{{ route('admin.team-utilities.index') }}" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-brand-primary hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary transition-colors duration-200">
                    Process Another Purchase
                </a>
                <a href="{{ route('admin.accounting.transactions.index') }}" class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary transition-colors duration-200">
                    View All Transactions
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
