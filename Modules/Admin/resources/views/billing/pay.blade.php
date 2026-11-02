@extends('admin::layouts.app')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="text-center">
        <a href="{{ route('admin.billing.index') }}" class="text-xs font-bold text-emerald-400 hover:text-emerald-300 transition">&larr; Back to Subscription Overview</a>
        <h1 class="text-2xl font-extrabold text-white mt-2">Complete Payment</h1>
        <p class="text-xs text-gray-400">Pay your Bizpaddi subscription via direct bank transfer or virtual account.</p>
    </div>

    <!-- Virtual Account Payment Card -->
    <div class="bg-[#10231C]/60 backdrop-blur-xl rounded-3xl p-8 shadow-2xl border border-white/10 text-center space-y-6 relative overflow-hidden text-white">
        <!-- Top Accent Bar -->
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-emerald-500 via-[#F2A900] to-emerald-500"></div>

        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Amount Due</span>
            <span class="text-4xl font-extrabold text-white block mt-1">₦{{ number_format($payment->amount, 2) }}</span>
            <span class="text-xs text-emerald-300 font-semibold px-2.5 py-0.5 rounded-full bg-emerald-500/20 border border-emerald-500/30 inline-block mt-2">
                {{ $payment->metadata['plan_name'] ?? 'Bizpaddi Subscription' }}
            </span>
        </div>

        @if($vaNumber)
        <!-- Virtual Account Box -->
        <div class="bg-[#0a1612] rounded-2xl p-6 border border-white/10 text-left space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-white/5">
                <span class="text-xs font-bold uppercase text-gray-400">Bank Name</span>
                <span class="text-sm font-extrabold text-white">{{ $bankName }}</span>
            </div>

            <div class="flex items-center justify-between pb-3 border-b border-white/5">
                <span class="text-xs font-bold uppercase text-gray-400">Account Number</span>
                <div class="flex items-center space-x-2">
                    <span class="text-lg font-mono font-black text-emerald-400 tracking-wider select-all" id="va_number">{{ $vaNumber }}</span>
                    <button type="button" onclick="navigator.clipboard.writeText('{{ $vaNumber }}'); alert('Account number copied to clipboard!');"
                            class="px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-600 text-white hover:bg-emerald-500 transition">
                        Copy
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase text-gray-400">Beneficiary Name</span>
                <span class="text-xs font-semibold text-gray-200 text-right">{{ $accountName }}</span>
            </div>
        </div>
        <p class="text-[11px] text-gray-400">Transfer exactly <strong>₦{{ number_format($payment->amount, 2) }}</strong> to the account above. Your subscription activates automatically upon transfer settlement.</p>
        @else
        <div class="p-4 rounded-xl bg-amber-500/20 text-amber-300 border border-amber-400/30 text-xs">
            Virtual account number generation in progress. Check connection with Billway API keys.
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="space-y-3 pt-2">
            <form action="{{ route('admin.billing.verify-now') }}" method="POST">
                @csrf
                <input type="hidden" name="reference" value="{{ $payment->reference }}">
                <button type="submit" class="w-full py-3.5 rounded-2xl text-sm font-bold bg-[#F2A900] hover:bg-amber-300 text-[#08110e] shadow-lg transition flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>I Have Made This Transfer</span>
                </button>
            </form>

            <span class="block text-[11px] text-gray-400">
                Payment Reference: <span class="font-mono font-bold text-gray-300">{{ $payment->reference }}</span>
            </span>
        </div>
    </div>
</div>
@endsection
