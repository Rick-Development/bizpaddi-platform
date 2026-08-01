@extends('teamportal::layouts.app')

@section('title', 'Clients')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Clients</h1>
        <p class="mt-2 text-sm text-gray-600">View and manage client details</p>
    </div>
</div>

<!-- Search -->
<div class="mb-6">
    <form action="{{ route('team.clients.index') }}" method="GET" class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <input type="text" name="search" value="{{ request('search') }}" 
            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-brand-primary focus:border-brand-primary sm:text-sm shadow-sm"
            placeholder="Search clients by name, company, or email...">
    </form>
</div>

@if($clients->isEmpty())
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
    <div class="mx-auto h-16 w-16 rounded-full bg-gray-50 flex items-center justify-center mb-4">
        <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
    </div>
    <h3 class="text-lg font-semibold text-gray-900">No clients found</h3>
    <p class="mt-2 text-sm text-gray-500">Try adjusting your search query.</p>
</div>
@else
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($clients as $client)
    <div class="group relative bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden">
        <div class="p-6">
            <!-- Header with Status -->
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-3 flex-1">
                    <div class="h-12 w-12 rounded-full bg-brand-primary flex items-center justify-center text-white font-bold text-lg shadow-lg">
                        {{ strtoupper(substr($client->name, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base font-bold text-gray-900 truncate">{{ $client->name }}</h3>
                        <p class="text-xs text-gray-500 truncate">{{ $client->company_name ?? 'No Company' }}</p>
                    </div>
                </div>
                
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold 
                    {{ $client->is_active ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-gray-50 text-gray-600 border border-gray-200' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $client->is_active ? 'bg-green-500' : 'bg-gray-400' }} mr-1.5"></span>
                    {{ $client->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            <!-- Contact Info -->
            <div class="space-y-2 mb-4">
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span class="truncate">{{ $client->email }}</span>
                </div>
                @if($client->phone)
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    {{ $client->phone }}
                </div>
                @endif
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 gap-4 py-4 border-t border-gray-100 bg-gray-50/50 rounded-xl mb-4">
                <div class="text-center">
                    <span class="block text-2xl font-bold text-gray-900">{{ $client->projects()->count() }}</span>
                    <span class="text-xs text-gray-500 uppercase tracking-wide">Projects</span>
                </div>
                <div class="text-center border-l border-gray-200">
                    <span class="block text-2xl font-bold text-gray-900">{{ $client->invoices()->count() }}</span>
                    <span class="text-xs text-gray-500 uppercase tracking-wide">Invoices</span>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-2">
                <a href="{{ route('team.clients.show', $client->id) }}" class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-brand-primary text-sm font-medium rounded-lg text-brand-primary bg-white hover:bg-brand-primary hover:text-white transition-colors">
                    View Profile
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-6">
    {{ $clients->links() }}
</div>
@endif
@endsection
