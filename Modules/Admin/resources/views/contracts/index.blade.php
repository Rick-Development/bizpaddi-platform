@extends('admin::layouts.app')

@section('title', 'Contracts')

@section('content')
<!-- Header Section -->
<div class="mb-8">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">Contracts</h1>
            <p class="mt-2 text-sm text-gray-400">Manage all your contracts and agreements</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.contracts.create') }}" class="inline-flex items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-emerald-600 to-teal-700 hover:from-[#0A4335] hover:to-[#0E5E4A] shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                New Contract
            </a>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium">Total Contracts</p>
                <p class="text-3xl font-bold mt-1">{{ $contracts->total() }}</p>
            </div>
            <div class="bg-[#10231C]/60 bg-opacity-20 rounded-lg p-3">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        </div>
    </div>

    @if($expiringSoon > 0)
    <a href="{{ route('admin.contracts.expiring') }}" class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-yellow-100 text-sm font-medium">Expiring Soon</p>
                <p class="text-3xl font-bold mt-1">{{ $expiringSoon }}</p>
            </div>
            <div class="bg-[#10231C]/60 bg-opacity-20 rounded-lg p-3">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </a>
    @endif
</div>

<!-- Filters -->
<div class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl p-6 mb-6">
    <form method="GET" action="{{ route('admin.contracts.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search contracts..." class="pl-10 w-full px-4 py-2.5 border border-white/10 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all bg-[#0a1612] text-white border-white/10">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Type</label>
            <select name="type" class="w-full px-4 py-2.5 border border-white/10 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all bg-[#0a1612] text-white border-white/10">
                <option value="">All Types</option>
                <option value="sla" {{ request('type') == 'sla' ? 'selected' : '' }}>SLA</option>
                <option value="nda" {{ request('type') == 'nda' ? 'selected' : '' }}>NDA</option>
                <option value="employment" {{ request('type') == 'employment' ? 'selected' : '' }}>Employment</option>
                <option value="service_agreement" {{ request('type') == 'service_agreement' ? 'selected' : '' }}>Service Agreement</option>
                <option value="partnership" {{ request('type') == 'partnership' ? 'selected' : '' }}>Partnership</option>
                <option value="vendor" {{ request('type') == 'vendor' ? 'selected' : '' }}>Vendor</option>
                <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Status</label>
            <select name="status" class="w-full px-4 py-2.5 border border-white/10 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all bg-[#0a1612] text-white border-white/10">
                <option value="">All Statuses</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="pending_signature" {{ request('status') == 'pending_signature' ? 'selected' : '' }}>Pending Signature</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                <option value="terminated" {{ request('status') == 'terminated' ? 'selected' : '' }}>Terminated</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full px-4 py-2.5 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-all duration-200 font-medium">
                Apply Filters
            </button>
        </div>
    </form>
</div>

<!-- Contracts Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
    @forelse($contracts as $contract)
    <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl hover:shadow-lg transition-all duration-200 overflow-hidden group">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-white/10">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-white group-hover:text-emerald-400 transition-colors">
                        {{ $contract->contract_number }}
                    </h3>
                    <p class="text-sm text-gray-400 mt-1 line-clamp-1">{{ $contract->title }}</p>
                </div>
                @php
                    $statusColors = [
                        'draft' => 'bg-white/[0.06] text-gray-300 ring-gray-200',
                        'pending_signature' => 'bg-yellow-100 text-yellow-700 ring-yellow-200',
                        'active' => 'bg-green-100 text-green-700 ring-green-200',
                        'expired' => 'bg-red-100 text-red-700 ring-red-200',
                        'terminated' => 'bg-red-100 text-red-700 ring-red-200',
                        'completed' => 'bg-blue-100 text-blue-700 ring-blue-200',
                    ];
                @endphp
                <span class="ml-3 px-3 py-1 text-xs font-semibold rounded-full ring-1 {{ $statusColors[$contract->status] ?? 'bg-white/[0.06] text-gray-300 ring-gray-200' }}">
                    {{ ucfirst(str_replace('_', ' ', $contract->status)) }}
                </span>
            </div>
        </div>

        <!-- Card Body -->
        <div class="px-6 py-4 space-y-3">
            <!-- Party Info -->
            <div class="flex items-center text-sm">
                <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <div>
                    <p class="font-medium text-white">{{ $contract->party_name }}</p>
                    <p class="text-xs text-gray-400">{{ ucfirst(str_replace('_', ' ', $contract->party_type ?? 'N/A')) }}</p>
                </div>
            </div>

            <!-- Type -->
            <div class="flex items-center text-sm">
                <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                <span class="px-2.5 py-1 text-xs font-medium rounded-md bg-blue-50 text-blue-700">
                    {{ strtoupper($contract->contract_type) }}
                </span>
            </div>

            <!-- Dates -->
            <div class="flex items-center text-sm text-gray-400">
                <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <div class="text-xs">
                    <p>Start: <span class="font-medium text-white">{{ $contract->start_date->format('M d, Y') }}</span></p>
                    @if($contract->end_date)
                    <p class="mt-0.5">End: <span class="font-medium text-white">{{ $contract->end_date->format('M d, Y') }}</span></p>
                    @endif
                </div>
            </div>

            <!-- Value -->
            @if($contract->value)
            <div class="flex items-center text-sm">
                <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-semibold text-white">{{ $contract->currency->symbol ?? '$' }}{{ number_format($contract->value, 2) }}</span>
            </div>
            @endif
        </div>

        <!-- Card Footer -->
        <div class="px-6 py-4 bg-white/[0.03] border-t border-white/10 flex items-center justify-between">
            <a href="{{ route('admin.contracts.show', $contract) }}" class="inline-flex items-center text-sm font-medium text-emerald-400 hover:text-emerald-300 transition-colors">
                View Details
                <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.contracts.edit', $contract) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Edit">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </a>
                <a href="{{ route('admin.contracts.pdf', $contract) }}" class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all" title="Download PDF">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
    @empty
    <!-- Empty State -->
    <div class="col-span-full">
        <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 shadow-xl-sm border-2 border-dashed border-white/10 p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-white">No contracts found</h3>
            <p class="mt-2 text-sm text-gray-400">Get started by creating your first contract.</p>
            <a href="{{ route('admin.contracts.create') }}" class="mt-6 inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-emerald-600 to-teal-700 hover:from-[#0A4335] hover:to-[#0E5E4A] shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Create Contract
            </a>
        </div>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($contracts->hasPages())
<div class="mt-8">
    {{ $contracts->links() }}
</div>
@endif
@endsection
