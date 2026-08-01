@extends('admin::layouts.app')

@section('title', 'Leads')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-semibold text-gray-900">Lead Management</h1>
    <a href="{{ route('admin.leads.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition">
        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Add New Lead
    </a>
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
                <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                <option value="contacted" {{ request('status') == 'contacted' ? 'selected' : '' }}>Contacted</option>
                <option value="nurturing" {{ request('status') == 'nurturing' ? 'selected' : '' }}>Nurturing</option>
                <option value="qualified" {{ request('status') == 'qualified' ? 'selected' : '' }}>Qualified</option>
                <option value="converted" {{ request('status') == 'converted' ? 'selected' : '' }}>Converted</option>
                <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>Lost</option>
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
            <input type="text" name="source" value="{{ request('source') }}" placeholder="e.g., Website Form" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
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
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lead</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact Info</th>
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
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $lead->source ?? '-' }}
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
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
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
@endsection
