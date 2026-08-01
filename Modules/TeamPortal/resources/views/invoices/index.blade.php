@extends('teamportal::layouts.app')

@section('title', 'Invoices')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
  <div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
      <h1 class="text-xl font-semibold text-gray-900">Invoices</h1>
      <p class="mt-2 text-sm text-gray-700">A list of all invoices.</p>
    </div>
  </div>
  <div class="mt-8 flex flex-col">
    <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
      <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
          <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Invoice #</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Client</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Total</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Issue Date</th>
                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                  <span class="sr-only">View</span>
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
              @forelse($invoices as $invoice)
              <tr>
                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $invoice->invoice_number }}</td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $invoice->client_name }}</td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                    {{ $invoice->currency->symbol ?? '$' }}{{ number_format($invoice->total_amount, 2) }}
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                  <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 
                    @if($invoice->status === 'paid') bg-green-100 text-green-800 
                    @elseif($invoice->status === 'overdue') bg-red-100 text-red-800 
                    @else bg-blue-100 text-blue-800 @endif">
                    {{ ucfirst($invoice->status) }}
                  </span>
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $invoice->issue_date->format('M d, Y') }}</td>
                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                  <a href="{{ route('team.invoices.show', $invoice) }}" class="text-brand-primary hover:text-brand-primary/80">View</a>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No invoices found.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="mt-4">
             {{ $invoices->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
