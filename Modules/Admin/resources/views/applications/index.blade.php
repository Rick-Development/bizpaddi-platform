@extends('admin::layouts.app')

@section('title', 'Applications')

@section('content')
<div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
        <h1 class="text-3xl font-bold text-gray-900">Job Applications</h1>
        <p class="mt-2 text-sm text-gray-700">Review and manage candidate applications</p>
    </div>
</div>

<!-- Search and Filter Controls -->
<div class="mt-6 bg-white p-5 rounded-2xl border border-gray-200 shadow-sm space-y-4">
    {{-- Top Row: Search + Status + Job Filters --}}
    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
        {{-- Search Input --}}
        <div class="md:col-span-6 relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 pointer-events-none">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
            <input type="text" id="search-input"
                   placeholder="Search candidate name, email, or job position..."
                   class="w-full border border-gray-300 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm" />
        </div>

        {{-- Status Filter --}}
        <div class="md:col-span-3">
            <select id="status-filter"
                    class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-white">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="reviewing">Reviewing</option>
                <option value="interview">Interview</option>
                <option value="accepted">Accepted</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>

        {{-- Job Position Filter --}}
        <div class="md:col-span-3">
            <select id="job-filter"
                    class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-white">
                <option value="">All Job Positions</option>
                @foreach($jobs as $job)
                    <option value="{{ $job->id }}">{{ $job->title }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Bottom Row: Date Range & Actions --}}
    <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-gray-100">
        {{-- Date Range Controls --}}
        <div class="flex flex-wrap items-center gap-3">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Date Range:</span>
            
            <div class="flex items-center gap-2">
                <label for="from-date-filter" class="text-xs text-gray-600 font-medium">From</label>
                <input type="date" id="from-date-filter"
                       class="border border-gray-300 rounded-xl px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-white" />
            </div>

            <div class="flex items-center gap-2">
                <label for="to-date-filter" class="text-xs text-gray-600 font-medium">To</label>
                <input type="date" id="to-date-filter"
                       class="border border-gray-300 rounded-xl px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-white" />
            </div>

            {{-- Reset Button --}}
            <button type="button" id="reset-filters"
                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-xl transition-colors shadow-sm cursor-pointer">
                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Clear Filters
            </button>
        </div>

        {{-- Results count --}}
        <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200 shadow-sm" id="results-count">
            {{ $applications->count() }} result(s)
        </div>
    </div>
</div>

<div class="mt-8 flow-root">
    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Candidate</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Job Position</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Applied Date</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">AI Score</th>
                            <th class="relative py-3.5 pl-3 pr-4 sm:pr-6"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody id="applications-table-body" class="divide-y divide-gray-200 bg-white">
                        @include('admin::applications.partials.table')
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput    = document.getElementById('search-input');
        const statusFilter   = document.getElementById('status-filter');
        const jobFilter      = document.getElementById('job-filter');
        const fromDateFilter = document.getElementById('from-date-filter');
        const toDateFilter   = document.getElementById('to-date-filter');
        const resetFilters   = document.getElementById('reset-filters');
        const tableBody      = document.getElementById('applications-table-body');
        
        let debounceTimer;

        function fetchApplications() {
            const search   = searchInput.value;
            const status   = statusFilter.value;
            const jobId    = jobFilter.value;
            const fromDate = fromDateFilter.value;
            const toDate   = toDateFilter.value;

            // Show loading placeholder rows or spinner in table
            tableBody.innerHTML = `<tr>
                <td colspan="6" class="px-3 py-8 text-center text-sm text-gray-400">
                    <div class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Loading applications...
                    </div>
                </td>
            </tr>`;

            const params = new URLSearchParams({
                ajax:      '1',
                search:    search,
                status:    status,
                job_id:    jobId,
                from_date: fromDate,
                to_date:   toDate
            });

            fetch(`${window.location.pathname}?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                tableBody.innerHTML = data.html;
            })
            .catch(error => {
                console.error('Error fetching applications:', error);
                tableBody.innerHTML = `<tr>
                    <td colspan="6" class="px-3 py-8 text-center text-sm text-red-500 font-semibold">
                        Failed to load data. Please try again.
                    </td>
                </tr>`;
            });
        }

        searchInput.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(fetchApplications, 300);
        });

        statusFilter.addEventListener('change', fetchApplications);
        jobFilter.addEventListener('change', fetchApplications);
        fromDateFilter.addEventListener('change', fetchApplications);
        toDateFilter.addEventListener('change', fetchApplications);

        resetFilters.addEventListener('click', function () {
            searchInput.value = '';
            statusFilter.value = '';
            jobFilter.value = '';
            fromDateFilter.value = '';
            toDateFilter.value = '';
            fetchApplications();
        });
    });
</script>
@endpush
@endsection
