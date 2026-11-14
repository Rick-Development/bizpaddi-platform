@extends('teamportal::layouts.app')

@section('title', 'Payment Details')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Payment Details</h1>
            <p class="mt-1 text-xs sm:text-sm text-gray-400 font-mono">Ref: {{ $payment->reference }}</p>
        </div>
        <a href="{{ route('team.payments.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-xs sm:text-sm font-semibold text-gray-300 hover:bg-white/10 hover:text-white transition-all shadow-sm">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to History
        </a>
    </div>

    <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/10 overflow-hidden">
        <div class="p-6 sm:p-8 border-b border-white/10 bg-[#08110e]/40 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Amount</p>
                <h2 class="text-3xl sm:text-4xl font-black text-white">{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</h2>
            </div>
            <div class="md:text-right">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Status</p>
                @if(in_array(strtolower($payment->status), ['completed', 'successful', 'processing', 'paid']))
                    <span class="inline-flex items-center rounded-full bg-emerald-500/10 border border-emerald-500/30 px-4 py-1.5 text-xs font-bold text-emerald-400 uppercase tracking-wider">
                       {{ ucfirst($payment->status) }}
                    </span>
                @elseif(strtolower($payment->status) === 'failed')
                    <span class="inline-flex items-center rounded-full bg-red-500/10 border border-red-500/30 px-4 py-1.5 text-xs font-bold text-red-400 uppercase tracking-wider">
                        Failed
                    </span>
                @else
                    <span class="inline-flex items-center rounded-full bg-white/5 border border-white/10 px-4 py-1.5 text-xs font-bold text-gray-300 uppercase tracking-wider">
                        {{ ucfirst($payment->status) }}
                    </span>
                @endif
            </div>
        </div>

        <div class="p-6 sm:p-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="p-5 rounded-2xl bg-[#08110e]/60 border border-white/10">
                    <h4 class="text-xs font-bold text-emerald-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Payment Info
                    </h4>
                    <dl class="space-y-4 text-xs sm:text-sm">
                        <div>
                            <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Payment Date</dt>
                            <dd class="text-white font-semibold mt-0.5">{{ $payment->payment_date->format('F d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Description</dt>
                            <dd class="text-gray-300 mt-0.5">{{ $payment->description ?: 'No description provided.' }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="p-5 rounded-2xl bg-[#08110e]/60 border border-white/10">
                    <h4 class="text-xs font-bold text-emerald-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        Beneficiary Bank
                    </h4>
                    <dl class="space-y-4 text-xs sm:text-sm">
                        <div>
                            <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Account Name</dt>
                            <dd class="text-white font-semibold mt-0.5">{{ $payment->account_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Account Details</dt>
                            <dd class="text-gray-300 font-mono mt-0.5">{{ $payment->account_number }} ({{ $payment->bank_name ?? $payment->bank_code }})</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="pt-6 border-t border-white/10">
                <div class="bg-[#08110e] rounded-2xl p-5 text-white flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border border-white/10">
                     <div class="flex items-center gap-4">
                        <div class="h-10 w-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-xl shrink-0">
                            🔒
                        </div>
                        <div>
                            <p class="text-xs font-bold text-emerald-400 uppercase tracking-wider">Secure Transfer</p>
                            <p class="text-[11px] text-gray-400 mt-0.5">Verified SafeHaven Disbursement Session</p>
                        </div>
                     </div>
                     <div class="sm:text-right">
                         <p class="text-[10px] font-mono text-gray-500 uppercase tracking-wider">Session ID</p>
                         <p class="text-[11px] font-mono text-gray-300 break-all">{{ $payment->session_id }}</p>
                     </div>
                </div>
            </div>
        </div>
    </div>
    
    <p class="text-center text-xs text-gray-500">
        Contact management if you have any questions about this disbursement.
    </p>
</div>
@endsection
