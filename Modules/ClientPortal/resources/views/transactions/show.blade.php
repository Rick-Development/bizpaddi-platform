@extends('clientportal::layouts.app')

@section('title', 'Transaction Details')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-x-4">
        <a href="{{ route('client.transactions.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Transaction Details</h1>
            <p class="mt-1 text-sm text-gray-600">Reference: {{ $transaction->reference }}</p>
        </div>
        <div class="ml-auto">
             <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                {{ $transaction->status === 'successful' ? 'bg-green-100 text-green-800' : 
                   ($transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                {{ ucfirst($transaction->status) }}
            </span>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Payment Details</h3>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-xs text-gray-500 uppercase tracking-wider">Amount</dt>
                        <dd class="text-2xl font-bold text-gray-900 mt-1">
                            {{ $transaction->currency_code }} {{ number_format($transaction->amount, 2) }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 uppercase tracking-wider">Date</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ $transaction->created_at->format('M d, Y H:i A') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 uppercase tracking-wider">Description</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ $transaction->description }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 uppercase tracking-wider">Payment Method</dt>
                        <dd class="text-sm text-gray-900 mt-1">Virtual Account ({{ ucfirst($transaction->provider) }})</dd>
                    </div>
                </dl>
            </div>
            
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Related Information</h3>
                <dl class="space-y-4">
                     @if($transaction->payable_type === \Modules\Accounting\Models\Invoice::class)
                    <div>
                        <dt class="text-xs text-gray-500 uppercase tracking-wider">Payment For</dt>
                        <dd class="text-sm text-gray-900 mt-1">
                            Invoice #{{ $transaction->payable->invoice_number }}
                            <a href="{{ route('client.invoices.show', $transaction->payable_id) }}" class="text-brand-primary hover:underline ml-2 text-xs">View Invoice</a>
                        </dd>
                    </div>
                    @endif
                    @if($transaction->paid_at)
                    <div>
                        <dt class="text-xs text-gray-500 uppercase tracking-wider">Paid At</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ $transaction->paid_at->format('M d, Y H:i A') }}</dd>
                    </div>
                    @endif
                    @if($transaction->provider_reference)
                    <div>
                        <dt class="text-xs text-gray-500 uppercase tracking-wider">External Reference</dt>
                        <dd class="text-sm text-gray-500 mt-1 font-mono bg-gray-50 px-2 py-1 rounded inline-block">
                            {{ $transaction->provider_reference }}
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
    
    @if($transaction->metadata)
    <div class="bg-gray-50 px-6 py-6 border-t border-gray-100">
        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Technical Metadata</h4>
        <pre class="text-xs text-gray-600 bg-gray-100 p-4 rounded-lg overflow-x-auto">{{ json_encode($transaction->metadata, JSON_PRETTY_PRINT) }}</pre>
    </div>
    @endif
</div>
@endsection
