@extends('admin::layouts.app')

@section('title', 'Project Milestones')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Header Section -->
    <div class="sm:flex sm:items-center sm:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight sm:truncate">Milestones</h1>
            <p class="mt-2 text-sm text-gray-500 font-medium">
                Track and manage major project milestones and their deliverables.
            </p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-4">
            <button onclick="document.getElementById('new-milestone-modal').classList.remove('hidden')" 
                    class="inline-flex items-center px-5 py-2.5 border border-transparent shadow-sm text-sm font-bold rounded-xl text-white bg-brand-primary hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary transition-all hover:scale-[1.02] active:scale-[0.98]">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                New Milestone
            </button>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-8">
        <form method="GET" action="{{ route('admin.pm-milestones.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="relative">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Project Filter</label>
                <select name="project_id" class="block w-full pl-3 pr-10 py-3 text-sm border-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary rounded-xl transition-all">
                    <option value="">All Projects</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-gray-900 text-white text-sm font-bold rounded-xl hover:bg-black transition-all shadow-sm">
                    Apply Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Content Section -->
    @if($milestones->isEmpty())
        <div class="bg-white rounded-3xl shadow-sm border border-dashed border-gray-200 p-12 text-center">
            <div class="mx-auto h-24 w-24 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                <svg class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900">No Milestones Found</h3>
            <p class="mt-2 text-gray-500 max-w-sm mx-auto">Start by creating your first project milestone to track progress effectively.</p>
            <button onclick="document.getElementById('new-milestone-modal').classList.remove('hidden')" class="mt-6 text-brand-primary font-bold hover:underline">
                Create One Now &rarr;
            </button>
        </div>
    @else
        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-hidden bg-white shadow-sm ring-1 ring-gray-900/5 rounded-2xl">
            <table class="min-w-full divide-y divide-gray-100">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th scope="col" class="py-4 pl-6 pr-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Milestone & Project</th>
                        <th scope="col" class="px-3 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Progress</th>
                        <th scope="col" class="px-3 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-3 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Due Date</th>
                        <th scope="col" class="relative py-4 pl-3 pr-6 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 bg-white">
                    @foreach($milestones as $milestone)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-5 pl-6 pr-3">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-gray-900">{{ $milestone->title }}</span>
                                <a href="{{ route('admin.pm-projects.show', $milestone->project_id) }}" class="text-xs text-brand-primary font-medium hover:underline mt-0.5">
                                    {{ $milestone->project->name }}
                                </a>
                            </div>
                        </td>
                        <td class="px-3 py-5">
                            <div class="flex items-center gap-3 w-40">
                                <div class="flex-1 bg-gray-100 rounded-full h-2">
                                    <div class="bg-brand-primary h-2 rounded-full shadow-sm" style="width: {{ $milestone->progress }}%"></div>
                                </div>
                                <span class="text-xs font-bold text-gray-700 min-w-[30px]">{{ $milestone->progress }}%</span>
                            </div>
                        </td>
                        <td class="px-3 py-5">
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-bold border 
                                {{ $milestone->status === 'completed' 
                                    ? 'bg-green-50 text-green-700 border-green-100' 
                                    : 'bg-yellow-50 text-yellow-700 border-yellow-100' }}">
                                <span class="h-1.5 w-1.5 rounded-full mr-2 {{ $milestone->status === 'completed' ? 'bg-green-500' : 'bg-yellow-500' }}"></span>
                                {{ ucfirst($milestone->status) }}
                            </span>
                        </td>
                        <td class="px-3 py-5">
                            <span class="text-xs font-semibold text-gray-600">
                                @if($milestone->due_date)
                                    <div class="flex items-center gap-1.5">
                                        <svg class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $milestone->due_date->format('M d, Y') }}
                                    </div>
                                @else
                                    Not Set
                                @endif
                            </span>
                        </td>
                        <td class="py-5 pl-3 pr-6 text-right text-sm font-bold">
                            <div class="flex justify-end items-center gap-4">
                                <a href="{{ route('admin.pm-milestones.edit', $milestone) }}" class="text-gray-400 hover:text-brand-primary transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form action="{{ route('admin.pm-milestones.update', $milestone) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="project_id" value="{{ $milestone->project_id }}">
                                    <input type="hidden" name="title" value="{{ $milestone->title }}">
                                    <input type="hidden" name="status" value="{{ $milestone->status === 'completed' ? 'pending' : 'completed' }}">
                                    <button type="submit" class="text-[10px] px-2.5 py-1 rounded bg-gray-50 border border-brand-primary/10 text-brand-primary uppercase tracking-tight hover:bg-brand-primary hover:text-white transition-all">
                                        {{ $milestone->status === 'completed' ? 'Mark Pending' : 'Mark Done' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.pm-milestones.destroy', $milestone) }}" method="POST" onsubmit="return confirm('Delete this milestone?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="grid grid-cols-1 gap-4 md:hidden">
            @foreach($milestones as $milestone)
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1 min-w-0">
                        <h4 class="text-base font-bold text-gray-900 truncate">{{ $milestone->title }}</h4>
                        <p class="text-xs text-brand-primary font-bold mt-1">{{ $milestone->project->name }}</p>
                    </div>
                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-bold border 
                        {{ $milestone->status === 'completed' ? 'bg-green-50 text-green-700 border-green-100' : 'bg-yellow-50 text-yellow-700 border-yellow-100' }}">
                        {{ ucfirst($milestone->status) }}
                    </span>
                </div>

                <div class="mb-5">
                    <div class="flex justify-between text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">
                        <span>Progress</span>
                        <span>{{ $milestone->progress }}%</span>
                    </div>
                    <div class="w-full bg-gray-50 rounded-full h-2">
                        <div class="bg-brand-primary h-2 rounded-full shadow-sm" style="width: {{ $milestone->progress }}%"></div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                    <div class="text-[10px] font-bold text-gray-400 uppercase flex items-center gap-1.5">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ $milestone->due_date ? $milestone->due_date->format('M d, Y') : 'No Date' }}
                    </div>
                    <div class="flex items-center gap-3">
                         <a href="{{ route('admin.pm-milestones.edit', $milestone) }}" class="p-2 text-gray-400 hover:text-brand-primary">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                        <form action="{{ route('admin.pm-milestones.destroy', $milestone) }}" method="POST" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-gray-400 hover:text-red-500">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $milestones->links() }}
        </div>
    @endif
</div>

<!-- New Milestone Modal -->
<div id="new-milestone-modal" class="hidden fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="this.parentElement.parentElement.classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-white/20">
            <form action="{{ route('admin.pm-milestones.store') }}" method="POST">
                @csrf
                <div class="bg-white px-6 pt-6 pb-4 sm:p-8 sm:pb-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Create New Milestone</h3>
                        <button type="button" onclick="document.getElementById('new-milestone-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l18 18" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="space-y-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Assign to Project</label>
                            <select name="project_id" required class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-brand-primary/10 focus:border-brand-primary transition-all text-sm font-medium">
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Milestone Title</label>
                            <input type="text" name="title" required placeholder="e.g., Phase 1 Completion" class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-brand-primary/10 focus:border-brand-primary transition-all text-sm font-medium">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Description</label>
                            <textarea name="description" rows="3" placeholder="Briefly describe the key deliverables..." class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-brand-primary/10 focus:border-brand-primary transition-all text-sm font-medium"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Expected Due Date</label>
                            <input type="date" name="due_date" class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-brand-primary/10 focus:border-brand-primary transition-all text-sm font-medium">
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50/50 px-6 py-4 sm:px-8 sm:flex sm:flex-row-reverse gap-4 border-t border-gray-100">
                    <button type="submit" class="w-full inline-flex justify-center rounded-2xl px-6 py-3 bg-brand-primary text-sm font-bold text-white shadow-lg shadow-brand-primary/20 hover:bg-brand-primary/90 hover:-translate-y-0.5 transition-all sm:w-auto">
                        Create Milestone
                    </button>
                    <button type="button" onclick="document.getElementById('new-milestone-modal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-2xl px-6 py-3 bg-white border border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all sm:mt-0 sm:w-auto">
                        Discard
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
