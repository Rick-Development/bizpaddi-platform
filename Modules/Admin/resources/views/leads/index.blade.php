@extends('admin::layouts.app')

@section('title', 'Leads')

@section('content')

{{-- Import modal --}}
<div id="import-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
        <div class="flex items-center justify-between bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h3 class="text-sm font-bold text-gray-900">Import Leads from CSV</h3>
            <button onclick="document.getElementById('import-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-700 transition">
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.leads.import-csv') }}" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">CSV File <span class="text-red-500">*</span></label>
                <input type="file" name="file" accept=".csv,.txt" required
                    class="block w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-brand-primary file:text-white hover:file:bg-brand-primary/90 transition cursor-pointer">
                <p class="mt-2 text-xs text-gray-500">Max 10 MB. Expected columns: <code>name, company, email, phone, status, score, source, value, notes</code></p>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="document.getElementById('import-modal').classList.add('hidden')"
                    class="px-4 py-2 text-sm text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition">Cancel</button>
                <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-brand-primary hover:bg-brand-primary/90 rounded-lg transition">
                    Upload &amp; Import
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Flash messages --}}
@if(session('success'))
<div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800">
    {{ session('error') }}
</div>
@endif

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <h1 class="text-2xl font-semibold text-gray-900">Lead Management</h1>
    <div class="flex flex-wrap items-center gap-2">
        {{-- Import CSV --}}
        <button onclick="document.getElementById('import-modal').classList.remove('hidden')"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition shadow-sm">
            <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
            </svg>
            Import CSV
        </button>

        {{-- Export CSV --}}
        <a href="{{ route('admin.leads.export-csv', request()->query()) }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition shadow-sm">
            <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
            </svg>
            Export CSV
        </a>

        {{-- Export PDF --}}
        <a href="{{ route('admin.leads.export-pdf', request()->query()) }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-red-200 bg-red-50 text-sm font-medium text-red-700 hover:bg-red-100 transition shadow-sm">
            <svg class="h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
            </svg>
            Export PDF
        </a>

        {{-- Add New Lead --}}
        <a href="{{ route('admin.leads.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-brand-primary text-white text-sm font-semibold hover:bg-brand-primary/90 transition shadow-sm">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add New Lead
        </a>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow-sm p-6 mb-6">
    <form method="GET" action="{{ route('admin.leads.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Search -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, company..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
        </div>

        <!-- Status Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                <option value="">All Statuses</option>
                <option value="new"       {{ request('status') == 'new'       ? 'selected' : '' }}>New</option>
                <option value="contacted" {{ request('status') == 'contacted' ? 'selected' : '' }}>Contacted</option>
                <option value="nurturing" {{ request('status') == 'nurturing' ? 'selected' : '' }}>Nurturing</option>
                <option value="qualified" {{ request('status') == 'qualified' ? 'selected' : '' }}>Qualified</option>
                <option value="converted" {{ request('status') == 'converted' ? 'selected' : '' }}>Converted</option>
                <option value="lost"      {{ request('status') == 'lost'      ? 'selected' : '' }}>Lost</option>
            </select>
        </div>

        <!-- Assigned To Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Assigned To</label>
            <select name="assigned_to" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                <option value="">All Team Members</option>
                @foreach($teamMembers as $member)
                    <option value="{{ $member->id }}" {{ request('assigned_to') == $member->id ? 'selected' : '' }}>
                        {{ $member->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Source Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Source</label>
            <input type="text" name="source" value="{{ request('source') }}" placeholder="e.g., AI BDM" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
        </div>

        <!-- Start Date -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Start Date (From)</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
        </div>

        <!-- End Date -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">End Date (To)</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
        </div>

        <!-- Filter Buttons -->
        <div class="md:col-span-2 lg:col-span-4 flex gap-2">
            <button type="submit" class="px-6 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition">
                Apply Filters
            </button>
            <a href="{{ route('admin.leads.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Clear Filters
            </a>
        </div>
    </form>
</div>

<!-- Leads Table -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex items-center justify-between flex-wrap gap-2">
        <h3 class="text-sm font-semibold text-gray-900">Leads List</h3>
        <div class="flex items-center gap-2">
            <input type="checkbox" id="toggle-details" class="rounded border-gray-300 text-brand-primary focus:ring-brand-primary cursor-pointer h-4 w-4">
            <label for="toggle-details" class="text-xs font-semibold text-gray-700 select-none cursor-pointer">Show Company Details</label>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lead</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact Info</th>
                    <th class="col-company-details hidden px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company Details</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Source</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($leads as $lead)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $lead->name }}</div>
                                    @if($lead->company)
                                        <div class="text-sm text-gray-500">{{ $lead->company }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $lead->email }}</div>
                            @if($lead->phone)
                                <div class="text-sm text-gray-500">{{ $lead->phone }}</div>
                            @endif
                        </td>
                        <td class="col-company-details hidden px-6 py-4">
                            <div class="text-xs text-gray-600 max-w-xs truncate" title="{{ $lead->company_details }}">{{ $lead->company_details ?? '—' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($lead->status == 'new') bg-blue-100 text-blue-800
                                @elseif($lead->status == 'contacted') bg-yellow-100 text-yellow-800
                                @elseif($lead->status == 'nurturing') bg-purple-100 text-purple-800
                                @elseif($lead->status == 'qualified') bg-green-100 text-green-800
                                @elseif($lead->status == 'converted') bg-emerald-100 text-emerald-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($lead->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="text-sm font-medium text-gray-900">{{ $lead->score }}</div>
                                <div class="ml-2 w-16 bg-gray-200 rounded-full h-2">
                                    <div class="bg-brand-primary h-2 rounded-full" style="width: {{ $lead->score }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $lead->source ?? '-' }}</div>
                            @if($lead->utm_source)
                                <div class="text-xs text-brand-primary mt-1 font-semibold uppercase tracking-wider">
                                    UTM: {{ $lead->utm_source }}
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $lead->assignedTo->name ?? 'Unassigned' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $lead->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.leads.show', $lead) }}" class="text-brand-primary hover:text-brand-primary/80">
                                    View
                                </a>
                                <a href="{{ route('admin.leads.edit', $lead) }}" class="text-gray-600 hover:text-gray-900">
                                    Edit
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="mt-2">No leads found</p>
                            <a href="{{ route('admin.leads.create') }}" class="mt-4 inline-block text-brand-primary hover:text-brand-primary/80">
                                Create your first lead
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($leads->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $leads->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggle = document.getElementById('toggle-details');
        const detailCols = document.querySelectorAll('.col-company-details');

        function updateColumnVisibility(visible) {
            detailCols.forEach(col => {
                if (visible) {
                    col.classList.remove('hidden');
                } else {
                    col.classList.add('hidden');
                }
            });
            toggle.checked = visible;
        }

        // Load state from localStorage
        const storedState = localStorage.getItem('leads_show_company_details') === 'true';
        updateColumnVisibility(storedState);

        toggle.addEventListener('change', function() {
            localStorage.setItem('leads_show_company_details', this.checked);
            updateColumnVisibility(this.checked);
        });
    });
</script>
@endpush
@endsection
