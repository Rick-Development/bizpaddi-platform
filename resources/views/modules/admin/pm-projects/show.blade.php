@extends('admin::layouts.app')

@section('title', $project->name)

@section('content')
<div class="mb-8">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">{{ $project->name }}</h1>
            <div class="mt-2 flex items-center text-sm text-gray-400">
                <span class="mr-4 capitalize px-2.5 py-0.5 rounded-full text-xs font-medium 
                    @if($project->status == 'active') bg-green-100 text-green-800
                    @elseif($project->status == 'completed') bg-blue-100 text-blue-800
                    @else bg-white/[0.06] text-white @endif">
                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                </span>
                <span>{{ $project->client ? $project->client->name : 'No Client' }}</span>
                @if($project->due_date)
                    <span class="mx-2">&bull;</span>
                    <span>Due {{ $project->due_date->format('M d, Y') }}</span>
                @endif
            </div>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <a href="{{ route('admin.tasks.index', ['project_id' => $project->id]) }}" class="inline-flex items-center px-4 py-2 border border-white/10 rounded-lg text-sm font-medium text-gray-300 bg-[#10231C]/60 hover:bg-white/[0.03]">
                View Kanban Board
            </a>
            <a href="{{ route('admin.pm-projects.edit', $project) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-gray-600 hover:bg-gray-700">
                Edit
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Progress / Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-[#10231C]/60 p-6 rounded-xl shadow-sm border border-white/10">
                <p class="text-sm font-medium text-gray-400">Total Tasks</p>
                <p class="mt-2 text-3xl font-bold text-white">{{ $project->tasks->count() }}</p>
            </div>
            <div class="bg-[#10231C]/60 p-6 rounded-xl shadow-sm border border-white/10">
                <p class="text-sm font-medium text-gray-400">Completed</p>
                <p class="mt-2 text-3xl font-bold text-white">{{ $project->tasks->where('status', 'done')->count() }}</p>
            </div>
            <div class="bg-[#10231C]/60 p-6 rounded-xl shadow-sm border border-white/10">
                <p class="text-sm font-medium text-gray-400">Pending</p>
                <p class="mt-2 text-3xl font-bold text-white">{{ $project->tasks->where('status', '!=', 'done')->count() }}</p>
            </div>
            <div class="bg-[#10231C]/60 p-6 rounded-xl shadow-sm border border-white/10">
                <p class="text-sm font-medium text-gray-400">Overall Progress</p>
                @php
                    $total = $project->tasks->count();
                    $done = $project->tasks->where('status', 'done')->count();
                    $percent = $total > 0 ? round(($done / $total) * 100) : 0;
                @endphp
                <p class="mt-2 text-3xl font-bold text-white">{{ $percent }}%</p>
                <div class="w-full bg-white/[0.06] rounded-full h-1.5 mt-3">
                    <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $percent }}%"></div>
                </div>
            </div>
        </div>

        <!-- Health & Distribution -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-[#10231C]/60 p-6 rounded-xl shadow-sm border border-white/10">
                <p class="text-sm font-medium text-gray-400 mb-3">Priority Distribution</p>
                <div class="flex h-4 rounded-full overflow-hidden bg-white/[0.06]">
                    @php
                        $counts = $project->tasks->groupBy('priority')->map->count();
                        $totalDist = $project->tasks->count() ?: 1;
                        $colors = ['urgent' => 'bg-red-500', 'high' => 'bg-orange-500', 'medium' => 'bg-blue-500', 'low' => 'bg-gray-400'];
                    @endphp
                    @foreach(['urgent', 'high', 'medium', 'low'] as $prio)
                        @if(isset($counts[$prio]))
                            <div class="{{ $colors[$prio] }}" style="width: {{ ($counts[$prio] / $totalDist) * 100 }}%" title="{{ ucfirst($prio) }}: {{ $counts[$prio] }}"></div>
                        @endif
                    @endforeach
                </div>
                <div class="flex flex-wrap gap-4 mt-4">
                    @foreach(['urgent', 'high', 'medium', 'low'] as $prio)
                        <div class="flex items-center gap-2">
                            <div class="w-2.5 h-2.5 rounded-full {{ $colors[$prio] }}"></div>
                            <span class="text-xs text-gray-400 font-medium">{{ ucfirst($prio) }} ({{ $counts[$prio] ?? 0 }})</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-[#10231C]/60 p-6 rounded-xl shadow-sm border border-white/10">
                <p class="text-sm font-medium text-gray-400 mb-3">Status Distribution</p>
                <div class="flex h-4 rounded-full overflow-hidden bg-white/[0.06]">
                    @php
                        $statusCounts = $project->tasks->groupBy('status')->map->count();
                        $totalStatus = $project->tasks->count() ?: 1;
                        $statusColors = ['todo' => 'bg-gray-400', 'in_progress' => 'bg-blue-500', 'review' => 'bg-orange-500', 'done' => 'bg-green-500'];
                    @endphp
                    @foreach(['todo', 'in_progress', 'review', 'done'] as $stat)
                        @if(isset($statusCounts[$stat]))
                            <div class="{{ $statusColors[$stat] }}" style="width: {{ ($statusCounts[$stat] / $totalStatus) * 100 }}%" title="{{ ucfirst(str_replace('_', ' ', $stat)) }}: {{ $statusCounts[$stat] }}"></div>
                        @endif
                    @endforeach
                </div>
                <div class="flex flex-wrap gap-4 mt-4">
                    @foreach(['todo', 'in_progress', 'review', 'done'] as $stat)
                        <div class="flex items-center gap-2">
                            <div class="w-2.5 h-2.5 rounded-full {{ $statusColors[$stat] }}"></div>
                            <span class="text-xs text-gray-400 font-medium">{{ ucfirst(str_replace('_', ' ', $stat)) }} ({{ $statusCounts[$stat] ?? 0 }})</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recent Tasks -->
        <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl">
            <div class="px-6 py-4 border-b border-white/10 bg-white/[0.03] flex justify-between items-center">
                <h3 class="text-lg font-medium text-white">Recent Tasks</h3>
            </div>
            <ul class="divide-y divide-white/10">
                @forelse($project->tasks->sortByDesc('created_at')->take(5) as $task)
                <li class="px-6 py-4 flex items-center justify-between">
                    <div>
                        <a href="{{ route('admin.tasks.show', $task) }}" class="text-sm font-medium text-white hover:text-gray-400">
                            {{ $task->title }}
                        </a>
                        <p class="text-xs text-gray-400">Assigned to: {{ $task->assignee ? $task->assignee->name : 'Unassigned' }}</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white/[0.06] text-white">
                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                    </span>
                </li>
                @empty
                <li class="px-6 py-4 text-center text-sm text-gray-400">No tasks created yet.</li>
                @endforelse
            </ul>
        </div>
        <!-- Collaboration Section -->
        <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl overflow-hidden mt-6">
            <div class="border-b border-white/10 bg-white/[0.03] px-6 py-4">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button type="button" onclick="switchCollaborationTab('milestones')" id="tab-milestones" class="border-transparent text-gray-400 hover:text-gray-300 hover:border-white/10 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Milestones ({{ $project->milestones->count() }})
                    </button>
                    <button type="button" onclick="switchCollaborationTab('comments')" id="tab-comments" class="border-gray-600 text-white whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Comments ({{ $project->comments->count() }})
                    </button>
                    <button type="button" onclick="switchCollaborationTab('files')" id="tab-files" class="border-transparent text-gray-400 hover:text-gray-300 hover:border-white/10 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Task Files ({{ $project->tasks->sum(fn($t) => $t->getMedia('attachments')->count()) }})
                    </button>
                    <button type="button" onclick="switchCollaborationTab('vault')" id="tab-vault" class="border-transparent text-gray-400 hover:text-gray-300 hover:border-white/10 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Project Vault ({{ $project->getMedia('vault')->count() }})
                    </button>
                </nav>
            </div>

            <div class="p-6">
                <!-- Milestones Tab -->
                <div id="content-milestones" class="hidden">
                    <div class="flex justify-between items-center mb-6">
                        <h4 class="text-sm font-bold text-white">Project Milestones</h4>
                        <button type="button" onclick="document.getElementById('new-milestone-form').classList.toggle('hidden')" class="text-xs bg-gray-900 text-white px-3 py-1.5 rounded-lg hover:bg-black transition-colors">
                            + Add Milestone
                        </button>
                    </div>

                    <form id="new-milestone-form" action="{{ route('admin.pm-milestones.store') }}" method="POST" class="hidden mb-8 p-4 bg-white/[0.03] rounded-xl border border-white/10">
                        @csrf
                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Title</label>
                                <input type="text" name="title" required class="block w-full rounded-lg border-white/10 text-sm focus:ring-gray-500 bg-[#0a1612] text-white border-white/10">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Description</label>
                                <textarea name="description" rows="2" class="block w-full rounded-lg border-white/10 text-sm focus:ring-gray-500 bg-[#0a1612] text-white border-white/10"></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Due Date</label>
                                <input type="date" name="due_date" class="block w-full rounded-lg border-white/10 text-sm focus:ring-gray-500 bg-[#0a1612] text-white border-white/10">
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="w-full bg-gray-900 text-white text-sm font-bold py-2 rounded-lg hover:bg-black transition-colors">Save Milestone</button>
                            </div>
                        </div>
                    </form>

                    <div class="space-y-4">
                        @forelse($project->milestones as $milestone)
                        <div class="group relative bg-[#10231C]/60 border border-white/10 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h5 class="font-bold text-white truncate">{{ $milestone->title }}</h5>
                                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-bold border 
                                            {{ $milestone->status === 'completed' ? 'bg-green-50 text-green-700 border-green-100' : 'bg-yellow-50 text-yellow-700 border-yellow-100' }}">
                                            {{ ucfirst($milestone->status) }}
                                        </span>
                                    </div>
                                    @if($milestone->description)
                                    <p class="text-xs text-gray-400 line-clamp-2 leading-relaxed mb-4">{{ strip_tags($milestone->description) }}</p>
                                    @endif
                                    
                                    <div>
                                        <div class="flex justify-between text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">
                                            <span>Progress</span>
                                            <span>{{ $milestone->progress }}%</span>
                                        </div>
                                        <div class="w-full bg-white/[0.03] rounded-full h-1.5">
                                            <div class="bg-brand-primary h-1.5 rounded-full shadow-sm" style="width: {{ $milestone->progress }}%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="ml-6 flex flex-col items-end gap-3">
                                    <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('admin.pm-milestones.edit', $milestone) }}" class="p-1.5 text-gray-300 hover:text-brand-primary transition-colors">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.pm-milestones.destroy', $milestone) }}" method="POST" onsubmit="return confirm('Delete milestone?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-1.5 text-gray-300 hover:text-red-500 transition-colors">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                    <form action="{{ route('admin.pm-milestones.update', $milestone) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                                        <input type="hidden" name="title" value="{{ $milestone->title }}">
                                        <input type="hidden" name="status" value="{{ $milestone->status === 'completed' ? 'pending' : 'completed' }}">
                                        <button type="submit" class="text-[9px] font-bold px-2 py-1 rounded bg-white/[0.03] text-gray-400 hover:bg-brand-primary hover:text-white transition-all uppercase border border-white/10">
                                            {{ $milestone->status === 'completed' ? 'Pending' : 'Done' }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 bg-white/[0.03] rounded-xl border border-dashed border-white/10">
                            <p class="text-sm text-gray-400">No milestones defined for this project.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Comments Tab -->
                <div id="content-comments">
                    <form action="{{ route('pm.comments.store') }}" method="POST" enctype="multipart/form-data" class="mb-8">
                        @csrf
                        <input type="hidden" name="commentable_id" value="{{ $project->id }}">
                        <input type="hidden" name="commentable_type" value="project">
                        <input type="hidden" name="guard" value="admin">
                        <div>
                            <textarea name="content" rows="3" class="block w-full rounded-lg border-white/10 shadow-sm focus:border-gray-500 focus:ring-gray-500 sm:text-sm bg-[#0a1612] text-white border-white/10" placeholder="Add a comment..."></textarea>
                        </div>
                        <div class="mt-3 flex items-center justify-between">
                            <div class="flex items-center">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="file" name="attachment" class="hidden bg-[#0a1612] text-white border-white/10">
                                    <span class="text-xs text-gray-400 hover:text-gray-300">Attach file</span>
                                </label>
                                <label class="ml-4 inline-flex items-center">
                                    <input type="checkbox" name="is_internal" value="1" class="rounded border-white/10 text-gray-400 shadow-sm focus:ring-gray-500">
                                    <span class="ml-2 text-xs text-gray-400">Internal only</span>
                                </label>
                            </div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-gray-600 hover:bg-gray-700">
                                Post Comment
                            </button>
                        </div>
                    </form>

                    <div class="space-y-6">
                        @forelse($project->comments as $comment)
                        <div class="flex space-x-3">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full {{ $comment->user_type === 'App\Models\Admin' ? 'bg-red-100 text-red-600' : 'bg-gray-200 text-gray-400' }} flex items-center justify-center text-xs font-bold">
                                    {{ substr($comment->user->name ?? 'U', 0, 1) }}
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm">
                                    <span class="font-medium text-white">{{ $comment->user->name ?? 'User' }}</span>
                                    @if($comment->user_type === 'App\Models\Admin')
                                    <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700 uppercase">Admin</span>
                                    @endif
                                    @if($comment->is_internal)
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Internal</span>
                                    @endif
                                </div>
                                <div class="mt-1 text-sm text-gray-300">
                                    <p>{{ $comment->content }}</p>
                                </div>
                                @if($comment->hasMedia('comment_attachments'))
                                <div class="mt-2 text-xs">
                                    @foreach($comment->getMedia('comment_attachments') as $media)
                                    <a href="{{ $media->getUrl() }}" target="_blank" class="inline-flex items-center text-gray-400 hover:text-gray-300 mr-3">
                                        📎 {{ $media->file_name }}
                                    </a>
                                    @endforeach
                                </div>
                                @endif
                                <div class="mt-2 flex items-center space-x-2 text-xs text-gray-400">
                                    <span>{{ $comment->created_at->diffForHumans() }}</span>
                                    <span>&middot;</span>
                                    <form action="{{ route('pm.comments.destroy', $comment) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Delete comment?')">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-center text-sm text-gray-400 py-4">No comments yet.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Task Files Tab -->
                <div id="content-files" class="hidden">
                    <div class="space-y-4">
                        @foreach($project->tasks as $task)
                            @if($task->getMedia('attachments')->count() > 0)
                            <div>
                                <h4 class="text-xs font-bold text-gray-400 uppercase mb-2">{{ $task->title }}</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach($task->getMedia('attachments') as $media)
                                    <div class="flex items-center justify-between p-3 border border-white/10 rounded-lg bg-white/[0.03] bg-[#10231C]/60 border border-white/10">
                                        <div class="flex items-center overflow-hidden">
                                            <div class="h-8 w-8 flex-shrink-0 bg-white/[0.06] rounded flex items-center justify-center mr-3">
                                                <span class="text-[10px] font-bold text-gray-400 uppercase">{{ $media->extension }}</span>
                                            </div>
                                            <div class="truncate">
                                                <p class="text-xs font-medium text-white truncate">{{ $media->file_name }}</p>
                                                <p class="text-[10px] text-gray-400">{{ $media->human_readable_size }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center ml-2">
                                            @if($media->getCustomProperty('is_internal'))
                                            <span class="mr-2 px-1 py-0.5 rounded text-[8px] bg-yellow-100 text-yellow-800 font-bold uppercase">Internal</span>
                                            @endif
                                            <a href="{{ $media->getUrl() }}" download class="text-gray-400 hover:text-gray-400">⬇️</a>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Vault Tab -->
                <div id="content-vault" class="hidden">
                    <form action="{{ route('pm.attachments.store') }}" method="POST" enctype="multipart/form-data" class="mb-8 p-6 border-2 border-dashed border-white/10 rounded-xl text-center hover:border-gray-400 transition-colors">
                        @csrf
                        <input type="hidden" name="model_id" value="{{ $project->id }}">
                        <input type="hidden" name="model_type" value="project">
                        <input type="hidden" name="collection" value="vault">
                        <div class="space-y-2">
                            <input type="file" name="file" id="vault-upload" class="hidden bg-[#0a1612] text-white border-white/10" onchange="this.form.submit()">
                            <label for="vault-upload" class="cursor-pointer block">
                                <span class="text-sm font-medium text-gray-400">Add Master Document to Vault</span>
                                <p class="text-xs text-gray-400">These files are shared with the client</p>
                            </label>
                        </div>
                    </form>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse($project->getMedia('vault') as $media)
                        <div class="flex items-center justify-between p-4 border border-white/10 rounded-xl bg-[#10231C]/60 border border-white/10">
                            <div class="flex items-center overflow-hidden">
                                <div class="h-10 w-10 flex-shrink-0 bg-brand-primary/10 rounded-lg flex items-center justify-center mr-3">
                                    <span class="text-xs font-bold text-brand-primary uppercase">{{ $media->extension }}</span>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-sm font-medium text-white truncate">{{ $media->file_name }}</p>
                                    <p class="text-xs text-gray-400">{{ $media->human_readable_size }} &middot; {{ $media->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center ml-4 space-x-3">
                                <a href="{{ $media->getUrl() }}" download class="text-gray-400 hover:text-gray-400 transition-colors">⬇️</a>
                                <form action="{{ route('pm.attachments.destroy', $media->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-600 transition-colors" onclick="return confirm('Remove document?')">🗑️</button>
                                </form>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-2 text-center py-12 bg-white/[0.03] rounded-xl border border-dashed border-white/10">
                             <p class="text-sm text-gray-400 font-medium">The Project Vault is empty.</p>
                             <p class="text-xs text-gray-400 mt-1">Upload brand assets, contracts, or final deliverables here.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl p-6">
            <h3 class="text-lg font-medium text-white mb-4">Project Details</h3>
            <div class="space-y-4">
                <div>
                    <span class="block text-sm font-medium text-gray-400">Budget</span>
                    <span class="block text-sm text-white">${{ number_format($project->budget, 2) }}</span>
                </div>
                <div>
                    <span class="block text-sm font-medium text-gray-400">Start Date</span>
                    <span class="block text-sm text-white">{{ $project->start_date ? $project->start_date->format('M d, Y') : '-' }}</span>
                </div>
                <div>
                    <span class="block text-sm font-medium text-gray-400">Description</span>
                    <div class="block text-sm text-white mt-1 prose prose-sm max-w-none">
                        {!! $project->description !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function switchCollaborationTab(tab) {
    const commentsTab = document.getElementById('tab-comments');
    const filesTab = document.getElementById('tab-files');
    const vaultTab = document.getElementById('tab-vault');
    const milestonesTab = document.getElementById('tab-milestones');
    const commentsContent = document.getElementById('content-comments');
    const filesContent = document.getElementById('content-files');
    const vaultContent = document.getElementById('content-vault');
    const milestonesContent = document.getElementById('content-milestones');

    // Reset tabs
    [commentsTab, filesTab, vaultTab, milestonesTab].forEach(t => {
        if (t) {
            t.classList.remove('border-gray-600', 'text-white');
            t.classList.add('border-transparent', 'text-gray-400');
        }
    });
    [commentsContent, filesContent, vaultContent, milestonesContent].forEach(c => {
        if (c) c.classList.add('hidden');
    });

    // Set active
    const activeTab = document.getElementById('tab-' + tab);
    const activeContent = document.getElementById('content-' + tab);
    if (activeTab && activeContent) {
        activeTab.classList.add('border-gray-600', 'text-white');
        activeTab.classList.remove('border-transparent', 'text-gray-400');
        activeContent.classList.remove('hidden');
    }
}
</script>
@endpush
