@extends('teamportal::layouts.app')

@section('title', 'Quote ' . $quote->quote_number)

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Quote {{ $quote->quote_number }}</h1>
            <p class="mt-1 text-sm text-gray-600">Created on {{ $quote->created_at->format('M d, Y') }}</p>
        </div>
        <div class="flex gap-x-3">
            <a href="{{ route('team.bdm.quotes.edit', $quote) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm">
                <svg class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                </svg>
                Edit
            </a>
            <form action="{{ route('team.bdm.quotes.destroy', $quote) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this quote?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-white border border-red-300 rounded-lg text-sm font-medium text-red-700 hover:bg-red-50 shadow-sm">
                    <svg class="h-5 w-5 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                    Delete
                </button>
            </form>
            <a href="{{ route('team.bdm.quotes.pdf', $quote) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm">
                <svg class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                PDF
            </a>
            
            @if($quote->status === 'draft')
            <form action="{{ route('team.bdm.quotes.send', $quote) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 shadow-sm">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                    </svg>
                    Send Quote
                </button>
            </form>
            @endif

            @if($quote->status !== 'approved' && $quote->status !== 'converted')
            <form action="{{ route('team.bdm.quotes.approve', $quote) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-green-700 shadow-sm">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    Approve
                </button>
            </form>
            @endif

            @if($quote->status === 'approved')
            <form action="{{ route('team.bdm.quotes.convert', $quote) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-[#FB4C4B] border border-transparent rounded-lg text-sm font-medium text-white hover:bg-[#D93837] shadow-sm">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    Convert to Invoice
                </button>
            </form>
            @endif
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <!-- Quote Status Bar -->
    <div class="bg-gray-50 px-8 py-4 border-b border-gray-200 flex items-center justify-between">
        <div class="flex items-center gap-x-4">
            <span class="text-sm text-gray-600">Status:</span>
            @if($quote->status === 'approved')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Approved</span>
            @elseif($quote->status === 'sent')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Sent</span>
            @elseif($quote->status === 'converted')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">Converted</span>
            @elseif($quote->status === 'rejected')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Rejected</span>
            @elseif($quote->status === 'expired')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Expired</span>
            @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Draft</span>
            @endif
        </div>
        
        @if($quote->converted_to_invoice_id)
            <a href="{{ route('team.bdm.invoices.show', $quote->converted_to_invoice_id) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                View Invoice
            </a>
        @endif
    </div>

    <div class="relative p-8 overflow-hidden">
        <!-- Watermark -->
        @if(\Modules\Settings\Models\Setting::get('watermark_logo'))
        <div class="absolute inset-0 z-0 flex items-center justify-center pointer-events-none overflow-hidden">
            <img src="{{ asset('storage/' . \Modules\Settings\Models\Setting::get('watermark_logo')) }}" 
                 alt="Watermark" 
                 style="width: 80%; height: auto; opacity: 0.1; transform: rotate(-45deg);"
                 class="object-contain">
        </div>
        @endif

        <div class="relative z-10">
            <!-- Header -->
            <div class="flex justify-between items-start mb-12">
                <div>
                    @if(\Modules\Settings\Models\Setting::get('company_logo'))
                        <img src="{{ asset('storage/' . \Modules\Settings\Models\Setting::get('company_logo')) }}" alt="{{ \Modules\Settings\Models\Setting::get('site_name', 'Company Logo') }}" class="h-16 w-auto mb-4 object-contain">
                    @else
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ \Modules\Settings\Models\Setting::get('site_name', config('app.name')) }}</h2>
                    @endif
                    
                    <div class="text-gray-500 text-sm">
                        <p class="whitespace-pre-line">{{ \Modules\Settings\Models\Setting::get('contact_address') }}</p>
                        <p>{{ \Modules\Settings\Models\Setting::get('contact_email') }}</p>
                        <p>{{ \Modules\Settings\Models\Setting::get('contact_phone') }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">QUOTE</h2>
                    <div class="text-gray-600">
                        <p class="mb-1"><span class="font-medium">Date:</span> {{ $quote->issue_date->format('M d, Y') }}</p>
                        <p class="mb-1"><span class="font-medium">Valid Until:</span> {{ $quote->valid_until->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Bill To & Project Details -->
            <div class="flex justify-between mb-12">
                <div class="w-5/12">
                    <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wider mb-2">Bill To</h3>
                    <div class="text-gray-900 font-medium text-lg">{{ $quote->client_name }}</div>
                    <div class="text-gray-600 mt-1">
                        <p>{{ $quote->client_email }}</p>
                        <p>{{ $quote->client_phone }}</p>
                        <div class="whitespace-pre-line">{{ $quote->client_address }}</div>
                    </div>
                </div>
                @if($quote->serviceCategory || $quote->service)
                <div class="w-5/12 text-right">
                    <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wider mb-2">Project Details</h3>
                    <div class="space-y-1">
                        @if($quote->serviceCategory)
                            <p class="text-gray-600"><span class="font-medium text-gray-900">Category:</span> {{ $quote->serviceCategory->name }}</p>
                        @endif
                        @if($quote->service)
                            <p class="text-gray-600"><span class="font-medium text-gray-900">Service:</span> {{ $quote->service->name }}</p>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Items Table -->
            <div class="mb-12">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 text-sm font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="text-right py-3 text-sm font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                            <th class="text-right py-3 text-sm font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="text-right py-3 text-sm font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($quote->items as $item)
                        <tr>
                            <td class="py-4 text-gray-900">
                                <p class="font-bold mb-1">{{ $item->title }}</p>
                                <div class="text-sm text-gray-500">{!! $item->description !!}</div>
                            </td>
                            <td class="py-4 text-right text-gray-600">{{ $item->quantity }}</td>
                            <td class="py-4 text-right text-gray-600">{{ $quote->currency->symbol ?? '$' }}{{ number_format($item->unit_price, 2) }}</td>
                            <td class="py-4 text-right text-gray-900 font-medium">{{ $quote->currency->symbol ?? '$' }}{{ number_format($item->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Totals -->
            <div class="flex justify-end mb-12">
                <div class="w-1/3 space-y-3">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span>{{ $quote->currency->symbol ?? '$' }}{{ number_format($quote->subtotal, 2) }}</span>
                    </div>
                    
                    @if($quote->tax_amount > 0)
                    <div class="flex justify-between text-gray-600">
                        <span>Tax ({{ $quote->tax_rate }}%)</span>
                        <span>{{ $quote->currency->symbol ?? '$' }}{{ number_format($quote->tax_amount, 2) }}</span>
                    </div>
                    @endif
                    
                    @if($quote->discount_amount > 0)
                    <div class="flex justify-between text-gray-600">
                        <span>Discount</span>
                        <span class="text-red-600">-{{ $quote->currency->symbol ?? '$' }}{{ number_format($quote->discount_amount, 2) }}</span>
                    </div>
                    @endif

                    <div class="flex justify-between text-lg font-bold text-gray-900 border-t border-gray-200 pt-3">
                        <span>Total</span>
                        <span class="text-[#FB4C4B]">{{ $quote->currency->symbol ?? '$' }}{{ number_format($quote->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Notes & Terms -->
            <div class="grid grid-cols-2 gap-8 text-sm text-gray-600 border-t border-gray-200 pt-8 mt-12">
                <div>
                    <h4 class="font-medium text-gray-900 mb-2 uppercase tracking-wider text-xs">Notes</h4>
                    <div class="prose prose-sm max-w-none text-gray-600">
                        {!! $quote->notes ?? \Modules\Settings\Models\Setting::get('default_quote_notes', '<p>No additional notes</p>') !!}
                    </div>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 mb-2 uppercase tracking-wider text-xs">Terms & Conditions</h4>
                    <div class="prose prose-sm max-w-none text-gray-600">
                        {!! $quote->terms_conditions ?? \Modules\Settings\Models\Setting::get('default_quote_terms', '<p>Standard terms apply.</p>') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
