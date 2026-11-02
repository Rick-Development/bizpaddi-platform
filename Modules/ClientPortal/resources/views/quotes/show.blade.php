@extends('clientportal::layouts.app')

@section('title', 'Quote ' . $quote->quote_number)

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-x-4">
        <a href="{{ route('client.orders.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $quote->quote_number }}</h1>
            <p class="mt-1 text-sm text-gray-600">Date: {{ $quote->issue_date ? $quote->issue_date->format('M d, Y') : 'N/A' }} • Valid until: {{ $quote->valid_until ? $quote->valid_until->format('M d, Y') : 'N/A' }}</p>
        </div>
        <div class="ml-auto flex items-center gap-3">
             <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                {{ $quote->status === 'accepted' ? 'bg-green-100 text-green-800' : 
                   ($quote->status === 'sent' ? 'bg-blue-100 text-blue-800' : 
                   ($quote->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                {{ ucfirst($quote->status) }}
            </span>
            <!-- PDF Download -->
            <a href="{{ route('client.quotes.pdf', $quote->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary">
                <svg class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Download PDF
            </a>

            @if(in_array($quote->status, ['sent', 'accepted']))
            <a href="{{ route('client.quotes.pay', $quote->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a1 1 0 11-2 0 1 1 0 012 0z" />
                </svg>
                Pay Now
            </a>
            @endif
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <!-- Quote Header -->
    <div class="px-6 py-8 border-b border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">From</h3>
                <div class="text-sm text-gray-900 font-medium">{{ \Modules\Settings\Models\Setting::get('site_name', 'Bizpaddi') }}</div>
                <div class="text-sm text-gray-500 mt-1">
                    {!! nl2br(e(\Modules\Settings\Models\Setting::get('contact_address'))) !!}<br>
                    {{ \Modules\Settings\Models\Setting::get('contact_email') }}<br>
                    {{ \Modules\Settings\Models\Setting::get('contact_phone') }}
                </div>
            </div>
            <div>
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">To</h3>
                <div class="text-sm text-gray-900 font-medium">{{ $quote->client_name }}</div>
                <div class="text-sm text-gray-500 mt-1">
                    @if($quote->client_address)
                        {!! nl2br(e($quote->client_address)) !!}<br>
                    @endif
                    @if($quote->client_email)
                        {{ $quote->client_email }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quote Items -->
    <div class="px-6 py-8">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th scope="col" class="py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th scope="col" class="py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                    <th scope="col" class="py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                    <th scope="col" class="py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($quote->items as $item)
                <tr>
                    <td class="py-4 text-sm text-gray-900">{{ $item->description }}</td>
                    <td class="py-4 text-right text-sm text-gray-500">{{ $item->quantity }}</td>
                    <td class="py-4 text-right text-sm text-gray-500">{{ number_format($item->unit_price, 2) }}</td>
                    <td class="py-4 text-right text-sm text-gray-900 font-medium">{{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Quote Totals -->
    <div class="bg-gray-50 px-6 py-8">
        <div class="flex flex-col items-end space-y-3">
            <div class="flex justify-between w-full md:w-1/3 text-sm">
                <span class="text-gray-500 font-medium">Subtotal</span>
                <span class="text-gray-900">{{ number_format($quote->subtotal, 2) }}</span>
            </div>
            @if($quote->tax_amount > 0)
            <div class="flex justify-between w-full md:w-1/3 text-sm">
                <span class="text-gray-500 font-medium">Tax</span>
                <span class="text-gray-900">{{ number_format($quote->tax_amount, 2) }}</span>
            </div>
            @endif
             @if($quote->discount_amount > 0)
            <div class="flex justify-between w-full md:w-1/3 text-sm">
                <span class="text-gray-500 font-medium">Discount</span>
                <span class="text-red-600">-{{ number_format($quote->discount_amount, 2) }}</span>
            </div>
            @endif
            <div class="flex justify-between w-full md:w-1/3 text-lg font-bold pt-3 border-t border-gray-200">
                <span class="text-gray-900">Total</span>
                <span class="text-brand-primary">
                    {{ $quote->currency ? $quote->currency->symbol : '$' }}{{ number_format($quote->total_amount, 2) }}
                </span>
            </div>
        </div>
    </div>
    
    @if($quote->notes)
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Notes</h4>
        <p class="text-sm text-gray-600">{{ $quote->notes }}</p>
    </div>
    @endif
</div>
@endsection
