@extends('superadmin::layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">Billway Transactions</h1>
            <p class="text-sm text-gray-400">Audit log of all platform subscription charges and renewals.</p>
        </div>
    </div>

    <!-- Search / Filter -->
    <div class="bg-[#10231C]/90 backdrop-blur-md rounded-2xl p-4 shadow-xl border border-white/10 flex flex-col md:flex-row gap-3 items-center justify-between">
        <form method="GET" action="{{ route('super-admin.transactions.index') }}" class="flex-1 flex flex-wrap gap-3 w-full">
            <div class="relative flex-1 min-w-[240px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by reference, business name, or email..."
                       class="w-full pl-10 pr-4 py-2 text-sm rounded-xl border border-white/10 focus:outline-none focus:ring-2 focus:ring-[#0E5E4A] bg-white/5 text-white placeholder-gray-500">
                <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <select name="status" class="text-sm rounded-xl border border-white/10 px-3 py-2 bg-[#10231C] text-white focus:ring-2 focus:ring-[#0E5E4A]">
                <option value="">All Statuses</option>
                <option value="successful" {{ request('status') === 'successful' ? 'selected' : '' }}>Successful</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
            </select>

            <button type="submit" class="px-4 py-2 rounded-xl text-sm font-semibold bg-[#0E5E4A] text-white hover:bg-[#0c4e3e] border border-emerald-400/30 transition">
                Filter
            </button>
        </form>
    </div>

    <!-- Transactions Table -->
    <div class="bg-[#10231C]/90 backdrop-blur-md rounded-2xl shadow-xl border border-white/10 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-white/5 border-b border-white/10 text-xs font-bold text-gray-400 uppercase">
                    <tr>
                        <th class="py-3 px-6">Reference</th>
                        <th class="py-3 px-6">Business</th>
                        <th class="py-3 px-6">Plan</th>
                        <th class="py-3 px-6">Amount</th>
                        <th class="py-3 px-6">Gateway Ref</th>
                        <th class="py-3 px-6">Status</th>
                        <th class="py-3 px-6">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($transactions as $tx)
                    <tr class="hover:bg-white/[0.03] transition">
                        <td class="py-4 px-6 font-mono text-xs font-bold text-emerald-300">
                            {{ $tx->reference }}
                        </td>
                        <td class="py-4 px-6">
                            @if($tx->tenant)
                            <a href="{{ route('super-admin.tenants.show', $tx->tenant) }}" class="font-bold text-white hover:text-emerald-400">
                                {{ $tx->tenant->name }}
                            </a>
                            <span class="text-xs text-gray-400 block">{{ $tx->tenant->email }}</span>
                            @else
                            <span class="text-gray-400 italic">Unknown Business</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-xs text-gray-300 font-semibold">
                            {{ $tx->plan->name ?? 'Standard Plan' }}
                        </td>
                        <td class="py-4 px-6 font-extrabold text-white text-sm">
                            ₦{{ number_format($tx->amount, 2) }}
                        </td>
                        <td class="py-4 px-6 font-mono text-xs text-gray-400">
                            {{ Str::limit($tx->gateway_reference, 18) }}
                        </td>
                        <td class="py-4 px-6">
                            @if($tx->status === 'successful')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Successful</span>
                            @elseif($tx->status === 'pending')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">Pending</span>
                            @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-red-500/20 text-red-300 border border-red-500/30">{{ ucfirst($tx->status) }}</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-xs text-gray-400">
                            {{ $tx->created_at->format('M d, Y H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-500">No payment transactions recorded.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-white/10">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
