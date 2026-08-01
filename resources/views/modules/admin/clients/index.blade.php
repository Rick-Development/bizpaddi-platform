@extends('admin::layouts.app')

@section('title', 'Clients')

@section('content')
<!-- Header -->
<div class="mb-8">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Clients</h1>
            <p class="mt-2 text-sm text-gray-500">Manage client profiles and access</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.clients.create') }}" class="inline-flex items-center px-5 py-2.5 bg-brand-primary text-white text-sm font-medium rounded-xl hover:bg-brand-primary/90 shadow-lg shadow-brand-primary/30 transition-all hover:-translate-y-0.5">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Add Client
            </a>
        </div>
    </div>
</div>

@if($clients->isEmpty())
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
    <div class="mx-auto h-16 w-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
        <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
        </svg>
    </div>
    <h3 class="text-lg font-semibold text-gray-900">No clients yet</h3>
    <p class="mt-2 text-sm text-gray-500 max-w-sm mx-auto">Get started by adding your first client to streamline billing and project management.</p>
    <div class="mt-6">
        <a href="{{ route('admin.clients.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-primary text-white text-sm font-medium rounded-lg hover:bg-brand-primary/90 transition-colors">
            Add Your First Client
        </a>
    </div>
</div>
@else
<!-- Client Cards Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($clients as $client)
    <div class="group relative bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden">
        <!-- Background Decoration -->
        <!-- <div class="absolute -right-8 -top-8 h-32 w-32 rounded-full opacity-5 transition-transform group-hover:scale-150 duration-500 bg-brand-primary"></div> -->
        
        <div class="p-6 relative z-10">
            <!-- Header with Status -->
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-brand-primary to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-lg">
                            {{ strtoupper(substr($client->name, 0, 2)) }}
                        </div>
                        <div class="flex-1">
                            <h3 class="text-base font-bold text-gray-900 line-clamp-1" title="{{ $client->name }}">{{ $client->name }}</h3>
                            @if($client->company_name)
                            <p class="text-xs text-gray-500 line-clamp-1">{{ $client->company_name }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                
                @if($client->is_active)
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-100">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                    Active
                </span>
                @else
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-gray-50 text-gray-600 border border-gray-200">
                    Inactive
                </span>
                @endif
            </div>

            <!-- Contact Info -->
            <div class="space-y-2 mb-4">
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="h-4 w-4 mr-2 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span class="truncate">{{ $client->email }}</span>
                </div>
                @if($client->phone)
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="h-4 w-4 mr-2 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <span>{{ $client->phone }}</span>
                </div>
                @endif
            </div>

            <!-- Footer -->
            <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                <span class="text-xs text-gray-500">
                    Added {{ $client->created_at->format('M d, Y') }}
                </span>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.clients.edit', $client) }}" class="p-1.5 text-gray-400 hover:text-brand-primary hover:bg-brand-primary/5 rounded-lg transition-colors" title="Edit">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>
                    <form action="{{ route('admin.clients.destroy', $client) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this client?');" class="inline-flex">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
<div class="mt-8">
    {{ $clients->links() }}
</div>
@endif
@endsection
