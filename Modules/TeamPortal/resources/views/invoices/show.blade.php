@extends('teamportal::layouts.app')

@section('title', 'Invoice ' . $invoice->invoice_number)

@section('content')
<div class="px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto space-y-6">
  <div class="flex items-center justify-between">
      <div>
          <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Invoice {{ $invoice->invoice_number }}</h1>
          <p class="mt-1 text-xs sm:text-sm text-gray-400">Created on {{ $invoice->created_at->format('M d, Y') }}</p>
      </div>
      <a href="{{ route('team.invoices.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-xs sm:text-sm font-semibold text-gray-300 hover:bg-white/10 hover:text-white transition-all shadow-sm">
          &larr; Back to Invoices
      </a>
  </div>

  <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/10 overflow-hidden">
      <!-- Invoice Status Bar -->
      <div class="bg-[#08110e]/40 px-6 sm:px-8 py-4 border-b border-white/10 flex items-center justify-between">
          <div class="flex items-center gap-x-4">
              <span class="text-xs text-gray-400 uppercase tracking-wider font-bold">Status:</span>
              <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider
                @if($invoice->status === 'paid') bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 
                @elseif($invoice->status === 'overdue') bg-red-500/10 text-red-400 border border-red-500/30 
                @else bg-blue-500/10 text-blue-400 border border-blue-500/30 @endif">
                {{ ucfirst($invoice->status) }}
              </span>
          </div>
      </div>

      <div class="p-6 sm:p-8 space-y-8">
          <div class="flex flex-col sm:flex-row justify-between items-start gap-6 border-b border-white/10 pb-8">
              <div>
                  <h2 class="text-2xl font-black text-white tracking-tight mb-1">{{ config('app.name') }}</h2>
                  <div class="text-gray-400 text-xs">
                      <p>Billing & Client Operations</p>
                  </div>
              </div>
              <div class="sm:text-right">
                  <h2 class="text-3xl font-black text-white mb-2">INVOICE</h2>
                  <div class="text-gray-400 text-xs space-y-1">
                      <p><span class="font-bold text-gray-300">Date:</span> {{ $invoice->issue_date->format('M d, Y') }}</p>
                      <p><span class="font-bold text-gray-300">Due Date:</span> {{ $invoice->due_date->format('M d, Y') }}</p>
                  </div>
              </div>
          </div>

          <!-- Bill To -->
          <div class="p-5 rounded-2xl bg-[#08110e]/60 border border-white/10">
              <h3 class="text-emerald-400 text-[10px] font-bold uppercase tracking-widest mb-2">Bill To</h3>
              <div class="text-white font-bold text-base">{{ $invoice->client_name }}</div>
              <div class="text-gray-400 text-xs mt-1 space-y-0.5">
                  <p>{{ $invoice->client_email }}</p>
                  <p>{{ $invoice->client_phone }}</p>
                  <div class="whitespace-pre-line mt-1">{{ $invoice->client_address }}</div>
              </div>
          </div>

          <!-- Items Table -->
          <div class="overflow-x-auto">
              <table class="w-full text-left text-xs sm:text-sm">
                  <thead>
                      <tr class="border-b border-white/10 bg-white/5">
                          <th class="py-3 px-4 font-bold text-gray-400 uppercase tracking-wider text-[11px]">Description</th>
                          <th class="text-right py-3 px-4 font-bold text-gray-400 uppercase tracking-wider text-[11px]">Qty</th>
                          <th class="text-right py-3 px-4 font-bold text-gray-400 uppercase tracking-wider text-[11px]">Price</th>
                          <th class="text-right py-3 px-4 font-bold text-gray-400 uppercase tracking-wider text-[11px]">Total</th>
                      </tr>
                  </thead>
                  <tbody class="divide-y divide-white/5">
                      @foreach($invoice->items as $item)
                      <tr>
                          <td class="py-4 px-4 text-white font-medium">
                              <p>{{ $item->description }}</p>
                          </td>
                          <td class="py-4 px-4 text-right text-gray-300">{{ $item->quantity }}</td>
                          <td class="py-4 px-4 text-right text-gray-300">{{ $invoice->currency->symbol ?? '$' }}{{ number_format($item->unit_price, 2) }}</td>
                          <td class="py-4 px-4 text-right text-white font-bold">{{ $invoice->currency->symbol ?? '$' }}{{ number_format($item->total, 2) }}</td>
                      </tr>
                      @endforeach
                  </tbody>
              </table>
          </div>

          <!-- Totals -->
          <div class="flex justify-end pt-4 border-t border-white/10">
              <div class="w-full sm:w-80 space-y-3 text-xs sm:text-sm">
                  <div class="flex justify-between text-gray-400">
                      <span>Subtotal</span>
                      <span class="text-white font-semibold">{{ $invoice->currency->symbol ?? '$' }}{{ number_format($invoice->subtotal, 2) }}</span>
                  </div>
                  
                  @if($invoice->tax_amount > 0)
                  <div class="flex justify-between text-gray-400">
                      <span>Tax ({{ $invoice->tax_rate }}%)</span>
                      <span class="text-white font-semibold">{{ $invoice->currency->symbol ?? '$' }}{{ number_format($invoice->tax_amount, 2) }}</span>
                  </div>
                  @endif
                  
                  @if($invoice->discount_amount > 0)
                  <div class="flex justify-between text-gray-400">
                      <span>Discount</span>
                      <span class="text-red-400 font-semibold">-{{ $invoice->currency->symbol ?? '$' }}{{ number_format($invoice->discount_amount, 2) }}</span>
                  </div>
                  @endif
                  
                  <div class="flex justify-between text-base font-black text-white pt-3 border-t border-white/10">
                      <span>Total</span>
                      <span class="text-emerald-400">{{ $invoice->currency->symbol ?? '$' }}{{ number_format($invoice->total_amount, 2) }}</span>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>
@endsection
