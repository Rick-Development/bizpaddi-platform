@extends('admin::layouts.app')

@section('title', 'Salary Disbursements')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">Salary Disbursements</h1>
            <p class="mt-2 text-sm text-gray-500">Manage team salary payments via SafeHaven.</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="{{ route('admin.accounting.salaries.create') }}" class="block rounded-md bg-brand-primary px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-brand-primary/80 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary transition-colors">
                New Disbursement
            </a>
        </div>
    </div>

    <!-- Filters (Simplified) -->
    <div class="mt-4 mb-4">
        <form action="{{ route('admin.accounting.salaries.index') }}" method="GET" class="flex gap-4">
             <select name="status" class="block rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm sm:leading-6" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="successful" {{ request('status') == 'successful' ? 'selected' : '' }}>Successful</option>
                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
        </form>
    </div>

    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg bg-white">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Team Member</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Amount</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Bank Details</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Date</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Reference</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($payments as $payment)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                    {{ $payment->user->name ?? "N\A"}}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 font-mono">
                                    {{ $payment->currency }} {{ number_format($payment->amount, 2) }}
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-500">
                                    <div class="font-medium text-gray-900">{{ $payment->account_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $payment->bank_name ?? $payment->bank_code }} - {{ $payment->account_number }}</div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    @php
                                        $statusClass = match($payment->status) {
                                            'successful' => 'bg-green-50 text-green-700 ring-green-600/20',
                                            'failed' => 'bg-red-50 text-red-700 ring-red-600/20',
                                            'processing' => 'bg-blue-50 text-blue-700 ring-blue-700/10',
                                            default => 'bg-gray-50 text-gray-600 ring-gray-500/10',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $statusClass }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ $payment->payment_date->format('M d, Y') }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400 font-mono text-xs">
                                    {{ $payment->reference }}
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                    <a href="{{ route('admin.accounting.salaries.show', $payment) }}" class="text-brand-primary hover:text-brand-primary/80 transition-colors">View<span class="sr-only">, {{ $payment->reference }}</span></a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-sm text-gray-500">
                                    No salary disbursements found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-6">
        {{ $payments->links() }}
    </div>
</div>
@endsection
