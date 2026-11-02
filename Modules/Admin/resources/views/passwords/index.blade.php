@extends('admin::layouts.app')

@section('title', 'Password Manager')

@section('content')
@php
    $activeFormKey = old('account_type') && old('account_id')
        ? old('account_type') . ':' . old('account_id')
        : null;
@endphp

<div class="mb-8">
    <div class="sm:flex sm:items-start sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-white">Password Manager</h1>
            <p class="mt-2 max-w-3xl text-sm text-gray-400">Change passwords for admin accounts, team members, and clients from one secure screen. Passwords are never shown here, only replaced.</p>
        </div>
    </div>
</div>

@if($errors->any())
<div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4">
    <p class="text-sm font-semibold text-red-800">The password update could not be completed.</p>
    <ul class="mt-2 space-y-1 text-sm text-red-700">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="mb-6 grid gap-4 md:grid-cols-3">
    <div class="rounded-2xl border border-white/10 bg-[#10231C]/60 p-5 shadow-sm">
        <p class="text-sm font-medium text-gray-400">Admin Accounts</p>
        <p class="mt-2 text-3xl font-bold text-white">{{ $admins->total() }}</p>
        <p class="mt-1 text-sm text-gray-400">Includes super admins and staff admins.</p>
    </div>
    <div class="rounded-2xl border border-white/10 bg-[#10231C]/60 p-5 shadow-sm">
        <p class="text-sm font-medium text-gray-400">Team Members</p>
        <p class="mt-2 text-3xl font-bold text-white">{{ $teamMembers->total() }}</p>
        <p class="mt-1 text-sm text-gray-400">Internal portal users and project team accounts.</p>
    </div>
    <div class="rounded-2xl border border-white/10 bg-[#10231C]/60 p-5 shadow-sm">
        <p class="text-sm font-medium text-gray-400">Clients</p>
        <p class="mt-2 text-3xl font-bold text-white">{{ $clients->total() }}</p>
        <p class="mt-1 text-sm text-gray-400">Customer accounts with client portal access.</p>
    </div>
</div>

<div class="mb-6 rounded-2xl border border-amber-200 bg-amber-50 p-5">
    <p class="text-sm font-semibold text-amber-900">Restricted Access</p>
    <p class="mt-1 text-sm text-amber-800">This screen is limited to super admins because it can replace the login password for any account in the system.</p>
</div>

<div class="mb-8 rounded-2xl border border-white/10 bg-[#10231C]/60 p-6 shadow-sm">
    <form method="GET" class="grid gap-4 md:grid-cols-[minmax(0,1fr)_auto]">
        <div>
            <label for="search" class="mb-2 block text-sm font-medium text-gray-300">Search Accounts</label>
            <input
                type="text"
                name="search"
                id="search"
                value="{{ $search }}"
                placeholder="Search by name, email, role, department, or company"
                class="w-full rounded-xl border border-white/10 px-4 py-3 text-sm focus:border-brand-primary focus:outline-none focus:ring-2 focus:ring-brand-primary/20 bg-[#0a1612] text-white border-white/10"
            >
        </div>
        <div class="flex items-end gap-3">
            <button type="submit" class="inline-flex items-center rounded-xl bg-brand-primary px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-brand-primary/20 transition hover:bg-brand-primary/90">
                Search
            </button>
            @if($search !== '')
                <a href="{{ route('admin.passwords.index') }}" class="inline-flex items-center rounded-xl border border-white/10 px-5 py-3 text-sm font-semibold text-gray-300 transition hover:bg-white/[0.03]">
                    Clear
                </a>
            @endif
        </div>
    </form>
</div>

