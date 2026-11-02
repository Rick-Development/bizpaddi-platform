@extends('admin::layouts.app')

@section('title', 'Administrators Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="border-b border-white/10 pb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                    Access Control
                </span>
            </div>
            <h1 class="text-2xl font-black tracking-tight text-white mt-1">Administrators</h1>
            <p class="text-xs sm:text-sm text-gray-400">Manage portal administrative accounts, permissions, and status.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.account.profile.edit') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border border-white/10 bg-white/5 text-xs font-bold text-gray-200 hover:bg-white/10 shadow-sm transition">
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                My Profile
            </a>
            <a href="{{ route('admin.administrators.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#0E5E4A] text-xs font-bold text-white shadow-lg shadow-emerald-900/30 hover:bg-emerald-600 transition hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Administrator
            </a>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-[#10231C]/60 rounded-2xl p-4 border border-white/10 shadow-sm">
            <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider">Total Admins</p>
            <p class="mt-1 text-2xl font-black text-white">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-[#10231C]/60 rounded-2xl p-4 border border-white/10 shadow-sm">
            <p class="text-[10px] font-extrabold text-purple-400 uppercase tracking-wider">Super Admins</p>
            <p class="mt-1 text-2xl font-black text-purple-300">{{ $stats['super_admin'] }}</p>
        </div>
        <div class="bg-[#10231C]/60 rounded-2xl p-4 border border-white/10 shadow-sm">
            <p class="text-[10px] font-extrabold text-emerald-400 uppercase tracking-wider">Active</p>
            <p class="mt-1 text-2xl font-black text-emerald-400">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-[#10231C]/60 rounded-2xl p-4 border border-white/10 shadow-sm">
            <p class="text-[10px] font-extrabold text-rose-400 uppercase tracking-wider">Inactive</p>
            <p class="mt-1 text-2xl font-black text-rose-400">{{ $stats['inactive'] }}</p>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-[#10231C]/60 rounded-2xl p-4 border border-white/10 shadow-sm">
        <form method="GET" action="{{ route('admin.administrators.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div class="sm:col-span-2 relative">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search administrators by name or email..."
                    class="w-full pl-10 pr-4 py-2 text-sm rounded-xl border border-white/15 bg-[#0a1612] text-white placeholder-gray-500 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none"
                >
                <svg class="w-4 h-4 text-gray-500 absolute left-3.5 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <div>
                <select name="role" class="w-full px-3 py-2 text-sm rounded-xl border border-white/15 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none bg-[#0a1612] text-white">
                    <option value="" class="bg-[#08110e]">All Roles</option>
                    <option value="super_admin" class="bg-[#08110e]" {{ request('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="admin" class="bg-[#08110e]" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div class="flex gap-2">
                <select name="status" class="w-full px-3 py-2 text-sm rounded-xl border border-white/15 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none bg-[#0a1612] text-white">
                    <option value="" class="bg-[#08110e]">All Statuses</option>
                    <option value="active" class="bg-[#08110e]" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" class="bg-[#08110e]" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>

                <button type="submit" class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/15 border border-white/10 text-white text-xs font-bold transition">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'role', 'status']))
                <a href="{{ route('admin.administrators.index') }}" class="px-3 py-2 rounded-xl border border-white/10 text-gray-400 hover:text-white text-xs font-bold flex items-center justify-center bg-white/5">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Administrators Table -->
    <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-white/5 border-b border-white/10 text-xs font-bold text-gray-400 uppercase tracking-wider">
                        <th class="py-3 px-6">Administrator</th>
                        <th class="py-3 px-6">Role</th>
                        <th class="py-3 px-6">Status</th>
                        <th class="py-3 px-6">Created</th>
                        <th class="py-3 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @forelse($admins as $adm)
                    <tr class="hover:bg-white/5 transition">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-sm text-white shadow-sm border border-white/10 {{ $adm->isSuperAdmin() ? 'bg-gradient-to-br from-purple-600 to-indigo-700' : 'bg-gradient-to-br from-[#0E5E4A] to-emerald-700' }}">
                                    {{ strtoupper(substr($adm->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="font-bold text-white flex items-center gap-2">
                                        <span>{{ $adm->name }}</span>
                                        @if($adm->id === auth('admin')->id())
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">You</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-400">{{ $adm->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold {{ $adm->isSuperAdmin() ? 'bg-purple-500/10 text-purple-300 border border-purple-500/20' : 'bg-blue-500/10 text-blue-300 border border-blue-500/20' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $adm->isSuperAdmin() ? 'bg-purple-400' : 'bg-blue-400' }}"></span>
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
                                    class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold transition {{ $adm->is_active ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20 hover:bg-rose-500/20' }} {{ $adm->id === auth('admin')->id() ? 'cursor-default opacity-80' : 'cursor-pointer' }}"
                                    title="{{ $adm->id === auth('admin')->id() ? 'Cannot change own status' : 'Click to toggle status' }}"
                                >
                                    <span class="w-1.5 h-1.5 rounded-full {{ $adm->is_active ? 'bg-emerald-400' : 'bg-rose-400' }}"></span>
                                    {{ $adm->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>
                        <td class="py-4 px-6 text-gray-400 text-xs">
                            {{ $adm->created_at->format('M d, Y') }}
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a
                                    href="{{ route('admin.administrators.edit', $adm) }}"
                                    class="p-2 rounded-xl text-gray-400 hover:text-emerald-400 hover:bg-white/5 transition"
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
                                        class="p-2 rounded-xl text-gray-400 hover:text-rose-400 hover:bg-rose-500/10 transition"
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
                        <td colspan="5" class="py-12 text-center text-gray-400">
                            <div class="mx-auto w-12 h-12 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 mb-3">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <p class="font-bold text-white">No administrators found</p>
                            <p class="text-xs text-gray-400 mt-1">Try changing your search keywords or filter options.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($admins->hasPages())
        <div class="px-6 py-4 border-t border-white/10">
            {{ $admins->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
