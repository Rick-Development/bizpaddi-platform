@extends('teamportal::layouts.app')

@section('title', $task->title)

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    <div class="flex items-center space-x-2 text-xs sm:text-sm text-gray-400">
        @if(auth()->user()->isProjectManager())
            <a href="{{ route('team.kanban.index') }}" class="hover:text-emerald-400 transition-colors line-clamp-1">Task Board</a>
        @else
            <a href="{{ route('team.tasks.index') }}" class="hover:text-emerald-400 transition-colors line-clamp-1">My Tasks</a>
        @endif
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-white font-medium line-clamp-1">{{ $task->title }}</span>
    </div>

    <div class="bg-[#10231C]/60 backdrop-blur-sm border border-white/10 rounded-3xl overflow-hidden p-6 sm:p-8 shadow-2xl">
        <div class="sm:flex sm:items-start sm:justify-between mb-8 gap-4">
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2 mb-3 text-xs font-bold">
                    <span class="px-2.5 py-1 rounded-lg uppercase tracking-wider
                        {{ $task->priority === 'urgent' ? 'bg-red-500/10 text-red-400 border border-red-500/30' : 
                           ($task->priority === 'high' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/30' : 
                           'bg-white/5 text-gray-300 border border-white/10') }}">
                        {{ $task->priority }}
                    </span>
                    <span class="px-2.5 py-1 rounded-lg bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 uppercase tracking-wider">
                        @php
                            $statusLabels = [
                                'todo' => 'To Do',
                                'in_progress' => 'In Progress',
                                'review' => 'Done',
                                'done' => 'Completed',
                            ];
                        @endphp
                        {{ $statusLabels[$task->status] ?? $task->status }}
                    </span>
                    @if($task->project)
                    <span class="text-gray-400 font-normal">&bull; {{ $task->project->name }}</span>
                    @endif
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">{{ $task->title }}</h1>
                @if($task->due_date)
                <div class="flex items-center gap-1.5 text-xs text-gray-400 mt-2">
                    <svg class="h-4 w-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="{{ $task->due_date->isPast() && !in_array($task->status, ['done','completed']) ? 'text-red-400 font-bold' : '' }}">
                        Due: {{ $task->due_date->format('M d, Y') }} at {{ $task->due_date->format('g:i A') }}
                    </span>
                </div>
                @endif
            </div>
            
            <div class="mt-4 sm:mt-0 shrink-0">
                <form action="{{ route('team.tasks.update-status', $task) }}" method="POST">
                    @csrf @method('PATCH')
                    <select name="status" onchange="this.form.submit()" class="rounded-xl bg-[#08110e] border border-white/10 text-xs sm:text-sm text-white px-3 py-2 focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 cursor-pointer shadow-inner">
                        <option value="todo" {{ $task->status == 'todo' ? 'selected' : '' }}>To Do</option>
                        <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="review" {{ $task->status == 'review' ? 'selected' : '' }}>Done</option>
                        @if(auth()->user()->isProjectManager() || $task->status == 'done')
                            <option value="done" {{ $task->status == 'done' ? 'selected' : '' }} {{ !auth()->user()->isProjectManager() ? 'disabled' : '' }}>Completed</option>
                        @endif
                    </select>
                </form>
            </div>
        </div>

        <div class="mb-8 p-5 bg-[#08110e]/60 rounded-2xl border border-white/10 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Time Spent</p>
                <h4 class="text-2xl font-black text-white">{{ $task->formatDuration($task->total_time_spent) }}</h4>
            </div>
            <div>
                @if($task->active_timer)
                    <form action="{{ route('pm.time-logs.stop', $task->active_timer) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-500 text-white font-bold rounded-xl transition-all shadow-lg text-xs sm:text-sm active:scale-95">
                            <span class="relative flex h-2.5 w-2.5">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-white"></span>
                            </span>
                            Stop Timer ({{ $task->active_timer->start_time->diffForHumans(null, true) }})
                        </button>
                    </form>
                @else
                    <form action="{{ route('pm.time-logs.start') }}" method="POST">
                        @csrf
                        <input type="hidden" name="task_id" value="{{ $task->id }}">
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl transition-all shadow-lg text-xs sm:text-sm active:scale-95">
                            ▶️ Start Work Timer
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="prose prose-invert prose-sm max-w-none text-gray-300 mb-8 border-b border-white/10 pb-8">
            {!! $task->description !!}
        </div>

        <!-- Collaboration -->
        <div class="mt-8 space-y-8">
            <h3 class="text-base font-bold text-white uppercase tracking-wider">Collaboration & Attachments</h3>
            
            <form action="{{ route('pm.attachments.store') }}" method="POST" enctype="multipart/form-data" class="bg-[#08110e]/60 p-5 rounded-2xl border border-white/10 flex flex-col items-center space-y-4">
                @csrf
                <input type="hidden" name="model_id" value="{{ $task->id }}">
                <input type="hidden" name="model_type" value="task">
                
                <div class="flex items-center justify-center w-full">
                    <label for="task-main-file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-white/10 border-dashed rounded-2xl cursor-pointer bg-[#10231C]/30 hover:bg-[#10231C]/60 transition-colors">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-3 text-emerald-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                            </svg>
                            <p class="mb-1 text-xs sm:text-sm text-gray-300 font-bold">Click to upload or drag and drop files here</p>
                            <p class="text-[11px] text-gray-400">Any file (MAX. 10MB)</p>
                        </div>
                        <input id="task-main-file" name="files[]" type="file" multiple class="hidden" onchange="updateMainFileList(this)" />
                    </label>
                </div>
                <ul id="task_main_file_list" class="mt-2 text-xs text-gray-300 space-y-1 w-full text-left"></ul>
                <script>
                    function updateMainFileList(input) {
                        const list = document.getElementById('task_main_file_list');
                        list.innerHTML = '';
                        for (let i = 0; i < input.files.length; i++) {
                            const li = document.createElement('li');
                            li.className = 'flex items-center gap-2 bg-white/5 border border-white/10 px-3 py-2 rounded-xl text-xs text-white';
                            li.innerHTML = `📎 ${input.files[i].name}`;
                            list.appendChild(li);
                        }
                    }
                </script>

                <div class="flex flex-wrap items-center space-x-4 w-full justify-between gap-3">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_internal" class="rounded border-white/20 bg-[#08110e] text-emerald-600 focus:ring-emerald-500" checked>
                        <span class="ml-2 text-[10px] font-bold text-gray-400 uppercase">Internal Only (Hide from client)</span>
                    </label>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs sm:text-sm font-bold rounded-xl transition-all shadow-lg active:scale-95">
                        Upload Files
                    </button>
                </div>
            </form>
            
            @if($task->getMedia('task_attachments')->count() > 0)
            <div class="bg-[#08110e]/60 p-5 rounded-2xl border border-white/10">
                <h4 class="text-xs font-bold text-gray-300 mb-3 uppercase tracking-wider">Main Task Attachments</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($task->getMedia('task_attachments') as $media)
                        <div class="relative group bg-[#10231C]/60 border border-white/10 rounded-2xl overflow-hidden shadow-lg hover:border-emerald-500/40 transition-all">
                            @if(explode('/', $media->mime_type)[0] === 'image')
                                <div class="aspect-video w-full bg-[#08110e] relative overflow-hidden">
                                    <img src="{{ route('pm.attachments.download', $media) }}" alt="{{ $media->file_name }}" class="object-cover w-full h-full">
                                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                        <a href="{{ route('pm.attachments.download', $media) }}" target="_blank" class="p-2 bg-white/10 hover:bg-white/30 rounded-xl backdrop-blur-sm text-white transition-colors" title="View Full">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="aspect-video w-full bg-[#08110e] flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            
                            <div class="p-3">
                                <div class="flex items-start justify-between gap-2 mb-3">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-bold text-white truncate" title="{{ $media->file_name }}">{{ $media->file_name }}</p>
                                        <p class="text-[10px] text-gray-400 uppercase">{{ $media->human_readable_size }}</p>
                                    </div>
                                    <a href="{{ route('pm.attachments.download', $media) }}" target="_blank" class="inline-flex items-center px-2 py-1 bg-white/5 hover:bg-white/10 text-gray-300 rounded text-[10px] font-bold transition-colors">
                                        VIEW
                                    </a>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('pm.attachments.download', $media) }}" download class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-bold shadow-sm transition-all active:scale-95">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                        Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
            
            <form action="{{ route('pm.comments.store') }}" method="POST" enctype="multipart/form-data" class="bg-[#08110e]/60 p-5 rounded-2xl border border-white/10">
                @csrf
                <input type="hidden" name="commentable_id" value="{{ $task->id }}">
                <input type="hidden" name="commentable_type" value="task">
                <input type="hidden" name="guard" value="web">
                <textarea name="content" rows="3" class="block w-full rounded-xl bg-[#08110e] border border-white/10 text-white shadow-inner focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 text-xs sm:text-sm p-3 placeholder-gray-500" placeholder="Post an update or question..."></textarea>
                <div class="mt-3 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex flex-col space-y-2">
                        <div class="flex items-center space-x-4">
                            <input type="file" name="attachments[]" id="task-attach" multiple class="hidden" onchange="updateTeamCommentFileList(this)">
                            <label for="task-attach" class="text-xs text-gray-400 hover:text-emerald-400 cursor-pointer flex items-center gap-1 transition-colors">
                                📎 Attach file(s)
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_internal" value="1" class="rounded border-white/20 bg-[#08110e] text-emerald-600 focus:ring-emerald-500" checked>
                                <span class="ml-2 text-[10px] font-bold text-gray-400 uppercase">Internal Only</span>
                            </label>
                        </div>
                        <ul id="team_comment_file_list" class="text-xs text-gray-400 space-y-1"></ul>
                        <script>
                            function updateTeamCommentFileList(input) {
                                const list = document.getElementById('team_comment_file_list');
                                list.innerHTML = '';
                                for (let i = 0; i < input.files.length; i++) {
                                    const li = document.createElement('li');
                                    li.className = 'flex items-center gap-1 text-white';
                                    li.innerHTML = `📎 ${input.files[i].name}`;
                                    list.appendChild(li);
                                }
                            }
                        </script>
                    </div>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs sm:text-sm font-bold rounded-xl transition-all shadow-lg active:scale-95">
                        Post to Thread
                    </button>
                </div>
            </form>

            <div class="space-y-6">
                @foreach($task->comments as $comment)
                <div class="flex gap-4">
                    <div class="h-10 w-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-sm font-bold text-emerald-400 capitalize shrink-0">
                        {{ substr($comment->user->name ?? '?', 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0 bg-[#08110e]/40 p-4 rounded-2xl border border-white/5">
                         <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="text-sm font-bold text-white">{{ $comment->user->name ?? 'Unknown' }}</span>
                            @if($comment->user_type === 'App\Models\Admin')
                            <span class="px-2 py-0.5 rounded-full text-[10px] bg-red-500/10 text-red-400 border border-red-500/30 font-bold uppercase">Admin</span>
                            @endif
                            @if($comment->is_internal)
                            <span class="px-2 py-0.5 rounded-full text-[10px] bg-white/5 text-gray-400 border border-white/10 font-bold uppercase">Internal</span>
                            @endif
                            <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs sm:text-sm text-gray-300 leading-relaxed">{{ $comment->content }}</p>
                        
                        @if($comment->hasMedia('comment_attachments'))
                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                             @foreach($comment->getMedia('comment_attachments') as $media)
                                <div class="relative group bg-[#10231C]/60 border border-white/10 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all">
                                    @if(explode('/', $media->mime_type)[0] === 'image')
                                        <div class="aspect-video w-full bg-[#08110e] relative overflow-hidden">
                                            <img src="{{ route('pm.attachments.download', $media) }}" alt="{{ $media->file_name }}" class="object-cover w-full h-full">
                                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                                <a href="{{ route('pm.attachments.download', $media) }}" target="_blank" class="p-2 bg-white/10 hover:bg-white/30 rounded-lg backdrop-blur-sm text-white transition-colors" title="View Full">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="aspect-video w-full bg-[#08110e] flex items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    <div class="p-3">
                                        <div class="flex items-start justify-between gap-2 mb-3">
                                            <div class="min-w-0 flex-1">
                                                <p class="text-xs font-bold text-white truncate" title="{{ $media->file_name }}">{{ $media->file_name }}</p>
                                                <p class="text-[10px] text-gray-400 uppercase">{{ $media->human_readable_size }}</p>
                                            </div>
                                            <a href="{{ route('pm.attachments.download', $media) }}" target="_blank" class="inline-flex items-center px-2 py-1 bg-white/5 hover:bg-white/10 text-gray-300 rounded text-[10px] font-bold transition-colors">
                                                VIEW
                                            </a>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('pm.attachments.download', $media) }}" download class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold shadow-sm transition-all active:scale-95">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                                Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                             @endforeach
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <div class="pt-8 border-t border-white/10">
                <h3 class="text-base font-bold text-white uppercase tracking-wider mb-4">My Time Logs</h3>
                <div class="space-y-3">
                    @forelse($task->timeLogs->where('user_id', auth()->id())->sortByDesc('created_at') as $log)
                    <div class="flex items-center justify-between p-4 border border-white/10 rounded-2xl bg-[#08110e]/60">
                        <div class="text-sm">
                            <p class="font-bold text-white">{{ $log->start_time->format('M d, Y') }}</p>
                            <p class="text-xs text-gray-400">{{ $log->start_time->format('H:i') }} - {{ $log->end_time ? $log->end_time->format('H:i') : 'Active' }}</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-sm font-black text-emerald-400">
                                {{ $log->end_time ? $task->formatDuration($log->duration_minutes) : 'Running...' }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs text-gray-400 italic">No time logged by you yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
