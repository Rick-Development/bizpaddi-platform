@extends('teamportal::layouts.app')

@section('title', 'Invoices')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6">
  <div class="sm:flex sm:items-center sm:justify-between">
    <div>
      <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Invoices</h1>
      <p class="mt-1 text-xs sm:text-sm text-gray-400">A list of all team and project invoices.</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="{{ route('team.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-semibold text-gray-300 transition-colors">
            &larr; Back to Dashboard
        </a>
    </div>
  </div>

  <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-3xl border border-white/10 shadow-2xl overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-white/5 text-left text-xs sm:text-sm">
        <thead class="bg-white/5">
          <tr>
            <th scope="col" class="py-4 pl-6 pr-3 font-bold text-gray-400 uppercase tracking-wider text-[11px]">Invoice #</th>
            <th scope="col" class="px-4 py-4 font-bold text-gray-400 uppercase tracking-wider text-[11px]">Client</th>
            <th scope="col" class="px-4 py-4 font-bold text-gray-400 uppercase tracking-wider text-[11px]">Total</th>
            <th scope="col" class="px-4 py-4 font-bold text-gray-400 uppercase tracking-wider text-[11px]">Status</th>
            <th scope="col" class="px-4 py-4 font-bold text-gray-400 uppercase tracking-wider text-[11px]">Issue Date</th>
            <th scope="col" class="relative py-4 pl-3 pr-6 text-right">
              <span class="sr-only">View</span>
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
          @forelse($invoices as $invoice)
          <tr class="hover:bg-white/[0.03] transition-colors">
            <td class="whitespace-nowrap py-4 pl-6 pr-3 font-mono text-white text-xs">{{ $invoice->invoice_number }}</td>
            <td class="whitespace-nowrap px-4 py-4 text-gray-300">{{ $invoice->client_name }}</td>
            <td class="whitespace-nowrap px-4 py-4 font-black text-white">
                {{ $invoice->currency->symbol ?? '$' }}{{ number_format($invoice->total_amount, 2) }}
            </td>
            <td class="whitespace-nowrap px-4 py-4">
              <span class="inline-flex rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider
                @if($invoice->status === 'paid') bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 
                @elseif($invoice->status === 'overdue') bg-red-500/10 text-red-400 border border-red-500/30 
                @else bg-blue-500/10 text-blue-400 border border-blue-500/30 @endif">
                {{ ucfirst($invoice->status) }}
              </span>
            </td>
            <td class="whitespace-nowrap px-4 py-4 text-gray-400 text-xs">{{ $invoice->issue_date->format('M d, Y') }}</td>
            <td class="relative whitespace-nowrap py-4 pl-3 pr-6 text-right font-medium">
              <a href="{{ route('team.invoices.show', $invoice) }}" class="text-emerald-400 hover:text-emerald-300 font-bold text-xs">View &rarr;</a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="px-6 py-12 text-center text-xs text-gray-400 italic">No invoices found.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($invoices->hasPages())
    <div class="p-4 border-t border-white/10 bg-white/[0.02]">
         {{ $invoices->links() }}
    </div>
    @endif
  </div>
</div>
@endsection
