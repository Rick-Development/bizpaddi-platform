@extends('admin::layouts.app')

@section('title', 'Client Details: ' . $client->name)

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-x-4">
        <a href="{{ route('admin.clients.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div class="flex-1">
            <h1 class="text-2xl font-bold text-gray-900">{{ $client->name }}</h1>
            <p class="mt-1 text-sm text-gray-500">{{ $client->company_name ?? 'No Company' }}</p>
        </div>
        <div class="flex gap-x-3">
            <a href="{{ route('admin.clients.edit', $client) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary">
                Edit Client
            </a>
            <form action="{{ route('admin.clients.destroy', $client) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Client Information -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white shadow rounded-lg px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Contact Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $client->email }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $client->phone ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Website</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        @if($client->website)
                        <a href="{{ $client->website }}" target="_blank" class="text-brand-primary hover:underline">{{ $client->website }}</a>
                        @else
                        -
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Industry</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $client->industry ?? '-' }}</dd>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Address Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Address</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $client->address ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">City</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $client->city ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">State / Province</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $client->state ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">ZIP / Postal Code</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $client->zip ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Country</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $client->country ?? '-' }}</dd>
                </div>
            </div>
        </div>
    </div>

    <!-- Status & Meta -->
    <div class="space-y-6">
        <div class="bg-white shadow rounded-lg px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Account Status</h3>
            <div>
                <dt class="text-sm font-medium text-gray-500">Status</dt>
                <dd class="mt-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $client->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $client->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </dd>
            </div>
            <div class="mt-4">
                <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $client->created_at->format('M d, Y') }}</dd>
            </div>
        </div>
    </div>
</div>
@endsection
