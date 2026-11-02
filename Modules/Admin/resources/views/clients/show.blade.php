@extends('admin::layouts.app')

@section('title', 'Client Details: ' . $client->name)

@section('content')
<div class="space-y-6">
    <div class="border-b border-white/10 pb-5 flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center gap-x-4">
            <a href="{{ route('admin.clients.index') }}" class="p-2 rounded-xl bg-white/5 border border-white/10 text-gray-400 hover:text-white hover:bg-white/10 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl font-black text-white tracking-tight">{{ $client->name }}</h1>
                <p class="mt-0.5 text-xs sm:text-sm text-gray-400">{{ $client->company_name ?? 'No Company' }}</p>
            </div>
        </div>
        <div class="flex gap-x-3">
            <a href="{{ route('admin.clients.edit', $client) }}" class="inline-flex items-center px-3.5 py-2 border border-white/10 text-xs font-bold rounded-xl text-gray-200 bg-white/5 hover:bg-white/10 transition">
                Edit Client
            </a>
            <form action="{{ route('admin.clients.destroy', $client) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this client?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-3.5 py-2 border border-rose-500/20 text-xs font-bold rounded-xl text-rose-400 bg-rose-500/10 hover:bg-rose-500/20 transition">
                    Delete
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Client Information -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 p-6 shadow-sm">
                <h3 class="text-base font-bold text-white mb-4">Contact Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <dt class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Email Address</dt>
                        <dd class="mt-1 text-sm font-semibold text-white">{{ $client->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Phone Number</dt>
                        <dd class="mt-1 text-sm font-semibold text-white">{{ $client->phone ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Website</dt>
                        <dd class="mt-1 text-sm font-semibold text-emerald-400">
                            @if($client->website)
                            <a href="{{ $client->website }}" target="_blank" class="hover:underline">{{ $client->website }}</a>
                            @else
                            <span class="text-gray-500">-</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Industry</dt>
                        <dd class="mt-1 text-sm font-semibold text-white">{{ $client->industry ?? '-' }}</dd>
                    </div>
                </div>
            </div>

            <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 p-6 shadow-sm">
                <h3 class="text-base font-bold text-white mb-4">Address Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <dt class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Address</dt>
                        <dd class="mt-1 text-sm font-semibold text-white">{{ $client->address ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">City</dt>
                        <dd class="mt-1 text-sm font-semibold text-white">{{ $client->city ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">State / Province</dt>
                        <dd class="mt-1 text-sm font-semibold text-white">{{ $client->state ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">ZIP / Postal Code</dt>
                        <dd class="mt-1 text-sm font-semibold text-white">{{ $client->zip ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Country</dt>
                        <dd class="mt-1 text-sm font-semibold text-white">{{ $client->country ?? '-' }}</dd>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status & Meta -->
        <div class="space-y-6">
            <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 p-6 shadow-sm">
                <h3 class="text-base font-bold text-white mb-4">Account Status</h3>
                <div>
                    <dt class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Status</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $client->is_active ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20' }}">
                            {{ $client->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </dd>
                </div>
                <div class="mt-4">
                    <dt class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Member Since</dt>
                    <dd class="mt-1 text-sm font-semibold text-white">{{ $client->created_at ? $client->created_at->format('M d, Y') : 'Recently' }}</dd>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
