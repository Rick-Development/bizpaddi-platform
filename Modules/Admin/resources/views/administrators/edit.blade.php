@extends('admin::layouts.app')

@section('title', 'Edit Administrator: ' . $administrator->name)

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Edit Administrator Details</h1>
            <p class="mt-1 text-sm text-gray-500">Update account credentials, permissions, and status for {{ $administrator->name }}.</p>
        </div>
        <a href="{{ route('admin.administrators.index') }}" class="px-4 py-2 bg-gray-900 text-white rounded-xl text-sm font-semibold hover:bg-black transition">
            &larr; Back to List
        </a>
    </div>

    @if($administrator->id === auth('admin')->id())
    <div class="mb-6 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-sm flex items-center gap-2">
        <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>You are editing your own administrator account details.</span>
    </div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.administrators.update', $administrator) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name', $administrator->name) }}"
                        required
                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-brand-primary focus:outline-none focus:ring-2 focus:ring-brand-primary/20"
                    >
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email', $administrator->email) }}"
                        required
                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-brand-primary focus:outline-none focus:ring-2 focus:ring-brand-primary/20"
                    >
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-gray-100">
                <div>
                    <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">Role <span class="text-red-500">*</span></label>
                    <select
                        name="role"
                        id="role"
                        required
                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm bg-white focus:border-brand-primary focus:outline-none focus:ring-2 focus:ring-brand-primary/20"
                    >
                        <option value="admin" {{ old('role', $administrator->role) === 'admin' ? 'selected' : '' }}>Administrator (Standard)</option>
                        <option value="super_admin" {{ old('role', $administrator->role) === 'super_admin' ? 'selected' : '' }}>Super Administrator (Full System Control)</option>
                    </select>
                    @error('role')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center pt-6">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input
                            type="checkbox"
                            name="is_active"
                            value="1"
                            {{ old('is_active', $administrator->is_active) ? 'checked' : '' }}
                            {{ $administrator->id === auth('admin')->id() ? 'disabled' : '' }}
                            class="sr-only peer"
                        >
                        @if($administrator->id === auth('admin')->id())
                        <input type="hidden" name="is_active" value="1">
                        @endif
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                        <span class="ml-3 text-sm font-semibold text-gray-700">
                            Account Active {{ $administrator->id === auth('admin')->id() ? '(Locked for your account)' : '' }}
                        </span>
                    </label>
                    @error('is_active')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Optional Reset Password -->
            <div class="pt-6 border-t border-gray-100" x-data="{ resetPassword: false }">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Reset Administrator Password</h3>
                        <p class="text-xs text-gray-500">Leave blank to keep the current password unchanged.</p>
                    </div>
                    <button
                        type="button"
                        @click="resetPassword = !resetPassword"
                        class="text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-200 hover:bg-gray-50 text-gray-700 transition"
                        x-text="resetPassword ? 'Cancel Password Change' : 'Set New Password'"
                    ></button>
                </div>

                <div x-show="resetPassword" x-cloak class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div>
                        <label for="password" class="block text-xs font-semibold text-gray-700 mb-1">New Password</label>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            autocomplete="new-password"
                            placeholder="At least 8 characters"
                            class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-brand-primary focus:outline-none focus:ring-2 focus:ring-brand-primary/20"
                        >
                        @error('password')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-gray-700 mb-1">Confirm New Password</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            autocomplete="new-password"
                            placeholder="Re-type new password"
                            class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-brand-primary focus:outline-none focus:ring-2 focus:ring-brand-primary/20"
                        >
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.administrators.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-brand-primary text-sm font-semibold text-white shadow-lg shadow-brand-primary/25 hover:bg-brand-primary/90 transition hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Save Administrator Details
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
