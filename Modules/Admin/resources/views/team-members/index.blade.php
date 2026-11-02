@extends('admin::layouts.app')

@section('title', 'Team Members')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between border-b border-white/10 pb-5">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                    Staff & Workspace
                </span>
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight mt-1">Team Members</h1>
            <p class="text-xs sm:text-sm text-gray-400">Manage team members, roles, performance, and workspace access permissions.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex items-center space-x-3">
            <a href="{{ route('admin.team-members.create') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-[#0E5E4A] hover:bg-emerald-600 text-white text-xs font-bold rounded-xl shadow-lg shadow-emerald-900/30 transition-all hover:-translate-y-0.5">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Add Team Member</span>
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 p-5 shadow-sm">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, department..." class="w-full px-3.5 py-2 bg-[#0a1612] border border-white/15 rounded-xl text-white placeholder-gray-500 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            <div>
                <label class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">Role</label>
                <select name="role" class="w-full px-3.5 py-2 bg-[#0a1612] border border-white/15 rounded-xl text-white text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="" class="bg-[#08110e] text-white">All Roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" class="bg-[#08110e] text-white" {{ request('role') == $role->name ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">Status</label>
                <select name="status" class="w-full px-3.5 py-2 bg-[#0a1612] border border-white/15 rounded-xl text-white text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="" class="bg-[#08110e] text-white">All Statuses</option>
                    <option value="active" class="bg-[#08110e] text-white" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="suspended" class="bg-[#08110e] text-white" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    <option value="inactive" class="bg-[#08110e] text-white" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-white/10 hover:bg-white/15 border border-white/10 text-white font-bold text-sm rounded-xl transition">
                    Filter
                </button>
            </div>
        </form>
    </div>

    @if($teamMembers->isEmpty())
    <div class="bg-[#10231C]/40 rounded-2xl border border-white/10 p-12 text-center">
        <div class="mx-auto h-16 w-16 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center mb-4">
            <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
        </div>
        <h3 class="text-lg font-bold text-white">No team members yet</h3>
        <p class="mt-2 text-sm text-gray-400 max-w-sm mx-auto">Start building your team by adding your first team member to your business workspace.</p>
        <div class="mt-6">
            <a href="{{ route('admin.team-members.create') }}" class="inline-flex items-center px-4 py-2 bg-[#0E5E4A] hover:bg-emerald-600 text-white text-xs font-bold rounded-xl transition shadow-sm">
                Add Your First Team Member
            </a>
        </div>
    </div>
    @else
    <!-- Team Members Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($teamMembers as $member)
        <div class="group relative bg-[#10231C]/60 hover:bg-[#10231C]/90 rounded-2xl border border-white/10 hover:border-emerald-500/30 transition-all duration-300 p-6 shadow-sm overflow-hidden flex flex-col justify-between">
            <div>
                <!-- Header with Status -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-[#0E5E4A] to-emerald-700 flex items-center justify-center text-white font-black text-base shadow-lg border border-white/10 shrink-0">
                            {{ strtoupper(substr($member->name, 0, 2)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-base font-bold text-white truncate" title="{{ $member->name }}">{{ $member->name }}</h3>
                            <p class="text-xs text-gray-400 truncate">{{ $member->email }}</p>
                        </div>
                    </div>
                    
                    @if($member->status === 'active')
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5"></span>
                        Active
                    </span>
                    @elseif($member->status === 'suspended')
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20 shrink-0">
                        Suspended
                    </span>
                    @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-white/5 text-gray-400 border border-white/10 shrink-0">
                        Inactive
                    </span>
                    @endif
                </div>

                <!-- Role & Department -->
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm">
                        <svg class="h-4 w-4 mr-2 text-gray-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="text-gray-200 font-medium">{{ ucfirst($member->role ?? 'Member') }}</span>
                    </div>
                    @if($member->department)
                    <div class="flex items-center text-sm text-gray-400">
                        <svg class="h-4 w-4 mr-2 text-gray-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span class="truncate">{{ $member->department }}</span>
                    </div>
                    @endif
                    @if($member->phone)
                    <div class="flex items-center text-sm text-gray-400">
                        <svg class="h-4 w-4 mr-2 text-gray-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span>{{ $member->phone }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="pt-4 border-t border-white/10 flex items-center justify-between">
                <span class="text-xs text-gray-500">
                    Joined {{ $member->created_at ? $member->created_at->format('M d, Y') : 'Recently' }}
                </span>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.team-members.impersonate', $member) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-white bg-[#0E5E4A] hover:bg-emerald-600 rounded-xl shadow-sm transition-all hover:-translate-y-0.5" title="One-click login to {{ $member->name }}'s account">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        <span>Login</span>
                    </a>
                    <a href="{{ route('admin.team-members.show', $member) }}" class="p-1.5 text-gray-400 hover:text-emerald-400 hover:bg-white/5 rounded-lg transition-colors" title="View Profile">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </a>
                    <a href="{{ route('admin.team-members.edit', $member) }}" class="p-1.5 text-gray-400 hover:text-emerald-400 hover:bg-white/5 rounded-lg transition-colors" title="Edit">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>
                    <form action="{{ route('admin.team-members.destroy', $member) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this team member?');" class="inline-flex">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 text-gray-400 hover:text-rose-400 hover:bg-rose-500/10 rounded-lg transition-colors" title="Delete">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $teamMembers->links() }}
    </div>
    @endif
</div>
@endsection
