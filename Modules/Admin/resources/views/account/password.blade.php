@extends('admin::layouts.app')

@section('title', 'Change Password')

@section('content')
<div class="mx-auto max-w-4xl">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Change Password</h1>
            <p class="mt-2 text-sm text-gray-500">Update your own admin login password here. You will need your current password before setting a new one.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.administrators.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm transition">
                <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Manage Administrators
            </a>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="mb-6 flex border-b border-gray-200 space-x-8">
        <a href="{{ route('admin.account.profile.edit') }}" class="border-b-2 border-transparent pb-3 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Profile Details
        </a>
        <a href="{{ route('admin.account.password.edit') }}" class="border-b-2 border-brand-primary pb-3 text-sm font-bold text-brand-primary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
            </svg>
            Change Password
        </a>
    </div>

    <div class="mb-6 grid gap-4 md:grid-cols-2">
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Logged In As</p>
            <p class="mt-2 text-lg font-semibold text-gray-900">{{ $admin->name }}</p>
            <p class="mt-1 text-sm text-gray-600">{{ $admin->email }}</p>
        </div>
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Role</p>
            <p class="mt-2 inline-flex rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-700">{{ str_replace('_', ' ', ucfirst($admin->role)) }}</p>
            <p class="mt-3 text-sm text-gray-500">This page only changes your own account password.</p>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.account.password.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="mb-2 block text-sm font-medium text-gray-700">Current Password</label>
                <input
                    type="password"
                    name="current_password"
                    id="current_password"
                    autocomplete="current-password"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-brand-primary focus:outline-none focus:ring-2 focus:ring-brand-primary/20"
                >
                @error('current_password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="mb-2 block text-sm font-medium text-gray-700">New Password</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    autocomplete="new-password"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-brand-primary focus:outline-none focus:ring-2 focus:ring-brand-primary/20"
                >
                <p class="mt-2 text-xs text-gray-500">Use at least 8 characters. A stronger password is recommended.</p>
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="mb-2 block text-sm font-medium text-gray-700">Confirm New Password</label>
                <input
                    type="password"
                    name="password_confirmation"
                    id="password_confirmation"
                    autocomplete="new-password"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-brand-primary focus:outline-none focus:ring-2 focus:ring-brand-primary/20"
                >
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 pt-5">
                <p class="text-sm text-gray-500">After saving, your new password will be used for future admin logins.</p>
                <button type="submit" class="inline-flex items-center rounded-xl bg-gray-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-black">
                    Update My Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
