@extends('teamportal::layouts.app')

@section('title', 'Quote Details')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Quote {{ $quote->reference }}</h1>
            <p class="mt-1 text-xs sm:text-sm text-gray-400">
                For {{ $quote->client->company_name ?? $quote->client->name ?? 'Unknown Client' }}
            </p>
        </div>
        <div>
            <a href="{{ route('team.quotes.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-xs sm:text-sm font-semibold text-gray-300 hover:bg-white/10 hover:text-white transition-all shadow-sm">
                &larr; Back to Quotes
            </a>
        </div>
    </div>

    <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/10 p-6 sm:p-8">
        <dl class="divide-y divide-white/5 text-xs sm:text-sm">
            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                <dt class="font-bold text-gray-400 uppercase tracking-wider text-[11px]">Status</dt>
                <dd class="mt-1 text-white sm:col-span-2 sm:mt-0 font-semibold">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-500/10 text-blue-400 border border-blue-500/30">
                        {{ ucfirst($quote->status) }}
                    </span>
                </dd>
            </div>
            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                <dt class="font-bold text-gray-400 uppercase tracking-wider text-[11px]">Date Issued</dt>
                <dd class="mt-1 text-white sm:col-span-2 sm:mt-0">{{ $quote->created_at->format('F d, Y') }}</dd>
            </div>
            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                <dt class="font-bold text-gray-400 uppercase tracking-wider text-[11px]">Total Amount</dt>
                <dd class="mt-1 text-emerald-400 font-black text-lg sm:col-span-2 sm:mt-0">${{ number_format($quote->total, 2) }}</dd>
            </div>
             <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                <dt class="font-bold text-gray-400 uppercase tracking-wider text-[11px]">Valid Until</dt>
                <dd class="mt-1 text-gray-300 sm:col-span-2 sm:mt-0">{{ $quote->valid_until ? $quote->valid_until->format('F d, Y') : 'N/A' }}</dd>
            </div>
        </dl>
    </div>
</div>
@endsection
