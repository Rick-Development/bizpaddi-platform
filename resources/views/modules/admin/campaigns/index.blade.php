@extends('admin::layouts.app')

@section('title', 'Email Campaigns')

@section('content')
<!-- Header Section -->
<div class="mb-8">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">Email Campaigns</h1>
            <p class="mt-2 text-sm text-gray-400">Create and manage your email marketing campaigns</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.campaigns.create') }}" class="inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-900/30 transition-all hover:-translate-y-0.5">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                New Campaign
            </a>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl p-6 relative overflow-hidden group hover:border-emerald-500/30 transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Campaigns</p>
                <p class="text-3xl font-bold text-white mt-2">{{ $campaigns->total() }}</p>
                <p class="text-xs text-gray-400 mt-1">Across all audiences</p>
            </div>
            <div class="h-12 w-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
        </div>
        <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-emerald-500/5 rounded-full blur-xl pointer-events-none"></div>
    </div>

    <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl p-6 relative overflow-hidden group hover:border-emerald-500/30 transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Sent Campaigns</p>
                <p class="text-3xl font-bold text-emerald-400 mt-2">{{ \Modules\Communications\Models\Campaign::where('status', 'sent')->count() }}</p>
                <p class="text-xs text-emerald-400/70 mt-1">Delivered successfully</p>
            </div>
            <div class="h-12 w-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-emerald-500/5 rounded-full blur-xl pointer-events-none"></div>
    </div>

    <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl p-6 relative overflow-hidden group hover:border-amber-500/30 transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Drafts & Scheduled</p>
                <p class="text-3xl font-bold text-amber-400 mt-2">{{ \Modules\Communications\Models\Campaign::whereIn('status', ['draft', 'scheduled'])->count() }}</p>
                <p class="text-xs text-amber-400/70 mt-1">Pending dispatch</p>
            </div>
            <div class="h-12 w-12 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
        </div>
        <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-amber-500/5 rounded-full blur-xl pointer-events-none"></div>
    </div>
</div>

<!-- Filters -->
<div class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl p-6 mb-6">
    <form method="GET" action="{{ route('admin.campaigns.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-300 mb-2">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search campaigns..." class="pl-10 w-full px-4 py-2.5 border border-white/10 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all bg-[#0a1612] text-white">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Status</label>
            <select name="status" class="w-full px-4 py-2.5 border border-white/10 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all bg-[#0a1612] text-white">
                <option value="">All Statuses</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-lg transition-all duration-200 font-medium">
                Apply Filters
            </button>
        </div>
    </form>
</div>

<!-- Campaigns Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($campaigns as $campaign)
    <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl hover:border-emerald-500/30 transition-all duration-200 overflow-hidden flex flex-col">
        <div class="p-6 flex-1">
            <div class="flex justify-between items-start mb-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold 
                    @if($campaign->status == 'sent') bg-emerald-500/20 text-emerald-300 border border-emerald-500/30
                    @elseif($campaign->status == 'scheduled') bg-blue-500/20 text-blue-300 border border-blue-500/30
                    @elseif($campaign->status == 'draft') bg-amber-500/20 text-amber-300 border border-amber-500/30
                    @else bg-white/[0.06] text-white @endif">
                    {{ ucfirst($campaign->status) }}
                </span>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="text-gray-400 hover:text-emerald-400 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>
                </div>
            </div>

            <a href="{{ route('admin.campaigns.show', $campaign) }}" class="block group">
                <h3 class="text-lg font-semibold text-white group-hover:text-emerald-400 transition-colors mb-2 line-clamp-1">{{ $campaign->name }}</h3>
                <p class="text-sm text-gray-400 mb-4 line-clamp-1">{{ $campaign->subject }}</p>
                
                <div class="space-y-3">
                    <div class="flex items-center text-sm text-gray-400">
                        <svg class="h-4 w-4 mr-2 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>{{ $campaign->contactList ? $campaign->contactList->name : 'No List Selected' }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-400">
                        <svg class="h-4 w-4 mr-2 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span>{{ $campaign->scheduled_at ? $campaign->scheduled_at->format('M d, Y h:i A') : 'Not Scheduled' }}</span>
                    </div>
                </div>
            </a>
        </div>

        @if($campaign->status == 'sent')
        <div class="bg-white/[0.03] px-6 py-4 border-t border-white/10">
            <div class="grid grid-cols-3 gap-2 text-center">
                <div>
                    <span class="block text-xs text-gray-400 uppercase tracking-wide">Open Rate</span>
                    <span class="block text-sm font-semibold text-emerald-400">{{ $campaign->open_rate }}%</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-400 uppercase tracking-wide">Click Rate</span>
                    <span class="block text-sm font-semibold text-emerald-400">{{ $campaign->click_rate }}%</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-400 uppercase tracking-wide">Sent</span>
                    <span class="block text-sm font-semibold text-white">{{ $campaign->total_sent }}</span>
                </div>
            </div>
        </div>
        @else
        <div class="bg-white/[0.03] px-6 py-4 border-t border-white/10 flex items-center justify-between">
            <span class="text-sm text-gray-400">
                Created {{ $campaign->created_at->diffForHumans() }}
            </span>
            @if($campaign->status == 'draft')
            <form action="{{ route('admin.campaigns.send', $campaign) }}" method="POST" onsubmit="return confirm('Are you sure you want to send this campaign now?');">
                @csrf
                <button type="submit" class="text-xs font-bold text-emerald-400 hover:text-emerald-300 uppercase tracking-wider flex items-center gap-1">
                    Send Now &rarr;
                </button>
            </form>
            @endif
        </div>
        @endif
    </div>
    @empty
    <div class="col-span-full">
        <div class="text-center py-12 bg-[#10231C]/60 rounded-2xl border border-dashed border-white/10">
            <div class="h-14 w-14 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center mx-auto mb-4">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <h3 class="mt-2 text-base font-bold text-white">No campaigns found</h3>
            <p class="mt-1 text-sm text-gray-400">Get started by creating a new email campaign.</p>
            <div class="mt-6">
                <a href="{{ route('admin.campaigns.create') }}" class="inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-900/30 transition-all hover:-translate-y-0.5">
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
