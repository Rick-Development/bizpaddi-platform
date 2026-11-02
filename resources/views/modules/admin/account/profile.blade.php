@extends('admin::layouts.app')

@section('title', 'Admin Profile Details')

@section('content')
<div class="mx-auto max-w-4xl">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-white">Admin Profile Details</h1>
            <p class="mt-2 text-sm text-gray-400">View and update your personal administrator information and credentials.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.administrators.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-white/10 bg-[#10231C]/60 text-sm font-semibold text-gray-300 hover:bg-white/[0.03] shadow-sm transition">
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Manage Administrators
            </a>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="mb-6 flex border-b border-white/10 space-x-8">
        <a href="{{ route('admin.account.profile.edit') }}" class="border-b-2 border-brand-primary pb-3 text-sm font-bold text-brand-primary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Profile Details
        </a>
        <a href="{{ route('admin.account.password.edit') }}" class="border-b-2 border-transparent pb-3 text-sm font-medium text-gray-400 hover:text-gray-300 hover:border-white/10 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
            </svg>
            Change Password
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="mb-6 grid gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-white/10 bg-[#10231C]/60 p-5 shadow-sm">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Logged In As</p>
            <p class="mt-2 text-lg font-bold text-white truncate">{{ $admin->name }}</p>
            <p class="mt-1 text-xs text-gray-400 truncate">{{ $admin->email }}</p>
        </div>
        <div class="rounded-2xl border border-white/10 bg-[#10231C]/60 p-5 shadow-sm">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Admin Role</p>
            <p class="mt-2 inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold {{ $admin->isSuperAdmin() ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $admin->isSuperAdmin() ? 'bg-purple-500' : 'bg-blue-500' }}"></span>
                {{ str_replace('_', ' ', ucfirst($admin->role)) }}
            </p>
            <p class="mt-2 text-xs text-gray-400">System Permissions Level</p>
        </div>
        <div class="rounded-2xl border border-white/10 bg-[#10231C]/60 p-5 shadow-sm">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Account Status</p>
            <p class="mt-2 inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold {{ $admin->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $admin->is_active ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                {{ $admin->is_active ? 'Active' : 'Inactive' }}
            </p>
            <p class="mt-2 text-xs text-gray-400">Created {{ $admin->created_at->format('M d, Y') }}</p>
        </div>
    </div>

    <!-- Edit Profile Form -->
    <div class="rounded-2xl border border-white/10 bg-[#10231C]/60 p-6 sm:p-8 shadow-sm">
        <form method="POST" action="{{ route('admin.account.profile.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="mb-2 block text-sm font-semibold text-gray-300">Full Name <span class="text-red-500">*</span></label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name', $admin->name) }}"
                        required
                        class="w-full rounded-xl border border-white/10 px-4 py-2.5 text-sm focus:border-brand-primary focus:outline-none focus:ring-2 focus:ring-brand-primary/20"
                    >
                    @error('name')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="mb-2 block text-sm font-semibold text-gray-300">Email Address <span class="text-red-500">*</span></label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email', $admin->email) }}"
                        required
                        class="w-full rounded-xl border border-white/10 px-4 py-2.5 text-sm focus:border-brand-primary focus:outline-none focus:ring-2 focus:ring-brand-primary/20"
                    >
                    @error('email')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Optional Password Section -->
            <div class="pt-6 border-t border-white/10" x-data="{ changePassword: false }">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-bold text-white">Password</h3>
                        <p class="text-xs text-gray-400">Want to update your password too? Check the box below.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" x-model="changePassword" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-[#10231C]/60 after:border-white/10 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-primary"></div>
                        <span class="ml-3 text-xs font-semibold text-gray-300" x-text="changePassword ? 'Changing Password' : 'Keep Current Password'"></span>
                    </label>
                </div>

                <div x-show="changePassword" x-cloak class="space-y-4 pt-2">
                    <div>
                        <label for="current_password" class="mb-1.5 block text-xs font-semibold text-gray-300">Current Password</label>
                        <input
                            type="password"
                            name="current_password"
                            id="current_password"
                            autocomplete="current-password"
                            placeholder="Enter current password"
                            class="w-full rounded-xl border border-white/10 px-4 py-2.5 text-sm focus:border-brand-primary focus:outline-none focus:ring-2 focus:ring-brand-primary/20 bg-[#0a1612] text-white border-white/10"
                        >
                        @error('current_password')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="mb-1.5 block text-xs font-semibold text-gray-300">New Password</label>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                autocomplete="new-password"
                                placeholder="At least 8 characters"
                                class="w-full rounded-xl border border-white/10 px-4 py-2.5 text-sm focus:border-brand-primary focus:outline-none focus:ring-2 focus:ring-brand-primary/20 bg-[#0a1612] text-white border-white/10"
                            >
                            @error('password')
                                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="mb-1.5 block text-xs font-semibold text-gray-300">Confirm New Password</label>
                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                autocomplete="new-password"
                                placeholder="Re-type new password"
                                class="w-full rounded-xl border border-white/10 px-4 py-2.5 text-sm focus:border-brand-primary focus:outline-none focus:ring-2 focus:ring-brand-primary/20 bg-[#0a1612] text-white border-white/10"
                            >
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between border-t border-white/10 pt-6">
                <p class="text-xs text-gray-400">Your details will be updated immediately upon saving.</p>
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-brand-primary px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-primary/20 transition hover:bg-brand-primary/90 hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
