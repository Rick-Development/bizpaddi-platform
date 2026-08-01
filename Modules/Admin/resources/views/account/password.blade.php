@extends('admin::layouts.app')

@section('title', 'Change Password')

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Change Password</h1>
        <p class="mt-2 text-sm text-gray-500">Update your own admin login password here. You will need your current password before setting a new one.</p>
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
