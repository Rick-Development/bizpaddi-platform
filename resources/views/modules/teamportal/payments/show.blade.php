@extends('teamportal::layouts.app')

@section('title', 'Payment Details')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Payment Details</h1>
            <p class="mt-1 text-sm text-gray-500">Ref: {{ $payment->reference }}</p>
        </div>
        <a href="{{ route('team.payments.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all shadow-sm">
            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to History
        </a>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-50 bg-gray-50/30 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Amount</p>
                    <h2 class="text-4xl font-black text-gray-900">{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</h2>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Status</p>
                    @if($payment->status === 'completed' || $payment->status === 'Successful' || $payment->status === 'processing')
                        <span class="inline-flex items-center rounded-full bg-green-100 px-4 py-1.5 text-xs font-black text-green-700 uppercase tracking-wider">
                           Success
                        </span>
                    @elseif($payment->status === 'failed')
                        <span class="inline-flex items-center rounded-full bg-red-100 px-4 py-1.5 text-xs font-black text-red-700 uppercase tracking-wider">
                            Failed
                        </span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-blue-100 px-4 py-1.5 text-xs font-black text-blue-700 uppercase tracking-wider">
                            {{ $payment->status }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="p-8 space-y-10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div>
                        <h4 class="text-xs font-black text-brand-primary uppercase tracking-widest mb-4">Payment Info</h4>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-[10px] font-bold text-gray-400 uppercase">Payment Date</dt>
                                <dd class="text-sm font-semibold text-gray-900">{{ $payment->payment_date->format('F d, Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-[10px] font-bold text-gray-400 uppercase">Description</dt>
                                <dd class="text-sm font-medium text-gray-600">{{ $payment->description ?: 'No description provided.' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <h4 class="text-xs font-black text-brand-primary uppercase tracking-widest mb-4">Beneficiary Bank</h4>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-[10px] font-bold text-gray-400 uppercase">Account Name</dt>
                                <dd class="text-sm font-semibold text-gray-900">{{ $payment->account_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-[10px] font-bold text-gray-400 uppercase">Account Details</dt>
                                <dd class="text-sm font-medium text-gray-600 font-mono">{{ $payment->account_number }} ({{ $payment->bank_name ?? $payment->bank_code }})</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="pt-8 border-t border-gray-50 hidden sm:block">
                    <div class="bg-gray-900 rounded-2xl p-6 text-white flex items-center justify-between">
                         <div class="flex items-center gap-4">
                            <div class="h-12 w-12 rounded-xl bg-brand-primary/20 flex items-center justify-center text-2xl">
                                🔒
                            </div>
                            <div>
                                <p class="text-xs font-bold text-brand-primary uppercase tracking-widest">Secure Transfer</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">Verified SafeHaven Disbursement Session</p>
                            </div>
                         </div>
                         <div class="text-right">
                             <p class="text-[10px] font-mono text-gray-500 uppercase tracking-tighter">Session ID</p>
                             <p class="text-[10px] font-mono text-gray-400">{{ $payment->session_id }}</p>
                         </div>
                    </div>
                </div>
            </div>
        </div>
        
        <p class="mt-6 text-center text-xs text-gray-400 font-medium">
            Contact support if you have any questions about this disbursement.
        </p>
    </div>
</div>
@endsection
