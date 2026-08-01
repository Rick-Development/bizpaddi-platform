@extends('clientportal::layouts.app')

@section('title', 'My Quotes')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">My Quotes</h1>
        <p class="mt-2 text-sm text-gray-600">Review and approve your project quotes</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    @if($quotes->isEmpty())
        <div class="p-12 text-center">
            <div class="mx-auto h-16 w-16 rounded-full bg-blue-50 flex items-center justify-center mb-4">
                <svg class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900">No quotes found</h3>
            <p class="mt-2 text-sm text-gray-500">You haven't received any quotes yet.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quote #</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($quotes as $quote)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-brand-primary">
                            {{ $quote->quote_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $quote->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                            {{ $quote->currency->symbol ?? '$' }}{{ number_format($quote->total_amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $quote->status === 'paid' ? 'bg-green-100 text-green-800' : 
                                   ($quote->status === 'sent' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($quote->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('client.quotes.show', $quote->id) }}" class="text-brand-primary hover:text-brand-primary/80">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $quotes->links() }}
        </div>
    @endif
</div>
@endsection
