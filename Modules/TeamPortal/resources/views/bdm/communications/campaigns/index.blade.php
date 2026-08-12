@extends('teamportal::layouts.app')

@section('title', 'Email Campaigns')

@section('content')
<!-- Header Section -->
<div class="mb-8">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Email Campaigns</h1>
            <p class="mt-2 text-sm text-gray-600">Create and manage your email marketing campaigns</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('team.bdm.campaigns.create') }}" class="inline-flex items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-[#FB4C4B] to-[#D93837] hover:from-[#D93837] hover:to-[#FB4C4B] shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                New Campaign
            </a>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium">Total Campaigns</p>
                <p class="text-3xl font-bold mt-1">{{ $campaigns->total() }}</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium">Sent Campaigns</p>
                <p class="text-3xl font-bold mt-1">{{ \Modules\Communications\Models\Campaign::where('status', 'sent')->count() }}</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium">Drafts</p>
                <p class="text-3xl font-bold mt-1">{{ \Modules\Communications\Models\Campaign::where('status', 'draft')->count() }}</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <form method="GET" action="{{ route('team.bdm.campaigns.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search campaigns..." class="pl-10 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent transition-all">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent transition-all">
                <option value="">All Statuses</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full px-4 py-2.5 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-all duration-200 font-medium">
                Apply Filters
            </button>
        </div>
    </form>
</div>

<!-- Campaigns Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($campaigns as $campaign)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200 overflow-hidden flex flex-col">
        <div class="p-6 flex-1">
            <div class="flex justify-between items-start mb-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    @if($campaign->status == 'sent') bg-green-100 text-green-800
                    @elseif($campaign->status == 'scheduled') bg-blue-100 text-blue-800
                    @elseif($campaign->status == 'draft') bg-gray-100 text-gray-800
                    @else bg-gray-100 text-gray-800 @endif">
                    {{ ucfirst($campaign->status) }}
                </span>
                <div class="flex space-x-2">
                    <a href="{{ route('team.bdm.campaigns.edit', $campaign) }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>
                </div>
            </div>

            <a href="{{ route('team.bdm.campaigns.show', $campaign) }}" class="block group">
                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-[#FB4C4B] transition-colors mb-2 line-clamp-1">{{ $campaign->name }}</h3>
                <p class="text-sm text-gray-500 mb-4 line-clamp-1">{{ $campaign->subject }}</p>
                
                <div class="space-y-3">
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>{{ $campaign->contactList ? $campaign->contactList->name : 'No List Selected' }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span>{{ $campaign->scheduled_at ? $campaign->scheduled_at->format('M d, Y h:i A') : 'Not Scheduled' }}</span>
                    </div>
                </div>
            </a>
        </div>

        @if($campaign->status == 'sent')
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
            <div class="grid grid-cols-3 gap-2 text-center">
                <div>
                    <span class="block text-xs text-gray-500 uppercase tracking-wide">Open Rate</span>
                    <span class="block text-sm font-semibold text-gray-900">{{ $campaign->open_rate }}%</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-500 uppercase tracking-wide">Click Rate</span>
                    <span class="block text-sm font-semibold text-gray-900">{{ $campaign->click_rate }}%</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-500 uppercase tracking-wide">Sent</span>
                    <span class="block text-sm font-semibold text-gray-900">{{ $campaign->total_sent }}</span>
                </div>
            </div>
        </div>
        @else
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex items-center justify-between">
            <span class="text-sm text-gray-500">
                Created {{ $campaign->created_at->diffForHumans() }}
            </span>
            @if($campaign->status == 'draft')
            <form action="{{ route('team.bdm.campaigns.send', $campaign) }}" method="POST" onsubmit="return confirm('Are you sure you want to send this campaign now?');">
                @csrf
                <button type="submit" class="text-xs font-semibold text-[#FB4C4B] hover:text-[#D93837] uppercase tracking-wide">
                    Send Now
                </button>
            </form>
            @endif
        </div>
        @endif
    </div>
    @empty
    <div class="col-span-full">
        <div class="text-center py-12 bg-white rounded-xl border border-dashed border-gray-300">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No campaigns found</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by creating a new email campaign.</p>
            <div class="mt-6">
                <a href="{{ route('team.bdm.campaigns.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#FB4C4B] hover:bg-[#D93837] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FB4C4B]">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Campaign
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $campaigns->links() }}
</div>
@endsection
