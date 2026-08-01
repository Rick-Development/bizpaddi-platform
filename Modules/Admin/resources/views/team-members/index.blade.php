@extends('admin::layouts.app')

@section('title', 'Team Members')

@section('content')
<!-- Header -->
<div class="mb-8">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Team Members</h1>
            <p class="mt-2 text-sm text-gray-500">Manage team members, roles, and permissions</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.team-members.create') }}" class="inline-flex items-center px-5 py-2.5 bg-brand-primary text-white text-sm font-medium rounded-xl hover:bg-brand-primary/90 shadow-lg shadow-brand-primary/30 transition-all hover:-translate-y-0.5">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Add Team Member
            </a>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, department..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
            <select name="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                <option value="">All Roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                Filter
            </button>
        </div>
    </form>
</div>

@if($teamMembers->isEmpty())
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
    <div class="mx-auto h-16 w-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
        <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
        </svg>
    </div>
    <h3 class="text-lg font-semibold text-gray-900">No team members yet</h3>
    <p class="mt-2 text-sm text-gray-500 max-w-sm mx-auto">Start building your team by adding your first team member.</p>
    <div class="mt-6">
        <a href="{{ route('admin.team-members.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-primary text-white text-sm font-medium rounded-lg hover:bg-brand-primary/90 transition-colors">
            Add Your First Team Member
        </a>
    </div>
</div>
@else
<!-- Team Members Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($teamMembers as $member)
    <div class="group relative bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden">
        <div class="p-6">
            <!-- Header with Status -->
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-3 flex-1">
                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-brand-primary to-purple-600 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                        {{ strtoupper(substr($member->name, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base font-bold text-gray-900 truncate" title="{{ $member->name }}">{{ $member->name }}</h3>
                        <p class="text-xs text-gray-500 truncate">{{ $member->email }}</p>
                    </div>
                </div>
                
                @if($member->status === 'active')
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-100">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                    Active
                </span>
                @elseif($member->status === 'suspended')
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-orange-50 text-orange-700 border border-orange-100">
                    Suspended
                </span>
                @else
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-gray-50 text-gray-600 border border-gray-200">
                    Inactive
                </span>
                @endif
            </div>

            <!-- Role & Department -->
            <div class="space-y-2 mb-4">
                <div class="flex items-center text-sm">
                    <svg class="h-4 w-4 mr-2 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span class="text-gray-700 font-medium">{{ ucfirst($member->role) }}</span>
                </div>
                @if($member->department)
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="h-4 w-4 mr-2 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span class="truncate">{{ $member->department }}</span>
                </div>
                @endif
                @if($member->phone)
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="h-4 w-4 mr-2 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <span>{{ $member->phone }}</span>
                </div>
                @endif
            </div>

            <!-- Footer Actions -->
            <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                <span class="text-xs text-gray-500">
                    Joined {{ $member->created_at->format('M d, Y') }}
                </span>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.team-members.show', $member) }}" class="p-1.5 text-gray-400 hover:text-brand-primary hover:bg-brand-primary/5 rounded-lg transition-colors" title="View Profile">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </a>
                    <a href="{{ route('admin.team-members.edit', $member) }}" class="p-1.5 text-gray-400 hover:text-brand-primary hover:bg-brand-primary/5 rounded-lg transition-colors" title="Edit">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>
                    <form action="{{ route('admin.team-members.destroy', $member) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this team member?');" class="inline-flex">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
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
@endsection
