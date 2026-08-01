@extends('clientportal::layouts.app')

@section('title', 'My Transactions')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Transactions</h1>
        <p class="mt-2 text-sm text-gray-600">View your payment history</p>
    </div>
</div>

@if($transactions->isEmpty())
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
    <div class="mx-auto h-16 w-16 rounded-full bg-blue-50 flex items-center justify-center mb-4">
        <svg class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </div>
    <h3 class="text-lg font-semibold text-gray-900">No transactions found</h3>
    <p class="mt-2 text-sm text-gray-500">You haven't made any payments yet.</p>
</div>
@else
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Actions</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($transactions as $transaction)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-brand-primary">
                        {{ $transaction->reference }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $transaction->created_at->format('M d, Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ Str::limit($transaction->description, 30) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $transaction->currency_code }} {{ number_format($transaction->amount, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $transaction->status === 'successful' ? 'bg-green-100 text-green-800' : 
                               ($transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('client.transactions.show', $transaction->id) }}" class="text-brand-primary hover:text-brand-primary/80">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    {{ $transactions->links() }}
</div>
@endif
@endsection
