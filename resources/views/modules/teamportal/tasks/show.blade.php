@extends('teamportal::layouts.app')

@section('title', $task->title)

@section('content')
<div class="space-y-6">
    <div class="flex items-center space-x-2 text-sm text-gray-500">
        @if(auth()->user()->isProjectManager())
            <a href="{{ route('team.kanban.index') }}" class="hover:text-gray-900 line-clamp-1">Task Board</a>
        @else
            <a href="{{ route('team.tasks.index') }}" class="hover:text-gray-900 line-clamp-1">My Tasks</a>
        @endif
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor font-medium">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-900 font-medium line-clamp-1">{{ $task->title }}</span>
    </div>

    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 rounded-xl overflow-hidden p-6">
        <div class="sm:flex sm:items-start sm:justify-between mb-8">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2 text-xs font-semibold">
                    <span class="px-2.5 py-1 rounded-lg bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20 uppercase tracking-wider">
                        {{ $task->priority }}
                    </span>
                    <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20 uppercase tracking-wider">
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
                    <span class="text-gray-500">{{ $task->project->name }}</span>
                    @endif
                </div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $task->title }}</h1>
                @if($task->due_date)
                <div class="flex items-center gap-1.5 text-xs text-gray-500 mt-2">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="{{ $task->due_date->isPast() && !in_array($task->status, ['done','completed']) ? 'text-red-600 font-semibold' : '' }}">
                        Due: {{ $task->due_date->format('M d, Y') }} at {{ $task->due_date->format('g:i A') }}
                    </span>
                </div>
                @endif
            </div>
            
            <div class="mt-4 sm:mt-0">
                <form action="{{ route('team.tasks.update-status', $task) }}" method="POST">
                    @csrf @method('PATCH')
                    <select name="status" onchange="this.form.submit()" class="rounded-lg border-gray-300 text-sm focus:ring-gray-500">
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

        <div class="mb-8 p-4 bg-gray-50 rounded-xl border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Time Spent</p>
                <h4 class="text-2xl font-bold text-gray-900">{{ $task->formatDuration($task->total_time_spent) }}</h4>
            </div>
            <div>
                @if($task->active_timer)
                    <form action="{{ route('pm.time-logs.stop', $task->active_timer) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-all shadow-lg shadow-red-200">
                            <span class="relative flex h-3 w-3">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-3 w-3 bg-white"></span>
                            </span>
                            Stop Timer ({{ $task->active_timer->start_time->diffForHumans(null, true) }})
                        </button>
                    </form>
                @else
                    <form action="{{ route('pm.time-logs.start') }}" method="POST">
                        @csrf
                        <input type="hidden" name="task_id" value="{{ $task->id }}">
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 text-white font-bold rounded-xl hover:bg-black transition-all shadow-lg shadow-gray-200">
                            ▶️ Start Work Timer
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="prose prose-sm max-w-none text-gray-700 mb-8 border-b border-gray-100 pb-8">
            {!! $task->description !!}
        </div>

        <!-- Collaboration -->
        <div class="mt-8 space-y-8">
            <h3 class="text-lg font-semibold text-gray-900">Collaboration & Attachments</h3>
            
            <form action="{{ route('pm.attachments.store') }}" method="POST" enctype="multipart/form-data" class="bg-gray-50 p-4 rounded-xl border border-gray-100 flex flex-col items-center space-y-4">
                @csrf
                <input type="hidden" name="model_id" value="{{ $task->id }}">
                <input type="hidden" name="model_type" value="task">
                
                <div class="flex items-center justify-center w-full">
                    <label for="task-main-file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-gray-50">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-4 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                            </svg>
                            <p class="mb-2 text-sm text-gray-500 font-semibold">Click to upload or drag and drop files here</p>
                            <p class="text-xs text-gray-400">Any file (MAX. 10MB)</p>
                        </div>
                        <input id="task-main-file" name="files[]" type="file" multiple class="hidden" onchange="updateMainFileList(this)" />
                    </label>
                </div>
                <ul id="task_main_file_list" class="mt-2 text-sm text-gray-700 space-y-1 w-full text-left"></ul>
                <script>
                    function updateMainFileList(input) {
                        const list = document.getElementById('task_main_file_list');
                        list.innerHTML = '';
                        for (let i = 0; i < input.files.length; i++) {
                            const li = document.createElement('li');
                            li.className = 'flex items-center gap-2 bg-white border border-gray-100 px-3 py-2 rounded-lg text-xs shadow-sm';
                            li.innerHTML = `📎 ${input.files[i].name}`;
                            list.appendChild(li);
                        }
                    }
                </script>

                <div class="flex items-center space-x-4 w-full justify-between">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_internal" class="rounded border-gray-300 text-gray-500 focus:ring-gray-500" checked>
                        <span class="ml-2 text-[10px] font-bold text-gray-400 uppercase">Internal Only (Hide from client)</span>
                    </label>
                    <button type="submit" class="px-6 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                        Upload Files
                    </button>
                </div>
            </form>
            
            @if($task->getMedia('task_attachments')->count() > 0)
            <div class="bg-gray-50 p-5 rounded-xl border border-gray-100">
                <h4 class="text-sm font-bold text-gray-900 mb-3 uppercase tracking-wider">Main Task Attachments</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($task->getMedia('task_attachments') as $media)
                        <div class="relative group bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-200">
                            @if(explode('/', $media->mime_type)[0] === 'image')
                                <div class="aspect-video w-full bg-gray-100 relative overflow-hidden">
                                    <img src="{{ route('pm.attachments.download', $media) }}" alt="{{ $media->file_name }}" class="object-cover w-full h-full">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                        <a href="{{ route('pm.attachments.download', $media) }}" target="_blank" class="p-2 bg-white/20 hover:bg-white/40 rounded-lg backdrop-blur-sm text-white transition-colors" title="View Full">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor font-semibold"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="aspect-video w-full bg-gray-50 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor font-medium">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            
                            <div class="p-3">
                                <div class="flex items-start justify-between gap-2 mb-3">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-bold text-gray-900 truncate" title="{{ $media->file_name }}">{{ $media->file_name }}</p>
                                        <p class="text-[10px] text-gray-500 uppercase">{{ $media->human_readable_size }}</p>
                                    </div>
                                    <a href="{{ route('pm.attachments.download', $media) }}" target="_blank" class="inline-flex items-center px-2 py-1 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded text-[10px] font-bold transition-colors">
                                        VIEW
                                    </a>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('pm.attachments.download', $media) }}" download class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 bg-gray-900 hover:bg-black text-white rounded-lg text-xs font-semibold shadow-sm transition-all active:scale-95">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor font-bold"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                        Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
            
            <form action="{{ route('pm.comments.store') }}" method="POST" enctype="multipart/form-data" class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                @csrf
                <input type="hidden" name="commentable_id" value="{{ $task->id }}">
                <input type="hidden" name="commentable_type" value="task">
                <textarea name="content" rows="3" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-gray-500 text-sm" placeholder="Post an update or question..."></textarea>
                <div class="mt-3 flex items-center justify-between">
                    <div class="flex flex-col space-y-2">
                        <div class="flex items-center space-x-4">
                            <input type="file" name="attachments[]" id="task-attach" multiple class="hidden" onchange="updateTeamCommentFileList(this)">
                            <label for="task-attach" class="text-xs text-gray-500 hover:text-gray-700 cursor-pointer flex items-center gap-1">
                                📎 Attach file(s)
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_internal" value="1" class="rounded border-gray-300 text-gray-500 focus:ring-gray-500" checked>
                                <span class="ml-2 text-[10px] font-bold text-gray-400 uppercase">Internal Only</span>
                            </label>
                        </div>
                        <ul id="team_comment_file_list" class="text-xs text-gray-600 space-y-1"></ul>
                        <script>
                            function updateTeamCommentFileList(input) {
                                const list = document.getElementById('team_comment_file_list');
                                list.innerHTML = '';
                                for (let i = 0; i < input.files.length; i++) {
                                    const li = document.createElement('li');
                                    li.className = 'flex items-center gap-1';
                                    li.innerHTML = `📎 ${input.files[i].name}`;
                                    list.appendChild(li);
                                }
                            }
                        </script>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                        Post to Thread
                    </button>
                </div>
            </form>

            <div class="space-y-6">
                @foreach($task->comments as $comment)
                <div class="flex gap-4">
                    <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center text-sm font-bold text-gray-400 capitalize">
                        {{ substr($comment->user->name ?? '?', 0, 1) }}
                    </div>
                    <div class="flex-1">
                         <div class="flex items-center gap-2 mb-1">
                            <span class="text-sm font-bold text-gray-900">{{ $comment->user->name ?? 'Unknown' }}</span>
                            @if($comment->is_internal)
                            <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-gray-100 text-gray-500 font-bold uppercase">Internal</span>
                            @endif
                            <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $comment->content }}</p>
                        
                        @if($comment->hasMedia('comment_attachments'))
                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                             @foreach($comment->getMedia('comment_attachments') as $media)
                                <div class="relative group bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-200">
                                    @if(explode('/', $media->mime_type)[0] === 'image')
                                        <div class="aspect-video w-full bg-gray-100 relative overflow-hidden">
                                            <img src="{{ route('pm.attachments.download', $media) }}" alt="{{ $media->file_name }}" class="object-cover w-full h-full">
                                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                                <a href="{{ route('pm.attachments.download', $media) }}" target="_blank" class="p-2 bg-white/20 hover:bg-white/40 rounded-lg backdrop-blur-sm text-white transition-colors" title="View Full">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor font-semibold"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="aspect-video w-full bg-gray-50 flex items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor font-medium">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    <div class="p-3">
                                        <div class="flex items-start justify-between gap-2 mb-3">
                                            <div class="min-w-0 flex-1">
                                                <p class="text-xs font-bold text-gray-900 truncate" title="{{ $media->file_name }}">{{ $media->file_name }}</p>
                                                <p class="text-[10px] text-gray-500 uppercase">{{ $media->human_readable_size }}</p>
                                            </div>
                                            <a href="{{ route('pm.attachments.download', $media) }}" target="_blank" class="inline-flex items-center px-2 py-1 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded text-[10px] font-bold transition-colors">
                                                VIEW
                                            </a>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('pm.attachments.download', $media) }}" download class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 bg-gray-900 hover:bg-black text-white rounded-lg text-xs font-semibold shadow-sm transition-all active:scale-95">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor font-bold"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
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

            <div class="pt-8 border-t border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">My Time Logs</h3>
                <div class="space-y-3">
                    @forelse($task->timeLogs->where('user_id', auth()->id())->sortByDesc('created_at') as $log)
                    <div class="flex items-center justify-between p-3 border border-gray-50 rounded-xl bg-white">
                        <div class="text-sm">
                            <p class="font-medium text-gray-900">{{ $log->start_time->format('M d, Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $log->start_time->format('H:i') }} - {{ $log->end_time ? $log->end_time->format('H:i') : 'Active' }}</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-sm font-bold text-gray-900">
                                {{ $log->end_time ? $task->formatDuration($log->duration_minutes) : 'Running...' }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-400">No time logged by you yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
