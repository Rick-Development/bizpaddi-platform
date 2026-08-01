@extends('admin::layouts.app')

@section('title', 'Visitor Sessions')

@section('content')
<div class="visitor-sessions-page" x-data="{ 
    selected: [], 
    allSelected: false,
    toggleAll() {
        this.allSelected = !this.allSelected;
        this.selected = this.allSelected ? {{ json_encode($visitors->pluck('id')) }} : [];
    }
}">
    <!-- Header -->
    <div class="page-header flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="header-content">
            <div>
                <h1 class="page-title text-2xl sm:text-3xl font-bold text-gray-900">Visitor Sessions</h1>
                <p class="page-subtitle mt-1 text-sm text-gray-500">Track and analyze global visitor activity</p>
            </div>
        </div>
        
        <!-- Bulk Actions -->
        <div class="flex-shrink-0">
             <form action="{{ route('admin.visitors.bulk-delete') }}" method="POST" x-show="selected.length > 0" x-transition x-cloak onsubmit="return confirm('Are you sure you want to delete the selected sessions?');">
                @csrf
                <template x-for="id in selected" :key="id">
                    <input type="hidden" name="ids[]" :value="id">
                </template>
                <button type="submit" class="inline-flex items-center justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:w-auto">
                    Delete Selected (<span x-text="selected.length"></span>)
                </button>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-4 flex items-center gap-4">
            <div class="flex-shrink-0 p-3 bg-blue-100 text-blue-600 rounded-lg">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Sessions</p>
                <h3 class="text-xl font-bold text-gray-900">{{ number_format($stats['total']) }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4 flex items-center gap-4">
            <div class="flex-shrink-0 p-3 bg-green-100 text-green-600 rounded-lg">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Today</p>
                <h3 class="text-xl font-bold text-gray-900">{{ number_format($stats['today']) }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4 flex items-center gap-4">
            <div class="flex-shrink-0 p-3 bg-purple-100 text-purple-600 rounded-lg">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Countries</p>
                <h3 class="text-xl font-bold text-gray-900">{{ number_format($stats['unique_countries']) }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4 flex items-center gap-4">
            <div class="flex-shrink-0 p-3 bg-orange-100 text-orange-600 rounded-lg">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Avg. Time</p>
                <h3 class="text-xl font-bold text-gray-900">{{ gmdate('i:s', $stats['avg_time_spent']) }}</h3>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <form method="GET" action="{{ route('admin.visitors.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 items-end">
            <div class="lg:col-span-2">
                <input type="text" name="search" placeholder="Search by IP, country, city, or URL..." 
                       value="{{ request('search') }}" 
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm">
            </div>

            <div>
                <select name="country" class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm">
                    <option value="">All Countries</option>
                    @foreach($countries as $country)
                        <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>
                            {{ $country }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="device" class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm">
                    <option value="">All Devices</option>
                    @foreach($devices as $device)
                        <option value="{{ $device }}" {{ request('device') == $device ? 'selected' : '' }}>
                            {{ ucfirst($device) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm" placeholder="From">
            </div>

            <div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-brand-primary hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'country', 'device', 'date_from', 'date_to']))
                        <a href="{{ route('admin.visitors.index') }}" class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary">
                            Clear
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden md:block bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="relative px-6 py-3 w-12 text-center">
                            <input type="checkbox" class="h-4 w-4 rounded border-gray-300 text-brand-primary focus:ring-brand-primary" 
                                   @click="toggleAll()" :checked="allSelected">
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Device</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Browser</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Page Visited</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Spent</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Visit Date</th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($visitors as $visitor)
                    <tr class="hover:bg-gray-50">
                        <td class="relative px-6 py-4 w-12 text-center">
                            <input type="checkbox" class="h-4 w-4 rounded border-gray-300 text-brand-primary focus:ring-brand-primary" 
                                   value="{{ $visitor->id }}" x-model="selected">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">{{ $visitor->ip_address }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                @if($visitor->country)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 w-fit mb-1">
                                        {{ $visitor->country }}
                                    </span>
                                @endif
                                @if($visitor->city)
                                    <span class="text-xs text-gray-500">{{ $visitor->city }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($visitor->device)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium
                                    {{ strtolower($visitor->device) === 'mobile' ? 'bg-pink-100 text-pink-800' : 
                                       (strtolower($visitor->device) === 'tablet' ? 'bg-green-100 text-green-800' : 'bg-indigo-100 text-indigo-800') }}">
                                    {{ ucfirst($visitor->device) }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $visitor->browser ?: '-' }}</td>
                        <td class="px-6 py-4">
                            <div class="max-w-xs truncate text-sm text-gray-500" title="{{ $visitor->url }}">
                                {{ Str::limit($visitor->url, 40) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 font-mono">
                                {{ gmdate('i:s', $visitor->time_spent) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $visitor->created_at->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.visitors.show', $visitor->id) }}" class="p-1 text-blue-600 hover:text-blue-900 bg-blue-50 rounded hover:bg-blue-100" title="View Details">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.visitors.destroy', $visitor->id) }}" class="inline" onsubmit="return confirm('Delete this visitor session?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 text-red-600 hover:text-red-900 bg-red-50 rounded hover:bg-red-100" title="Delete">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No visitor sessions found</h3>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Card View -->
    <div class="space-y-4 md:hidden">
        <!-- Mobile Select All -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 flex items-center justify-between">
            <label class="flex items-center space-x-3">
                <input type="checkbox" class="h-4 w-4 rounded border-gray-300 text-brand-primary focus:ring-brand-primary" 
                       @click="toggleAll()" :checked="allSelected">
                <span class="text-sm font-medium text-gray-700">Select All</span>
            </label>
        </div>

        @forelse($visitors as $visitor)
        <div class="bg-white rounded-lg shadow-sm p-4 space-y-3 border border-gray-100">
            <div class="flex justify-between items-start">
                <div class="space-y-1 flex items-start gap-3">
                    <input type="checkbox" class="h-4 w-4 rounded border-gray-300 text-brand-primary focus:ring-brand-primary mt-1" 
                           value="{{ $visitor->id }}" x-model="selected">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-sm font-semibold text-gray-900">{{ $visitor->ip_address }}</span>
                            @if($visitor->country)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $visitor->country }}
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500">{{ $visitor->city ?: 'Unknown City' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                     <a href="{{ route('admin.visitors.show', $visitor->id) }}" class="text-blue-600 hover:text-blue-900">
                        <span class="sr-only">View</span>
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </a>
                    <form method="POST" action="{{ route('admin.visitors.destroy', $visitor->id) }}" class="inline" onsubmit="return confirm('Delete this visitor session?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900">
                            <span class="sr-only">Delete</span>
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <div class="flex justify-between items-center text-sm border-t border-gray-50 pt-3 ml-7">
                <div class="flex items-center gap-2">
                    @if($visitor->device)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium
                            {{ strtolower($visitor->device) === 'mobile' ? 'bg-pink-100 text-pink-800' : 
                                (strtolower($visitor->device) === 'tablet' ? 'bg-green-100 text-green-800' : 'bg-indigo-100 text-indigo-800') }}">
                            {{ ucfirst($visitor->device) }}
                        </span>
                    @endif
                     <span class="text-gray-500">{{ $visitor->browser ?: 'Unknown' }}</span>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 font-mono">
                    {{ gmdate('i:s', $visitor->time_spent) }}
                </span>
            </div>
             <div class="text-xs text-gray-500 truncate ml-7" title="{{ $visitor->url }}">
                 Page: {{ Str::limit($visitor->url, 40) }}
            </div>
             <div class="text-xs text-right text-gray-400 ml-7">
                {{ $visitor->created_at->format('M d, Y H:i a') }}
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow-sm p-8 text-center text-gray-500">
            No visitor sessions found
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($visitors->hasPages())
    <div class="mt-4">
        {{ $visitors->links() }}
    </div>
    @endif
</div>
@endsection
