@extends('teamportal::layouts.app')

@section('title', 'Invoice ' . $invoice->invoice_number)

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
  <div class="mb-6 flex items-center justify-between">
      <div>
          <h1 class="text-2xl font-bold text-gray-900">Invoice {{ $invoice->invoice_number }}</h1>
          <p class="mt-1 text-sm text-gray-600">Created on {{ $invoice->created_at->format('M d, Y') }}</p>
      </div>
  </div>

  <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
      <!-- Invoice Status Bar -->
      <div class="bg-gray-50 px-8 py-4 border-b border-gray-200 flex items-center justify-between">
          <div class="flex items-center gap-x-4">
              <span class="text-sm text-gray-600">Status:</span>
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                @if($invoice->status === 'paid') bg-green-100 text-green-800 
                @elseif($invoice->status === 'overdue') bg-red-100 text-red-800 
                @else bg-blue-100 text-blue-800 @endif">
                {{ ucfirst($invoice->status) }}
              </span>
          </div>
      </div>

      <div class="p-8">
          <div class="flex justify-between items-start mb-12">
              <div>
                  <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ config('app.name') }}</h2>
                  <div class="text-gray-500 text-sm">
                      <p>Billing Department</p>
                  </div>
              </div>
              <div class="text-right">
                  <h2 class="text-3xl font-bold text-gray-900 mb-2">INVOICE</h2>
                  <div class="text-gray-600">
                      <p class="mb-1"><span class="font-medium">Date:</span> {{ $invoice->issue_date->format('M d, Y') }}</p>
                      <p class="mb-1"><span class="font-medium">Due Date:</span> {{ $invoice->due_date->format('M d, Y') }}</p>
                  </div>
              </div>
          </div>

          <!-- Bill To -->
          <div class="mb-12">
              <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wider mb-2">Bill To</h3>
              <div class="text-gray-900 font-medium text-lg">{{ $invoice->client_name }}</div>
              <div class="text-gray-600 mt-1">
                  <p>{{ $invoice->client_email }}</p>
                  <p>{{ $invoice->client_phone }}</p>
                  <div class="whitespace-pre-line">{{ $invoice->client_address }}</div>
              </div>
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
                      @foreach($invoice->items as $item)
                      <tr>
                          <td class="py-4 text-gray-900">
                              <p class="font-medium">{{ $item->description }}</p>
                          </td>
                          <td class="py-4 text-right text-gray-600">{{ $item->quantity }}</td>
                          <td class="py-4 text-right text-gray-600">{{ $invoice->currency->symbol ?? '$' }}{{ number_format($item->unit_price, 2) }}</td>
                          <td class="py-4 text-right text-gray-900 font-medium">{{ $invoice->currency->symbol ?? '$' }}{{ number_format($item->total, 2) }}</td>
                      </tr>
                      @endforeach
                  </tbody>
              </table>
          </div>

          <!-- Totals -->
          <div class="flex justify-end">
              <div class="w-1/3 space-y-3">
                  <div class="flex justify-between text-gray-600">
                      <span>Subtotal</span>
                      <span>{{ $invoice->currency->symbol ?? '$' }}{{ number_format($invoice->subtotal, 2) }}</span>
                  </div>
                  
                  @if($invoice->tax_amount > 0)
                  <div class="flex justify-between text-gray-600">
                      <span>Tax ({{ $invoice->tax_rate }}%)</span>
                      <span>{{ $invoice->currency->symbol ?? '$' }}{{ number_format($invoice->tax_amount, 2) }}</span>
                  </div>
                  @endif
                  
                  @if($invoice->discount_amount > 0)
                  <div class="flex justify-between text-gray-600">
                      <span>Discount</span>
                      <span class="text-red-600">-{{ $invoice->currency->symbol ?? '$' }}{{ number_format($invoice->discount_amount, 2) }}</span>
                  </div>
                  @endif

                  <div class="flex justify-between text-lg font-bold text-gray-900 border-t border-gray-200 pt-3">
                      <span>Total</span>
                      <span class="text-brand-primary">{{ $invoice->currency->symbol ?? '$' }}{{ number_format($invoice->total_amount, 2) }}</span>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>
@endsection
