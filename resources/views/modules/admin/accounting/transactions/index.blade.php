@extends('admin::layouts.app')

@section('title', 'Transactions')

@section('content')
<div class="mb-6">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl font-bold text-white">Transactions</h1>
            <p class="mt-1 text-sm text-gray-400">Record and track financial movements</p>
        </div>
        <div class="flex-shrink-0">
            <a href="{{ route('admin.accounting.transactions.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-brand-primary text-white text-base font-semibold rounded-lg hover:bg-brand-primary/90 transition-all shadow-lg hover:shadow-xl">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Record Transaction
            </a>
        </div>
    </div>
</div>

@if($transactions->isEmpty())
<div class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl p-12 text-center">
    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
    </svg>
    <h3 class="mt-4 text-lg font-semibold text-white">No transactions yet</h3>
    <p class="mt-2 text-sm text-gray-400">Record your first transaction to start tracking cash flow.</p>
    <div class="mt-6">
        <a href="{{ route('admin.accounting.transactions.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors">
            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Record Transaction
        </a>
    </div>
</div>
@else
<div class="bg-[#10231C]/60 rounded-2xl shadow-sm border border-white/10 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full whitespace-nowrap text-left text-sm">
            <thead class="bg-[#10231C]/60/[0.04]">
                <tr>
                    <th class="px-6 py-4 font-semibold text-white">Date</th>
                    <th class="px-6 py-4 font-semibold text-white">Description</th>
                    <th class="px-6 py-4 font-semibold text-white">Account</th>
                    <th class="px-6 py-4 font-semibold text-white">Ref #</th>
                    <th class="px-6 py-4 font-semibold text-white">Type</th>
                    <th class="px-6 py-4 font-semibold text-white">Amount</th>
                    <th class="px-6 py-4 font-semibold text-white text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5 bg-[#10231C]/60">
                @foreach($transactions as $transaction)
                <tr class="group hover:bg-white/[0.03]/50 transition-colors duration-200">
                    <td class="px-6 py-4 text-white font-medium whitespace-nowrap">
                        {{ $transaction->transaction_date->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-white">{{ $transaction->description }}</div>
                        @if($transaction->source !== 'manual')
                        <span class="inline-flex items-center rounded-md bg-white/[0.03] px-2 py-1 text-xs font-medium text-gray-400 ring-1 ring-inset ring-gray-500/10 mt-1">
                            {{ ucfirst($transaction->source) }}
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-400">
                        {{ $transaction->account ? $transaction->account->name : 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-gray-400">
                        <div class="font-mono text-xs">{{ $transaction->transaction_number }}</div>
                        @if($transaction->reference)
                        <div class="text-xs text-gray-400 mt-0.5">Ref: {{ $transaction->reference }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($transaction->type === 'credit')
                        <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                            Credit
                        </span>
                        @else
                        <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">
                            Debit
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-semibold {{ $transaction->type === 'debit' ? 'text-red-600' : 'text-green-600' }}">
                        {{ $transaction->type === 'debit' ? '-' : '+' }}${{ number_format($transaction->amount, 2) }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <a href="{{ route('admin.accounting.transactions.edit', $transaction) }}" class="text-gray-400 hover:text-brand-primary transition-colors" title="Edit">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </a>
                            <form action="{{ route('admin.accounting.transactions.destroy', $transaction) }}" method="POST" onsubmit="return confirm('Are you sure?');" class="inline-flex">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors" title="Delete">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $transactions->links() }}
</div>
@endif
@endsection
