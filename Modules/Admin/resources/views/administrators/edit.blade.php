@extends('admin::layouts.app')

@section('title', 'Edit Administrator: ' . $administrator->name)

@section('content')
<div class="mx-auto max-w-3xl space-y-6">
    <div class="border-b border-white/10 pb-5 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-white">Edit Administrator Details</h1>
            <p class="mt-1 text-xs sm:text-sm text-gray-400">Update account credentials, permissions, and status for {{ $administrator->name }}.</p>
        </div>
        <a href="{{ route('admin.administrators.index') }}" class="px-3.5 py-2 bg-white/5 border border-white/10 text-gray-200 rounded-xl text-xs font-bold hover:bg-white/10 transition">
            &larr; Back to List
        </a>
    </div>

    @if($administrator->id === auth('admin')->id())
    <div class="p-4 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-300 text-xs font-semibold flex items-center gap-2">
        <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>You are editing your own administrator account details.</span>
    </div>
    @endif

    <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 shadow-sm p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.administrators.update', $administrator) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="name" class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">Full Name <span class="text-rose-400">*</span></label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name', $administrator->name) }}"
                        required
                        class="w-full rounded-xl border border-white/15 bg-[#0a1612] px-4 py-2.5 text-sm text-white focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    >
                    @error('name')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">Email Address <span class="text-rose-400">*</span></label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email', $administrator->email) }}"
                        required
                        class="w-full rounded-xl border border-white/15 bg-[#0a1612] px-4 py-2.5 text-sm text-white focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    >
                    @error('email')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-4 border-t border-white/10">
                <div>
                    <label for="role" class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">Role <span class="text-rose-400">*</span></label>
                    <select
                        name="role"
                        id="role"
                        required
                        class="w-full rounded-xl border border-white/15 px-4 py-2.5 text-sm bg-[#0a1612] text-white focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    >
                        <option value="admin" class="bg-[#08110e]" {{ old('role', $administrator->role) === 'admin' ? 'selected' : '' }}>Administrator (Workspace)</option>
                        @if(auth('admin')->user() && auth('admin')->user()->isSuperAdmin())
                        <option value="super_admin" class="bg-[#08110e]" {{ old('role', $administrator->role) === 'super_admin' ? 'selected' : '' }}>Super Administrator (Full System Control)</option>
                        @endif
                    </select>
                    @error('role')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center pt-5">
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
                        <div class="w-11 h-6 bg-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-white/20 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                        <span class="ml-3 text-xs font-bold text-gray-300">
                            Account Active {{ $administrator->id === auth('admin')->id() ? '(Locked for your account)' : '' }}
                        </span>
                    </label>
                    @error('is_active')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Optional Reset Password -->
            <div class="pt-5 border-t border-white/10" x-data="{ resetPassword: false }">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-300">Reset Administrator Password</h3>
                        <p class="text-[11px] text-gray-500">Leave blank to keep the current password unchanged.</p>
                    </div>
                    <button
                        type="button"
                        @click="resetPassword = !resetPassword"
                        class="text-xs font-bold px-3 py-1.5 rounded-lg border border-white/10 hover:bg-white/10 text-gray-200 transition bg-white/5"
                        x-text="resetPassword ? 'Cancel' : 'Set New Password'"
                    ></button>
                </div>

                <div x-show="resetPassword" x-cloak class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div>
                        <label for="password" class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1">New Password</label>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            autocomplete="new-password"
                            placeholder="At least 8 characters"
                            class="w-full rounded-xl border border-white/15 bg-[#0a1612] px-4 py-2.5 text-sm text-white focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        >
                        @error('password')
                            <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1">Confirm New Password</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            autocomplete="new-password"
                            placeholder="Re-type new password"
                            class="w-full rounded-xl border border-white/15 bg-[#0a1612] px-4 py-2.5 text-sm text-white focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        >
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-white/10">
                <a href="{{ route('admin.administrators.index') }}" class="px-4 py-2 rounded-xl border border-white/10 text-xs font-bold text-gray-300 bg-white/5 hover:bg-white/10 transition">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-[#0E5E4A] text-xs font-bold text-white shadow-lg shadow-emerald-900/30 hover:bg-emerald-600 transition hover:-translate-y-0.5">
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
