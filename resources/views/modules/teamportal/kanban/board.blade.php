<div class="flex gap-4 overflow-x-auto pb-8 min-h-[calc(100vh-250px)]">
    @foreach($columns as $status => $label)
    <div class="flex-shrink-0 w-80">
        <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-2xl p-4 border border-white/10 flex flex-col h-full shadow-xl">
            <div class="flex items-center justify-between mb-4 px-1">
                <h3 class="font-bold text-white flex items-center gap-2">
                    {{ $label }}
                    <span class="bg-white/10 text-emerald-400 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-white/10">
                        {{ isset($groupedTasks[$status]) ? $groupedTasks[$status]->count() : 0 }}
                    </span>
                </h3>
            </div>
            
            <div id="column-{{ $status }}" data-status="{{ $status }}" class="kanban-column space-y-3 flex-1 min-h-[200px]">
                @if(isset($groupedTasks[$status]))
                    @foreach($groupedTasks[$status] as $task)
                    <div data-id="{{ $task->id }}" class="kanban-task bg-[#08110e]/90 p-4 rounded-xl border border-white/10 shadow-lg cursor-grab active:cursor-grabbing hover:border-emerald-500/40 hover:bg-[#08110e] transition-all group">
                        <div class="flex items-start justify-between mb-2">
                            <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider
                                @if($task->priority == 'urgent') bg-red-500/20 text-red-300 border border-red-500/30
                                @elseif($task->priority == 'high') bg-amber-500/20 text-amber-300 border border-amber-500/30
                                @elseif($task->priority == 'medium') bg-blue-500/20 text-blue-300 border border-blue-500/30
                                @else bg-gray-500/20 text-gray-300 border border-gray-500/30 @endif">
                                {{ $task->priority }}
                            </span>
                            <div class="flex items-center gap-1.5">
                                @if($task->milestone)
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-purple-500/20 text-purple-300 border border-purple-500/30 truncate max-w-[100px]" title="Milestone: {{ $task->milestone->title }}">
                                    🏔️ {{ Str::limit($task->milestone->title, 10) }}
                                </span>
                                @endif
                                @if((auth()->user()->isAdmin() || auth()->user()->isProjectManager()) && !in_array($task->status, ['review', 'done', 'completed']))
                                <form action="{{ route('team.tasks.destroy', $task) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this task?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-500 hover:text-red-400 hover:bg-red-500/10 p-1 rounded transition-colors" title="Delete Task">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                        <h4 class="text-sm font-bold text-white mb-1 group-hover:text-emerald-400 transition-colors line-clamp-2">
                            <a href="{{ route('team.tasks.show', $task) }}">{{ $task->title }}</a>
                        </h4>
                        <p class="text-[11px] text-gray-400 mb-2 truncate">{{ $task->project ? $task->project->name : 'N/A' }}</p>
                        
                        <div class="flex items-center justify-between pt-3 border-t border-white/5">
                            <div class="flex items-center gap-2 min-w-0">
                                @if($task->assignee)
                                <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 max-w-[130px]" title="Assigned to: {{ $task->assignee->name }}">
                                    <svg class="w-3 h-3 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="text-[11px] font-medium truncate">{{ $task->assignee->name }}</span>
                                </div>
                                @else
                                <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-white/5 border border-dashed border-white/15 text-gray-400 text-[11px]" title="Unassigned">
                                    <svg class="w-3 h-3 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="italic">Unassigned</span>
                                </div>
                                @endif
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <div class="text-[10px] flex items-center gap-1.5 {{ $task->due_date && $task->due_date->isPast() && !in_array($task->status, ['done','completed']) ? 'text-red-400 font-semibold' : 'text-gray-400' }} font-medium font-mono">
                                    @if($task->due_date)
                                        @if(!in_array($task->status, ['done','completed']))
                                            @if($task->due_date->isPast())
                                                <span class="relative flex h-2 w-2 shrink-0">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500 animate-intense-blink"></span>
                                                </span>
                                            @elseif($task->due_date->diffInHours(now(), false) >= -48)
                                                <span class="relative flex h-2 w-2 shrink-0">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500 animate-intense-blink"></span>
                                                </span>
                                            @endif
                                        @endif
                                        {{ $task->due_date->format('M d') }} at {{ $task->due_date->format('g:i A') }}
                                    @else
                                        No due date
                                    @endif
                                </div>
                                @if($task->requires_client_approval)
                                <div class="flex items-center gap-1">
                                    <span class="text-[8px] font-bold text-blue-400 uppercase tracking-tighter bg-blue-500/20 border border-blue-500/30 px-1 py-0.5 rounded">Client Review</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        @if($task->status_changed_at)
                        <div class="mt-2.5 pt-2.5 border-t border-white/5 flex items-center text-[10px] text-gray-500 font-mono">
                            <span class="flex items-center gap-1">
                                🕒 {{ $task->status === 'todo' ? 'Assigned' : 'Moved' }}: {{ $task->status_changed_at->format('M d, Y \a\t H:i:s') }}
                            </span>
                        </div>
                        @endif
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
