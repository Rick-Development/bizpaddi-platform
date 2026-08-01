@extends('admin::layouts.app')

@section('title', $campaign->name)

@section('content')
<!-- Header Section -->
<div class="mb-8">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $campaign->name }}</h1>
            <div class="mt-2 flex items-center text-sm text-gray-600">
                <span class="mr-4 capitalize px-2.5 py-0.5 rounded-full text-xs font-medium 
                    @if($campaign->status == 'sent') bg-green-100 text-green-800
                    @elseif($campaign->status == 'scheduled') bg-blue-100 text-blue-800
                    @elseif($campaign->status == 'draft') bg-gray-100 text-gray-800
                    @else bg-gray-100 text-gray-800 @endif">
                    {{ ucfirst($campaign->status) }}
                </span>
                <span>{{ $campaign->created_at->format('M d, Y h:i A') }}</span>
            </div>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <a href="{{ route('admin.campaigns.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FB4C4B]">
                Back to List
            </a>
            
            @if($campaign->status != 'sent')
            <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Edit Campaign
            </a>
            @endif

            @if($campaign->status == 'draft')
            <form action="{{ route('admin.campaigns.send', $campaign) }}" method="POST" onsubmit="return confirm('Are you sure you want to send this campaign now? This action cannot be undone.');">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-gradient-to-r from-[#FB4C4B] to-[#D93837] hover:from-[#D93837] hover:to-[#FB4C4B] shadow-sm hover:shadow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FB4C4B]">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    Send Now
                </button>
            </form>
            @endif

            <form action="{{ route('admin.campaigns.destroy', $campaign) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this campaign?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Stats (Only visible if sent) -->
        @if($campaign->status == 'sent')
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <p class="text-sm font-medium text-gray-500">Open Rate</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $campaign->open_rate }}%</p>
                <p class="mt-1 text-xs text-gray-500">{{ $campaign->total_opened }} opens</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <p class="text-sm font-medium text-gray-500">Click Rate</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $campaign->click_rate }}%</p>
                <p class="mt-1 text-xs text-gray-500">{{ $campaign->total_clicked }} clicks</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <p class="text-sm font-medium text-gray-500">Total Sent</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $campaign->total_sent }}</p>
                <p class="mt-1 text-xs text-gray-500">Recipients</p>
            </div>
        </div>
        @endif

        <!-- Email Content Review -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-900">Email Preview</h3>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <span class="block text-sm font-medium text-gray-500">Subject</span>
                    <span class="block text-lg text-gray-900">{{ $campaign->subject }}</span>
                </div>
                <div class="prose max-w-none border border-gray-200 rounded-lg p-6 bg-gray-50 min-h-[300px]">
                    {!! $campaign->content !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-900">Campaign Details</h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <span class="block text-sm font-medium text-gray-500">Contact List</span>
                    <a href="{{ route('admin.contact-lists.show', $campaign->contact_list_id) }}" class="text-sm font-medium text-[#FB4C4B] hover:text-[#D93837]">
                        {{ $campaign->contactList ? $campaign->contactList->name : 'No List Selected' }}
                    </a>
                </div>
                
                <div>
                    <span class="block text-sm font-medium text-gray-500">Sender Name</span>
                    <span class="block text-sm text-gray-900">{{ $campaign->from_name }}</span>
                </div>
                
                <div>
                    <span class="block text-sm font-medium text-gray-500">Sender Email</span>
                    <span class="block text-sm text-gray-900">{{ $campaign->from_email }}</span>
                </div>
                
                @if($campaign->scheduled_at)
                <div>
                    <span class="block text-sm font-medium text-gray-500">Scheduled For</span>
                    <span class="block text-sm text-gray-900">{{ $campaign->scheduled_at->format('M d, Y h:i A') }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
