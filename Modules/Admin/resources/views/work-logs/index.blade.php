@extends('admin::layouts.app')

@section('title', 'Work Session Logs')

@section('content')
<div class="mb-6">
    <div class="sm:flex sm:items-center sm:justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">Work Session Logs</h1>
            <p class="mt-1 text-sm text-gray-400">View team check-in and check-out sessions</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            <!-- Filters -->
            <form action="{{ route('admin.work-logs.index') }}" method="GET" class="flex gap-2 items-center">
                <div>
                    <select name="user_id" class="rounded-lg border-white/10 text-sm focus:ring-brand-primary focus:border-brand-primary bg-[#0a1612] text-white border-white/10">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <input type="date" name="date" value="{{ request('date') }}" class="rounded-lg border-white/10 text-sm focus:ring-brand-primary focus:border-brand-primary bg-[#0a1612] text-white border-white/10">
                </div>
                <button type="submit" class="px-4 py-2 bg-brand-secondary text-white rounded-lg hover:bg-brand-secondary/90 transition-colors text-sm font-medium">
                    Filter
                </button>
                @if(request()->has('date') || request()->has('user_id'))
                    <a href="{{ route('admin.work-logs.index') }}" class="px-4 py-2 text-gray-400 hover:text-white text-sm font-medium">
                        Clear
                    </a>
                @endif
            </form>

            <!-- Custom Export Generator Button -->
            <a href="{{ route('admin.work-logs.export-page') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-brand-secondary text-white text-sm font-medium rounded-lg hover:bg-brand-secondary/90 transition-colors shadow-sm">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 18H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 12h11.25" />
                </svg>
                Custom Export Generator
            </a>

            <!-- Export Dropdown -->
            <div x-data="{ open: false }" class="relative inline-block text-left">
                <button @click="open = !open" type="button" class="inline-flex items-center gap-2 px-4 py-2 bg-brand-primary text-white text-sm font-medium rounded-lg hover:bg-brand-primary/90 transition-colors shadow-sm">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Export Report
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>

                <div x-show="open" @click.away="open = false" x-cloak class="origin-top-right absolute right-0 mt-2 w-60 rounded-md shadow-lg bg-[#10231C]/60 ring-1 ring-black ring-opacity-5 divide-y divide-white/5 focus:outline-none z-50">
                    <div class="py-1">
                        <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">PDF Summary Reports</div>
                        <a href="{{ route('admin.work-logs.export-pdf', array_merge(request()->all(), ['period' => 'daily'])) }}" class="group flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-white/[0.06] hover:text-white">
                            📅 Export Daily PDF Report
                        </a>
                        <a href="{{ route('admin.work-logs.export-pdf', array_merge(request()->all(), ['period' => 'weekly'])) }}" class="group flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-white/[0.06] hover:text-white">
                            🗓️ Export Weekly PDF Report
                        </a>
                        <a href="{{ route('admin.work-logs.export-pdf', array_merge(request()->all(), ['period' => 'monthly'])) }}" class="group flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-white/[0.06] hover:text-white">
                            📆 Export Monthly PDF Report
                        </a>
                        <a href="{{ route('admin.work-logs.export-pdf', array_merge(request()->all(), ['period' => 'yearly'])) }}" class="group flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-white/[0.06] hover:text-white">
                            📊 Export Yearly PDF Report
                        </a>
                    </div>
                    <div class="py-1">
                        <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">CSV Data Exports</div>
                        <a href="{{ route('admin.work-logs.export-csv', array_merge(request()->all(), ['period' => 'daily'])) }}" class="group flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-white/[0.06] hover:text-white">
                            📥 Export Daily CSV
                        </a>
                        <a href="{{ route('admin.work-logs.export-csv', array_merge(request()->all(), ['period' => 'weekly'])) }}" class="group flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-white/[0.06] hover:text-white">
                            📥 Export Weekly CSV
                        </a>
                        <a href="{{ route('admin.work-logs.export-csv', array_merge(request()->all(), ['period' => 'monthly'])) }}" class="group flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-white/[0.06] hover:text-white">
                            📥 Export Monthly CSV
                        </a>
                        <a href="{{ route('admin.work-logs.export-csv', array_merge(request()->all(), ['period' => 'yearly'])) }}" class="group flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-white/[0.06] hover:text-white">
                            📥 Export Yearly CSV
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($workLogs->isEmpty())
    <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl p-12 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
        </svg>
        <h3 class="mt-4 text-lg font-semibold text-white">No work logs found</h3>
        <p class="mt-2 text-sm text-gray-400">Try adjusting your filters or check back later.</p>
    </div>
