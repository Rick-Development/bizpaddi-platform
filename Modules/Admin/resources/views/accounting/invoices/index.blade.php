@extends('admin::layouts.app')

@section('title', 'Invoices')

@section('content')
<div class="mb-6">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl font-bold text-gray-900">Invoices</h1>
            <p class="mt-1 text-sm text-gray-600">Manage and track your invoices</p>
        </div>
        <div class="flex-shrink-0">
            <a href="{{ route('admin.accounting.invoices.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-brand-primary text-white text-base font-semibold rounded-lg hover:bg-brand-primary/90 transition-all shadow-lg hover:shadow-xl">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Create Invoice
            </a>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden group hover:shadow-md transition-all duration-300">
        <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 rounded-full bg-gradient-to-br from-blue-50 to-blue-100 opacity-50 blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative z-10">
             <div class="flex items-center justify-between mb-4">
                 <div class="h-10 w-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                 </div>
                 <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full">+2.5%</span>
             </div>
             <p class="text-sm font-medium text-gray-500">Total Invoices</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $invoices->total() }}</p>
        </div>
    </div>
</div>

@if($invoices->isEmpty())
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
    </svg>
    <h3 class="mt-4 text-lg font-semibold text-gray-900">No invoices yet</h3>
    <p class="mt-2 text-sm text-gray-600">Create your first invoice to start billing clients.</p>
    <div class="mt-6">
        <a href="{{ route('admin.accounting.invoices.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors">
            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Create Invoice
        </a>
    </div>
</div>
@else
<div class="space-y-4">
    <div class="bg-gray-50/50 rounded-xl p-3 grid grid-cols-12 gap-4 text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:grid">
        <div class="col-span-4 pl-4">Client & Invoice</div>
        <div class="col-span-2">Amount</div>
        <div class="col-span-2">Due Date</div>
        <div class="col-span-2">Status</div>
        <div class="col-span-2 text-right pr-4">Actions</div>
    </div>

    @foreach($invoices as $invoice)
    <div class="group bg-white rounded-2xl p-5 shadow-sm hover:shadow-md border border-gray-100 transition-all duration-200 relative overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
             <!-- Status Stripe -->
            <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $invoice->status === 'paid' ? 'bg-green-500' : ($invoice->status === 'sent' ? 'bg-blue-500' : ($invoice->status === 'overdue' ? 'bg-red-500' : 'bg-gray-300')) }}"></div>

            <!-- Client & Info -->
            <div class="col-span-12 md:col-span-4 flex items-center gap-4 pl-2">
                <div class="h-12 w-12 rounded-xl bg-gray-100 flex items-center justify-center text-lg font-bold text-gray-600 shadow-inner">
                    {{ substr($invoice->client_name, 0, 1) }}
                </div>
                <div>
                    <div class="flex items-center gap-2 mb-1">
                         <span class="text-sm font-mono font-medium text-gray-500 bg-gray-50 px-2 py-0.5 rounded border border-gray-100">{{ $invoice->invoice_number }}</span>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 line-clamp-1">{{ $invoice->client_name }}</h3>
                    <p class="text-sm text-gray-500">{{ $invoice->client_email }}</p>
                </div>
            </div>

            <!-- Amount -->
            <div class="col-span-6 md:col-span-2">
                <p class="text-xs text-gray-500 md:hidden mb-1">Total Amount</p>
                <div class="text-lg font-bold text-gray-900">${{ number_format($invoice->total_amount, 2) }}</div>
            </div>

            <!-- Due Date -->
            <div class="col-span-6 md:col-span-2">
                <p class="text-xs text-gray-500 md:hidden mb-1">Due Date</p>
                <div class="flex items-center gap-2 text-sm text-gray-700">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                    {{ $invoice->due_date->format('M d, Y') }}
                </div>
            </div>

            <!-- Status -->
            <div class="col-span-6 md:col-span-2">
                <p class="text-xs text-gray-500 md:hidden mb-1">Status</p>
                @if($invoice->status === 'paid')
                <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700 ring-1 ring-inset ring-green-600/20">
                     <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span> Paid
                </span>
                @elseif($invoice->status === 'sent')
                <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700 ring-1 ring-inset ring-blue-700/10">
                     <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span> Sent
                </span>
                @elseif($invoice->status === 'overdue')
                <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700 ring-1 ring-inset ring-red-600/10">
                     <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span> Overdue
                </span>
                @else
                <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-50 px-3 py-1 text-xs font-semibold text-gray-600 ring-1 ring-inset ring-gray-500/10">
                    <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span> Draft
                </span>
                @endif
            </div>

            <!-- Actions -->
            <div class="col-span-12 md:col-span-2 flex items-center justify-end gap-2 md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-200">
                <a href="{{ route('admin.accounting.invoices.show', $invoice) }}" class="p-2 text-gray-500 hover:text-brand-primary bg-gray-50 hover:bg-brand-primary/5 rounded-lg transition-colors border border-gray-100" title="View Details">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </a>
                <a href="{{ route('admin.accounting.invoices.edit', $invoice) }}" class="p-2 text-gray-400 hover:text-brand-primary bg-gray-50 hover:bg-brand-primary/5 rounded-lg transition-colors border border-gray-100" title="Edit">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                </a>
                <form action="{{ route('admin.accounting.invoices.destroy', $invoice) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this invoice?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 text-gray-400 hover:text-red-500 bg-gray-50 hover:bg-red-50 rounded-lg transition-colors border border-gray-100" title="Delete">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-4">
    {{ $invoices->links() }}
</div>
@endif
@endsection
