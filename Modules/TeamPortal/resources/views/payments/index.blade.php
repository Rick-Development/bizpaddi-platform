@extends('teamportal::layouts.app')

@section('title', 'My Earnings')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold leading-6 text-gray-900">My Earnings</h1>
            <p class="mt-2 text-sm text-gray-700">A history of all salary payments and disbursements made to your account.</p>
        </div>
    </div>

    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-2xl bg-white p-6">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider sm:pl-6">Date</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Reference</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Description</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($payments as $payment)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                    {{ $payment->payment_date->format('M d, Y') }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 font-mono">
                                    {{ $payment->reference }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm font-bold text-gray-900">
                                    {{ $payment->currency }} {{ number_format($payment->amount, 2) }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    @if($payment->status === 'completed' || $payment->status === 'Successful' || $payment->status === 'processing')
                                        <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    @elseif($payment->status === 'failed')
                                        <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">
                                            Failed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-gray-50 px-2 py-1 text-xs font-medium text-gray-700 ring-1 ring-inset ring-gray-600/10">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-500 max-w-xs truncate">
                                    {{ $payment->description }}
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                    <a href="{{ route('team.payments.show', $payment->id) }}" class="text-brand-primary font-bold hover:text-brand-primary/80 transition-colors">Details</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-10 text-center text-sm text-gray-500 italic">No payment history found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    @if($payments->hasPages())
                    <div class="mt-4">
                        {{ $payments->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
