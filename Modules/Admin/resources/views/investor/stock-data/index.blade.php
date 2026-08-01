@extends('admin::layouts.app')

@section('title', 'Stock Data')

@section('content')
<div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
        <h1 class="text-3xl font-bold text-gray-900">Stock Data</h1>
        <p class="mt-2 text-sm text-gray-700">Track daily stock prices and market performance</p>
    </div>
    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
        <a href="{{ route('admin.investor.stock-data.create') }}" 
           class="block rounded-md bg-brand-primary px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-red-700">
            Add Stock Data
        </a>
    </div>
</div>

<div class="mt-8 flow-root">
    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Date</th>
                            <th class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Opening</th>
                            <th class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Closing</th>
                            <th class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">High</th>
                            <th class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Low</th>
                            <th class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Volume</th>
                            <th class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Change</th>
                            <th class="relative py-3.5 pl-3 pr-4 sm:pr-6"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($stockData as $data)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                <div class="flex items-center">
                                    <svg class="mr-2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $data->date->format('M d, Y') }}
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-right text-gray-900 font-medium">
                                ${{ number_format($data->opening_price, 2) }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-right text-gray-900 font-medium">
                                ${{ number_format($data->closing_price, 2) }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-right text-gray-500">
                                ${{ number_format($data->high_price, 2) }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-right text-gray-500">
                                ${{ number_format($data->low_price, 2) }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-right text-gray-500">
                                {{ number_format($data->volume) }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-right">
                                @if($data->price_change)
                                    <div class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium {{ $data->price_change >= 0 ? 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20' : 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20' }}">
                                        @if($data->price_change >= 0)
                                            <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            +${{ number_format($data->price_change, 2) }}
                                        @else
                                            <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            ${{ number_format($data->price_change, 2) }}
                                        @endif
                                        <span class="ml-1">({{ number_format($data->price_change_percent, 2) }}%)</span>
                                    </div>
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                <a href="{{ route('admin.investor.stock-data.edit', $data) }}" class="text-brand-primary hover:text-red-900 mr-4">Edit</a>
                                <form action="{{ route('admin.investor.stock-data.destroy', $data) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this stock data?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-3 py-12 text-center text-sm text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                                </svg>
                                <p class="mt-2">No stock data found.</p>
                                <a href="{{ route('admin.investor.stock-data.create') }}" class="mt-1 text-brand-primary hover:text-red-900">Add one</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($stockData->hasPages())
            <div class="mt-4">
                {{ $stockData->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
