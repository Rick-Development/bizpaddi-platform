@extends('teamportal::layouts.app')

@section('title', 'My Earnings')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">My Earnings</h1>
            <p class="mt-1 text-xs sm:text-sm text-gray-400">A history of all salary payments and disbursements made to your account.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('team.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-semibold text-gray-300 transition-colors">
                &larr; Back to Dashboard
            </a>
        </div>
    </div>

    <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-3xl border border-white/10 shadow-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/5 text-left text-xs sm:text-sm">
                <thead class="bg-white/5">
                    <tr>
                        <th scope="col" class="py-4 pl-6 pr-3 font-bold text-gray-400 uppercase tracking-wider text-[11px]">Date</th>
                        <th scope="col" class="px-4 py-4 font-bold text-gray-400 uppercase tracking-wider text-[11px]">Reference</th>
                        <th scope="col" class="px-4 py-4 font-bold text-gray-400 uppercase tracking-wider text-[11px]">Amount</th>
                        <th scope="col" class="px-4 py-4 font-bold text-gray-400 uppercase tracking-wider text-[11px]">Status</th>
                        <th scope="col" class="px-4 py-4 font-bold text-gray-400 uppercase tracking-wider text-[11px]">Description</th>
                        <th scope="col" class="relative py-4 pl-3 pr-6 text-right">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($payments as $payment)
                    <tr class="hover:bg-white/[0.03] transition-colors">
                        <td class="whitespace-nowrap py-4 pl-6 pr-3 font-semibold text-white">
                            {{ $payment->payment_date->format('M d, Y') }}
                        </td>
                        <td class="whitespace-nowrap px-4 py-4 text-gray-300 font-mono text-xs">
                            {{ $payment->reference }}
                        </td>
                        <td class="whitespace-nowrap px-4 py-4 font-black text-white">
                            {{ $payment->currency }} {{ number_format($payment->amount, 2) }}
                        </td>
                        <td class="whitespace-nowrap px-4 py-4">
                            @if(in_array(strtolower($payment->status), ['completed', 'successful', 'processing', 'paid']))
                                <span class="inline-flex items-center rounded-full bg-emerald-500/10 px-2.5 py-1 text-[11px] font-bold text-emerald-400 border border-emerald-500/30">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            @elseif(strtolower($payment->status) === 'failed')
                                <span class="inline-flex items-center rounded-full bg-red-500/10 px-2.5 py-1 text-[11px] font-bold text-red-400 border border-red-500/30">
                                    Failed
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-white/5 px-2.5 py-1 text-[11px] font-bold text-gray-300 border border-white/10">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-gray-400 max-w-xs truncate text-xs">
                            {{ $payment->description ?: '—' }}
                        </td>
                        <td class="whitespace-nowrap py-4 pl-3 pr-6 text-right font-semibold">
                            <a href="{{ route('team.payments.show', $payment->id) }}" class="text-emerald-400 font-bold hover:text-emerald-300 transition-colors text-xs">Details &rarr;</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center text-sm text-gray-400 italic">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <span class="text-2xl">💸</span>
                                <p class="text-gray-400 font-medium text-xs sm:text-sm">No payment history found.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($payments->hasPages())
        <div class="p-4 border-t border-white/10 bg-white/[0.02]">
            {{ $payments->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
