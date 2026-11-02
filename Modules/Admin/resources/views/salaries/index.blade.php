@extends('admin::layouts.app')

@section('title', 'Salary Disbursements')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 space-y-6">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold leading-7 text-white sm:truncate sm:text-3xl sm:tracking-tight">Salary Disbursements</h1>
            <p class="mt-2 text-sm text-gray-400">Manage team salary payments and disbursement records.</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="{{ route('admin.accounting.salaries.create') }}" class="block rounded-xl bg-emerald-600 px-4 py-2.5 text-center text-sm font-bold text-white shadow-lg hover:bg-emerald-500 transition-colors">
                New Disbursement
            </a>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="border-b border-white/10">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <a href="{{ route('admin.accounting.payroll.index') }}" class="border-transparent text-gray-400 hover:border-gray-500 hover:text-gray-200 whitespace-nowrap border-b-2 py-3 px-1 text-sm font-medium flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Staff Payroll & Active Salaries
            </a>
            <a href="{{ route('admin.accounting.salaries.index') }}" class="border-emerald-500 text-emerald-400 whitespace-nowrap border-b-2 py-3 px-1 text-sm font-bold flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
                Salary Disbursements (History)
            </a>
        </nav>
    </div>

    <!-- Filters -->
    <div class="mt-4 mb-4">
        <form action="{{ route('admin.accounting.salaries.index') }}" method="GET" class="flex gap-4">
             <select name="status" class="block rounded-xl bg-[#0a1612] border border-white/10 py-2.5 pl-3 pr-10 text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="successful" {{ request('status') == 'successful' ? 'selected' : '' }}>Successful</option>
                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
        </form>
    </div>

    <div class="mt-4 flow-root">
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden shadow-xl rounded-2xl bg-[#10231C]/60 backdrop-blur-xl border border-white/10">
                    <table class="min-w-full divide-y divide-white/5">
                        <thead class="bg-white/5">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-xs font-bold text-gray-400 uppercase sm:pl-6">Team Member</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold text-gray-400 uppercase">Amount</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold text-gray-400 uppercase">Bank Details</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold text-gray-400 uppercase">Status</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold text-gray-400 uppercase">Date</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold text-gray-400 uppercase">Reference</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6 text-right text-xs font-bold text-gray-400 uppercase">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($payments as $payment)
                            <tr class="hover:bg-white/[0.02] transition">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-semibold text-white sm:pl-6">
                                    {{ $payment->user?->name ?? 'Team Member' }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-emerald-400 font-bold font-mono">
                                    {{ $payment->currency }} {{ number_format($payment->amount, 2) }}
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-300">
                                    <div class="font-semibold text-white">{{ $payment->account_name }}</div>
                                    <div class="text-xs text-gray-400">{{ $payment->bank_name ?? $payment->bank_code }} - {{ $payment->account_number }}</div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    @php
                                        $statusClass = match(strtolower($payment->status)) {
                                            'successful', 'completed' => 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30',
                                            'failed' => 'bg-rose-500/20 text-rose-300 border border-rose-500/30',
                                            'processing' => 'bg-blue-500/20 text-blue-300 border border-blue-500/30',
                                            default => 'bg-amber-500/20 text-amber-300 border border-amber-400/30',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold {{ $statusClass }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">
                                    {{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : $payment->created_at->format('M d, Y') }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-xs font-mono text-gray-400">
                                    {{ $payment->reference }}
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-bold sm:pr-6">
                                    <a href="{{ route('admin.accounting.salaries.show', $payment) }}" class="text-emerald-400 hover:text-emerald-300 transition-colors">View &rarr;</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-400">
                                    <div class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center mx-auto text-emerald-400 mb-3">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                    </div>
                                    No salary disbursements found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-6">
        {{ $payments->links() }}
    </div>
</div>
@endsection