<div class="space-y-8">
    <section id="admin-accounts" class="rounded-2xl border border-white/10 bg-[#10231C]/60 border border-white/10">
        <div class="border-b border-white/10 px-6 py-5">
            <h2 class="text-xl font-bold text-white">Admin Accounts</h2>
            <p class="mt-1 text-sm text-gray-400">Use this when the website owner needs to replace an admin login password.</p>
        </div>

        <div class="divide-y divide-white/5">
            @forelse($admins as $adminAccount)
                @php $formKey = 'admin:' . $adminAccount->id; @endphp
                <div class="p-6" x-data="{ open: {{ $activeFormKey === $formKey ? 'true' : 'false' }} }">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="text-base font-semibold text-white">{{ $adminAccount->name }}</h3>
                                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">{{ str_replace('_', ' ', ucfirst($adminAccount->role)) }}</span>
                                @if($adminAccount->is_active)
                                    <span class="rounded-full bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-700">Active</span>
                                @else
                                    <span class="rounded-full bg-white/[0.06] px-2.5 py-1 text-xs font-semibold text-gray-400">Inactive</span>
                                @endif
                                @if(auth('admin')->id() === $adminAccount->id)
                                    <span class="rounded-full bg-brand-primary/10 px-2.5 py-1 text-xs font-semibold text-brand-primary">Current Account</span>
                                @endif
                            </div>
                            <p class="mt-2 text-sm text-gray-400">{{ $adminAccount->email }}</p>
                        </div>

                        <button type="button" @click="open = !open" class="inline-flex items-center justify-center rounded-xl border border-white/10 px-4 py-2.5 text-sm font-semibold text-gray-300 transition hover:bg-white/[0.03]">
                            <span x-text="open ? 'Hide Form' : 'Change Password'"></span>
                        </button>
                    </div>

                    <form x-show="open" style="display: none;" method="POST" action="{{ route('admin.passwords.update') }}" class="mt-5 grid gap-4 rounded-2xl bg-white/[0.03] p-5 md:grid-cols-2">
                        @csrf
                        <input type="hidden" name="account_type" value="admin">
                        <input type="hidden" name="account_id" value="{{ $adminAccount->id }}">

                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-300" for="admin-password-{{ $adminAccount->id }}">New Password</label>
                            <input type="password" name="password" id="admin-password-{{ $adminAccount->id }}" minlength="8" autocomplete="new-password" class="w-full rounded-xl border border-white/10 px-4 py-3 text-sm focus:border-brand-primary focus:outline-none focus:ring-2 focus:ring-brand-primary/20">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-300" for="admin-password-confirmation-{{ $adminAccount->id }}">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="admin-password-confirmation-{{ $adminAccount->id }}" minlength="8" autocomplete="new-password" class="w-full rounded-xl border border-white/10 px-4 py-3 text-sm focus:border-brand-primary focus:outline-none focus:ring-2 focus:ring-brand-primary/20">
                        </div>

                        @if($activeFormKey === $formKey)
                            @error('password')
                                <p class="md:col-span-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        @endif

                        <div class="md:col-span-2 flex flex-wrap items-center justify-between gap-3">
                            <p class="text-sm text-gray-400">Choose a strong password with at least 8 characters.</p>
                            <button type="submit" class="inline-flex items-center rounded-xl bg-gray-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-black">
                                Update Admin Password
                            </button>
                        </div>
                    </form>
                </div>
            @empty
                <div class="p-6 text-sm text-gray-400">No admin accounts matched your search.</div>
            @endforelse
        </div>

        @if($admins->hasPages())
            <div class="border-t border-white/10 px-6 py-4">
                {{ $admins->links() }}
            </div>
        @endif
    </section>

    <section id="team-members" class="rounded-2xl border border-white/10 bg-[#10231C]/60 border border-white/10">
        <div class="border-b border-white/10 px-6 py-5">
            <h2 class="text-xl font-bold text-white">Team Members</h2>
            <p class="mt-1 text-sm text-gray-400">Replace passwords for staff accounts, including project managers and other team portal users.</p>
        </div>

        <div class="divide-y divide-white/5">
            @forelse($teamMembers as $member)
                @php $formKey = 'team_member:' . $member->id; @endphp
                <div class="p-6" x-data="{ open: {{ $activeFormKey === $formKey ? 'true' : 'false' }} }">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="text-base font-semibold text-white">{{ $member->name }}</h3>
                                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">{{ ucfirst($member->role ?? 'team') }}</span>
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $member->status === 'active' ? 'bg-green-50 text-green-700' : ($member->status === 'suspended' ? 'bg-amber-50 text-amber-700' : 'bg-white/[0.06] text-gray-400') }}">
                                    {{ ucfirst($member->status ?? 'active') }}
                                </span>
                                @if($member->department)
                                    <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">{{ $member->department }}</span>
                                @endif
                            </div>
                            <p class="mt-2 text-sm text-gray-400">{{ $member->email }}</p>
                        </div>

                        <button type="button" @click="open = !open" class="inline-flex items-center justify-center rounded-xl border border-white/10 px-4 py-2.5 text-sm font-semibold text-gray-300 transition hover:bg-white/[0.03]">
                            <span x-text="open ? 'Hide Form' : 'Change Password'"></span>
                        </button>
                    </div>

                    <form x-show="open" style="display: none;" method="POST" action="{{ route('admin.passwords.update') }}" class="mt-5 grid gap-4 rounded-2xl bg-white/[0.03] p-5 md:grid-cols-2">
                        @csrf
                        <input type="hidden" name="account_type" value="team_member">
                        <input type="hidden" name="account_id" value="{{ $member->id }}">

                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-300" for="team-password-{{ $member->id }}">New Password</label>
                            <input type="password" name="password" id="team-password-{{ $member->id }}" minlength="8" autocomplete="new-password" class="w-full rounded-xl border border-white/10 px-4 py-3 text-sm focus:border-brand-primary focus:outline-none focus:ring-2 focus:ring-brand-primary/20">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-300" for="team-password-confirmation-{{ $member->id }}">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="team-password-confirmation-{{ $member->id }}" minlength="8" autocomplete="new-password" class="w-full rounded-xl border border-white/10 px-4 py-3 text-sm focus:border-brand-primary focus:outline-none focus:ring-2 focus:ring-brand-primary/20">
                        </div>

                        @if($activeFormKey === $formKey)
                            @error('password')
                                <p class="md:col-span-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        @endif

                        <div class="md:col-span-2 flex flex-wrap items-center justify-between gap-3">
                            <p class="text-sm text-gray-400">The team member will need the new password to sign in again.</p>
                            <button type="submit" class="inline-flex items-center rounded-xl bg-gray-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-black">
                                Update Team Password
                            </button>
                        </div>
                    </form>
                </div>
            @empty
                <div class="p-6 text-sm text-gray-400">No team members matched your search.</div>
            @endforelse
        </div>

        @if($teamMembers->hasPages())
            <div class="border-t border-white/10 px-6 py-4">
                {{ $teamMembers->links() }}
            </div>
        @endif
    </section>

    <section id="clients" class="rounded-2xl border border-white/10 bg-[#10231C]/60 border border-white/10">
        <div class="border-b border-white/10 px-6 py-5">
            <h2 class="text-xl font-bold text-white">Clients</h2>
            <p class="mt-1 text-sm text-gray-400">Replace client portal passwords without opening each profile one by one.</p>
        </div>

        <div class="divide-y divide-white/5">
            @forelse($clients as $client)
                @php $formKey = 'client:' . $client->id; @endphp
                <div class="p-6" x-data="{ open: {{ $activeFormKey === $formKey ? 'true' : 'false' }} }">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="text-base font-semibold text-white">{{ $client->name }}</h3>
                                @if($client->company_name)
                                    <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">{{ $client->company_name }}</span>
                                @endif
                                @if($client->is_active)
                                    <span class="rounded-full bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-700">Active</span>
                                @else
                                    <span class="rounded-full bg-white/[0.06] px-2.5 py-1 text-xs font-semibold text-gray-400">Inactive</span>
                                @endif
                            </div>
                            <p class="mt-2 text-sm text-gray-400">{{ $client->email }}</p>
                        </div>

                        <button type="button" @click="open = !open" class="inline-flex items-center justify-center rounded-xl border border-white/10 px-4 py-2.5 text-sm font-semibold text-gray-300 transition hover:bg-white/[0.03]">
                            <span x-text="open ? 'Hide Form' : 'Change Password'"></span>
                        </button>
                    </div>

                    <form x-show="open" style="display: none;" method="POST" action="{{ route('admin.passwords.update') }}" class="mt-5 grid gap-4 rounded-2xl bg-white/[0.03] p-5 md:grid-cols-2">
                        @csrf
                        <input type="hidden" name="account_type" value="client">
                        <input type="hidden" name="account_id" value="{{ $client->id }}">

                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-300" for="client-password-{{ $client->id }}">New Password</label>
                            <input type="password" name="password" id="client-password-{{ $client->id }}" minlength="8" autocomplete="new-password" class="w-full rounded-xl border border-white/10 px-4 py-3 text-sm focus:border-brand-primary focus:outline-none focus:ring-2 focus:ring-brand-primary/20">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-300" for="client-password-confirmation-{{ $client->id }}">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="client-password-confirmation-{{ $client->id }}" minlength="8" autocomplete="new-password" class="w-full rounded-xl border border-white/10 px-4 py-3 text-sm focus:border-brand-primary focus:outline-none focus:ring-2 focus:ring-brand-primary/20">
                        </div>

                        @if($activeFormKey === $formKey)
                            @error('password')
                                <p class="md:col-span-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        @endif

                        <div class="md:col-span-2 flex flex-wrap items-center justify-between gap-3">
                            <p class="text-sm text-gray-400">Use this when a client needs a manual reset from the admin side.</p>
                            <button type="submit" class="inline-flex items-center rounded-xl bg-gray-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-black">
                                Update Client Password
                            </button>
                        </div>
                    </form>
                </div>
            @empty
                <div class="p-6 text-sm text-gray-400">No clients matched your search.</div>
            @endforelse
        </div>

        @if($clients->hasPages())
            <div class="border-t border-white/10 px-6 py-4">
                {{ $clients->links() }}
            </div>
        @endif
    </section>
</div>
@endsection
