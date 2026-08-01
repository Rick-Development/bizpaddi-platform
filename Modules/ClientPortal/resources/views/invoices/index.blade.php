@extends('clientportal::layouts.app')

@section('title', 'My Invoices')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Invoices</h1>
        <p class="mt-2 text-sm text-gray-600">View and manage your billing history</p>
    </div>
</div>

@if($invoices->isEmpty())
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
    <div class="mx-auto h-16 w-16 rounded-full bg-yellow-50 flex items-center justify-center mb-4">
        <svg class="h-8 w-8 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
    </div>
    <h3 class="text-lg font-semibold text-gray-900">No invoices found</h3>
    <p class="mt-2 text-sm text-gray-500">You don't have any invoices generated yet.</p>
</div>
@else
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice #</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Issue Date</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Actions</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($invoices as $invoice)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-brand-primary">
                        <a href="{{ route('client.invoices.show', $invoice->id) }}">
                            {{ $invoice->invoice_number }}
                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $invoice->issue_date ? $invoice->issue_date->format('M d, Y') : 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $invoice->currency ? $invoice->currency->symbol : '$' }}{{ number_format($invoice->total_amount, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 
                               ($invoice->status === 'sent' ? 'bg-blue-100 text-blue-800' : 
                               ($invoice->status === 'overdue' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('client.invoices.show', $invoice->id) }}" class="text-brand-primary hover:text-brand-primary/80">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    {{ $invoices->links() }}
</div>
@endif
@endsection
