@extends('admin::layouts.app')

@section('title', 'Expiring Contracts')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-x-4">
        <a href="{{ route('admin.contracts.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Expiring Contracts</h1>
            <p class="mt-1 text-sm text-gray-600">Contracts expiring within 30 days</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contract</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Party</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days Left</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auto Renew</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($contracts as $contract)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $contract->contract_number }}</div>
                    <div class="text-sm text-gray-500">{{ $contract->title }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ $contract->party_name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ $contract->end_date->format('M d, Y') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @php
                        $daysLeft = $contract->end_date->diffInDays(now());
                        $colorClass = $daysLeft <= 7 ? 'text-red-600' : ($daysLeft <= 14 ? 'text-orange-600' : 'text-yellow-600');
                    @endphp
                    <span class="text-sm font-semibold {{ $colorClass }}">
                        {{ $daysLeft }} days
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($contract->auto_renew)
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Yes
                        </span>
                    @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                            No
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <a href="{{ route('admin.contracts.show', $contract) }}" class="text-[#FB4C4B] hover:text-[#D93837] mr-3">View</a>
                    <a href="{{ route('admin.contracts.edit', $contract) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="mt-2">No contracts expiring soon</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($contracts->hasPages())
<div class="mt-6">
    {{ $contracts->links() }}
</div>
@endif
@endsection