@else
    <form id="bulk-checkout-form" action="{{ route('admin.work-logs.bulk-checkout') }}" method="POST">
        @csrf
        
        <!-- Bulk Action Alert / Checkout Panel (Displays dynamically on checkbox select) -->
        <div id="bulk-action-bar" class="hidden bg-red-50 border border-red-200 rounded-xl p-4 mb-4 flex items-center justify-between transition-all duration-300">
            <div class="flex items-center gap-2">
                <span class="text-sm text-red-800 font-bold" id="selected-count">0 active session(s) selected</span>
            </div>
            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-semibold transition shadow-sm flex items-center gap-2 cursor-pointer">
                ⚡ Force Checkout Selected Team
            </button>
        </div>

        <div class="space-y-4">
            <!-- Header Row -->
            <div class="bg-white/[0.03]/50 rounded-xl p-3 grid grid-cols-12 gap-4 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden md:grid items-center">
                <div class="col-span-1 text-center">
                    <input type="checkbox" id="select-all-active" class="rounded border-white/10 text-brand-primary focus:ring-brand-primary cursor-pointer">
                </div>
                <div class="col-span-3 pl-4">User & Date</div>
                <div class="col-span-4">Planned Tasks</div>
                <div class="col-span-2">Check-in / Out</div>
                <div class="col-span-2 text-right pr-4">Status & Export</div>
            </div>

            @foreach($workLogs as $log)
            <div class="block group bg-[#10231C]/60 rounded-2xl p-5 shadow-sm hover:shadow-md border border-white/10 transition-all duration-200 relative overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                    
                    <!-- Selection Checkbox -->
                    <div class="col-span-12 md:col-span-1 flex items-center justify-center">
                        @if($log->status === 'in_progress')
                            <input type="checkbox" name="log_ids[]" value="{{ $log->id }}" class="log-checkbox rounded border-white/10 text-brand-primary focus:ring-brand-primary cursor-pointer">
                        @else
                            <input type="checkbox" disabled class="rounded border-white/10 text-gray-300 bg-white/[0.03] cursor-not-allowed">
                        @endif
                    </div>

                    <!-- User & Date -->
                    <div class="col-span-12 md:col-span-3">
                        <a href="{{ route('admin.work-logs.show', $log->id) }}" class="flex items-center gap-4 pl-2 group-hover:text-brand-primary">
                            <div class="h-10 w-10 rounded-full bg-brand-primary/10 flex items-center justify-center text-brand-primary font-bold text-sm">
                                {{ substr($log->user->name ?? 'U', 0, 1) }}
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-white group-hover:text-brand-primary transition-colors">{{ $log->user->name ?? 'Unknown User' }}</h3>
                                <p class="text-xs text-gray-400">{{ $log->start_time->format('M d, Y') }}</p>
                            </div>
                        </a>
                    </div>

                    <!-- Planned Tasks -->
                    <div class="col-span-12 md:col-span-4">
                        <a href="{{ route('admin.work-logs.show', $log->id) }}" class="block">
                            <p class="text-sm text-gray-400 line-clamp-2" title="{{ $log->planned_tasks }}">
                                <span class="font-medium text-white">Summary:</span> {{ $log->planned_tasks }}
                            </p>
                        </a>
                    </div>

                    <!-- Check-in / Out -->
                    <div class="col-span-6 md:col-span-2">
                        <a href="{{ route('admin.work-logs.show', $log->id) }}" class="flex flex-col">
                            <span class="text-sm font-bold text-white">
                                 In: {{ $log->start_time->format('H:i') }}
                            </span>
                            <span class="text-sm text-gray-400">
                                 Out: 
                                 @if($log->end_time)
                                    {{ $log->end_time->format('H:i') }}
                                    <span class="text-xs bg-white/[0.06] rounded px-1 ml-1" title="Duration">
                                        {{ floor($log->duration_minutes / 60) }}h {{ $log->duration_minutes % 60 }}m
                                    </span>
                                 @else
                                    <span class="text-green-600 font-semibold italic">Working...</span>
                                 @endif
                            </span>
                        </a>
                    </div>

                    <!-- Status & Single Export -->
                    <div class="col-span-6 md:col-span-2 flex items-center justify-end gap-3">
                        @if($log->status === 'completed')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Completed
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                In Progress
                            </span>
                        @endif

                        <a href="{{ route('admin.work-logs.single-pdf', $log->id) }}" title="Download Individual PDF Report" class="p-1.5 rounded-lg text-gray-400 hover:text-brand-primary hover:bg-white/[0.06] transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $workLogs->links() }}
        </div>
    </form>
@endif

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAllCheckbox = document.getElementById('select-all-active');
        const logCheckboxes = document.querySelectorAll('.log-checkbox');
        const bulkActionBar = document.getElementById('bulk-action-bar');
        const selectedCountSpan = document.getElementById('selected-count');

        function updateActionBar() {
            const checkedCount = document.querySelectorAll('.log-checkbox:checked').length;
            if (checkedCount > 0) {
                selectedCountSpan.textContent = `${checkedCount} active session(s) selected`;
                bulkActionBar.classList.remove('hidden');
            } else {
                bulkActionBar.classList.add('hidden');
            }
        }

        selectAllCheckbox?.addEventListener('change', function () {
            logCheckboxes.forEach(cb => {
                if (!cb.disabled) {
                    cb.checked = selectAllCheckbox.checked;
                }
            });
            updateActionBar();
        });

        logCheckboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                // If any checkbox is manually unchecked, uncheck the select-all box
                if (!this.checked && selectAllCheckbox) {
                    selectAllCheckbox.checked = false;
                }
                updateActionBar();
            });
        });
    });
</script>
@endpush

@endsection
