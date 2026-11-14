@extends('teamportal::layouts.app')

@section('title', 'Clients')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Clients</h1>
            <p class="mt-1 text-xs sm:text-sm text-gray-400">View and manage client details</p>
        </div>
        <a href="{{ route('team.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-semibold text-gray-300 transition-colors">
            &larr; Back to Dashboard
        </a>
    </div>

    <!-- Search -->
    <div>
        <form action="{{ route('team.clients.index') }}" method="GET" class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" 
                class="block w-full pl-10 pr-4 py-3 bg-[#10231C]/60 border border-white/10 rounded-2xl text-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 text-xs sm:text-sm shadow-inner"
                placeholder="Search clients by name, company, or email...">
        </form>
    </div>

    @if($clients->isEmpty())
    <div class="bg-[#10231C]/60 rounded-3xl border border-white/10 p-12 text-center backdrop-blur-sm shadow-xl">
        <div class="mx-auto h-14 w-14 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center mb-4 text-emerald-400">
            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </div>
        <h3 class="text-base font-bold text-white">No clients found</h3>
        <p class="mt-1 text-xs text-gray-400">Try adjusting your search query.</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($clients as $client)
        <div class="group relative bg-[#10231C]/60 rounded-3xl shadow-xl hover:border-emerald-500/30 transition-all border border-white/10 overflow-hidden backdrop-blur-sm">
            <div class="p-6">
                <!-- Header with Status -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="h-11 w-11 rounded-2xl bg-gradient-to-br from-emerald-600 to-[#10231C] border border-white/10 flex items-center justify-center text-white font-bold text-base shadow-sm shrink-0">
                            {{ strtoupper(substr($client->name, 0, 2)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-bold text-white truncate">{{ $client->name }}</h3>
                            <p class="text-xs text-gray-400 truncate">{{ $client->company_name ?? 'No Company' }}</p>
                        </div>
                    </div>
                    
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold shrink-0
                        {{ $client->is_active ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : 'bg-white/5 text-gray-400 border border-white/10' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $client->is_active ? 'bg-emerald-400' : 'bg-gray-400' }} mr-1.5"></span>
                        {{ $client->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <!-- Contact Info -->
                <div class="space-y-1.5 mb-4 text-xs">
                    <div class="flex items-center text-gray-300">
                        <svg class="h-4 w-4 mr-2 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="truncate">{{ $client->email }}</span>
                    </div>
                    @if($client->phone)
                    <div class="flex items-center text-gray-300">
                        <svg class="h-4 w-4 mr-2 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        {{ $client->phone }}
                    </div>
                    @endif
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-2 gap-4 py-3 border-t border-white/5 bg-[#08110e]/40 rounded-2xl mb-4">
                    <div class="text-center">
                        <span class="block text-xl font-black text-white">{{ $client->projects()->count() }}</span>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Projects</span>
                    </div>
                    <div class="text-center border-l border-white/5">
                        <span class="block text-xl font-black text-white">{{ $client->invoices()->count() }}</span>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Invoices</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('team.clients.show', $client->id) }}" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl transition-all shadow-md active:scale-95">
                        View Profile
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
