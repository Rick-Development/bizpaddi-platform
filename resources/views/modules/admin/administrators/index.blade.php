@extends('admin::layouts.app')

@section('title', 'Administrators Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Administrators</h1>
            <p class="mt-1 text-sm text-gray-500">Manage portal administrative accounts, permissions, and status.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.account.profile.edit') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm transition">
                <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                My Profile Details
            </a>
            <a href="{{ route('admin.administrators.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-brand-primary text-sm font-semibold text-white shadow-lg shadow-brand-primary/25 hover:bg-brand-primary/90 transition hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Administrator
            </a>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Admins</p>
            <p class="mt-2 text-3xl font-extrabold text-gray-900">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <p class="text-xs font-bold text-purple-600 uppercase tracking-wider">Super Admins</p>
            <p class="mt-2 text-3xl font-extrabold text-purple-700">{{ $stats['super_admin'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Active</p>
            <p class="mt-2 text-3xl font-extrabold text-emerald-700">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <p class="text-xs font-bold text-red-500 uppercase tracking-wider">Inactive</p>
            <p class="mt-2 text-3xl font-extrabold text-red-600">{{ $stats['inactive'] }}</p>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
        <form method="GET" action="{{ route('admin.administrators.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div class="sm:col-span-2 relative">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search administrators by name or email..."
                    class="w-full pl-10 pr-4 py-2 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary outline-none"
                >
                <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <div>
                <select name="role" class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary outline-none bg-white">
                    <option value="">All Roles</option>
                    <option value="super_admin" {{ request('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div class="flex gap-2">
                <select name="status" class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary outline-none bg-white">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>

                <button type="submit" class="px-4 py-2 rounded-xl bg-gray-900 text-white text-sm font-semibold hover:bg-black transition">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'role', 'status']))
                <a href="{{ route('admin.administrators.index') }}" class="px-3 py-2 rounded-xl border border-gray-200 text-gray-500 hover:text-gray-700 text-sm font-semibold flex items-center justify-center">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Administrators Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100 text-xs font-bold text-gray-500 uppercase tracking-wider">
                        <th class="py-4 px-6">Administrator</th>
                        <th class="py-4 px-6">Role</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6">Created</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($admins as $adm)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm text-white shadow-sm {{ $adm->isSuperAdmin() ? 'bg-gradient-to-br from-purple-600 to-indigo-700' : 'bg-gradient-to-br from-brand-primary to-orange-500' }}">
                                    {{ strtoupper(substr($adm->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900 flex items-center gap-2">
                                        <span>{{ $adm->name }}</span>
                                        @if($adm->id === auth('admin')->id())
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">You</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $adm->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $adm->isSuperAdmin() ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $adm->isSuperAdmin() ? 'bg-purple-600' : 'bg-blue-600' }}"></span>
                                {{ str_replace('_', ' ', ucfirst($adm->role)) }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <form action="{{ route('admin.administrators.update-status', $adm) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button
                                    type="submit"
                                    {{ $adm->id === auth('admin')->id() ? 'disabled' : '' }}
                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition {{ $adm->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-red-50 text-red-600 border border-red-200 hover:bg-red-100' }} {{ $adm->id === auth('admin')->id() ? 'cursor-default opacity-80' : 'cursor-pointer' }}"
                                    title="{{ $adm->id === auth('admin')->id() ? 'Cannot change own status' : 'Click to toggle status' }}"
                                >
                                    <span class="w-1.5 h-1.5 rounded-full {{ $adm->is_active ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                    {{ $adm->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>
                        <td class="py-4 px-6 text-gray-500 text-xs">
                            {{ $adm->created_at->format('M d, Y') }}
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a
                                    href="{{ route('admin.administrators.edit', $adm) }}"
                                    class="p-2 rounded-xl text-gray-600 hover:text-brand-primary hover:bg-brand-primary/5 transition"
                                    title="Edit Administrator Details"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                @if($adm->id !== auth('admin')->id())
                                <form action="{{ route('admin.administrators.destroy', $adm) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete administrator {{ $adm->name }}? This action cannot be undone.');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="p-2 rounded-xl text-gray-400 hover:text-red-600 hover:bg-red-50 transition"
                                        title="Delete Administrator"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-gray-500">
                            <div class="mx-auto w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 mb-3">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <p class="font-semibold text-gray-700">No administrators found</p>
                            <p class="text-xs text-gray-400 mt-1">Try changing your search keywords or filter options.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($admins->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $admins->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
