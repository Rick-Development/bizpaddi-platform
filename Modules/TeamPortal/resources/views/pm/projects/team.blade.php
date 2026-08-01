@extends('teamportal::layouts.app')

@section('title', 'Manage Team - ' . $project->name)

@section('content')
<div class="mb-8">
    <a href="{{ route('team.pm.projects.show', $project) }}" class="text-sm font-semibold text-brand-primary flex items-center gap-1 mb-2 hover:underline">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        Back to Project
    </a>
    <h1 class="text-3xl font-bold text-gray-900">Manage Team Members</h1>
    <p class="mt-2 text-sm text-gray-600">Assign or remove team members from {{ $project->name }}</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900">Current Team</h2>
                <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full font-bold">{{ $project->teamMembers->count() }} Members</span>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($project->teamMembers as $member)
                <div class="p-6 flex items-center justify-between hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 rounded-full bg-brand-primary/10 flex items-center justify-center text-brand-primary font-bold">
                            {{ substr($member->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">{{ $member->name }}</p>
                            <p class="text-xs text-gray-500">{{ $member->email }} • Joined {{ $member->pivot->joined_at ? \Carbon\Carbon::parse($member->pivot->joined_at)->format('M d, Y') : 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-[10px] font-bold uppercase tracking-widest">
                            {{ $member->pivot->role ?? 'Member' }}
                        </span>
                        <form action="{{ route('team.pm.projects.team.remove', [$project, $member]) }}" method="POST" onsubmit="return confirm('Remove this member from the project?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-600 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center">
                    <p class="text-gray-500 italic">No team members assigned yet</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Add Team Member</h2>
            <form action="{{ route('team.pm.projects.team.add', $project) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Select User</label>
                    <select name="user_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all outline-none text-sm">
                        <option value="">Select a user...</option>
                        @foreach($availableUsers as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Assignment Role</label>
                    <select name="role" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all outline-none text-sm">
                        <option value="member">Team Member</option>
                        <option value="lead">Project Lead</option>
                    </select>
                </div>
                <button type="submit" class="w-full py-3 bg-brand-primary text-white rounded-xl font-bold shadow-lg shadow-brand-primary/25 hover:shadow-brand-primary/40 hover:-translate-y-0.5 transition-all">
                    Add to Team
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
