@extends('teamportal::layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">My Profile</h1>
            <p class="mt-1 text-sm text-gray-500">View and manage your account information</p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <a href="{{ route('team.profile.edit') }}" class="inline-flex items-center gap-2 rounded-lg bg-brand-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
                Edit Profile
            </a>
        </div>
    </div>

    <!-- Profile Card -->
    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 rounded-2xl overflow-hidden">
        <div class="px-6 py-8">
            <!-- Avatar and Name -->
            <div class="flex items-center gap-6 mb-8">
                <div class="h-20 w-20 rounded-full bg-brand-primary flex items-center justify-center text-white text-2xl font-bold">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    @if($user->role)
                    <span class="mt-2 inline-flex items-center rounded-full bg-brand-secondary/10 px-3 py-1 text-xs font-semibold text-brand-secondary">
                        {{ ucfirst($user->role) }}
                    </span>
                    @endif
                </div>
            </div>

            <!-- Profile Details Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Full Name</label>
                    <p class="text-base font-medium text-gray-900">{{ $user->name }}</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Email Address</label>
                    <p class="text-base font-medium text-gray-900">{{ $user->email }}</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Phone Number</label>
                    <p class="text-base font-medium text-gray-900">{{ $user->phone ?? 'Not provided' }}</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Department</label>
                    <p class="text-base font-medium text-gray-900">{{ $user->department ?? 'Not assigned' }}</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Account Status</label>
                    @php
                        $statusClasses = [
                            'active' => 'bg-green-50 text-green-700 ring-green-600/20',
                            'inactive' => 'bg-red-50 text-red-700 ring-red-600/20',
                            'pending' => 'bg-yellow-50 text-yellow-700 ring-yellow-600/20',
                        ];
                        $statusClass = $statusClasses[$user->status ?? 'active'] ?? 'bg-gray-50 text-gray-700 ring-gray-600/20';
                    @endphp
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset {{ $statusClass }}">
                        {{ ucfirst($user->status ?? 'Active') }}
                    </span>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Member Since</label>
                    <p class="text-base font-medium text-gray-900">{{ $user->created_at->format('F j, Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Section -->
    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 rounded-2xl overflow-hidden">
        <div class="px-6 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Password & Security</h3>
                    <p class="text-sm text-gray-500 mt-1">Manage your password and account security</p>
                </div>
                <a href="{{ route('team.profile.password') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                    Change Password
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
