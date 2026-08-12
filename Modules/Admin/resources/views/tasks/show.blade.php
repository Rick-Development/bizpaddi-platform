@extends('admin::layouts.app')

@section('title', 'Task: ' . $task->title)

@section('content')
<div class="mb-8">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.pm-projects.show', $task->project) }}" class="text-gray-400 hover:text-gray-600">
                &larr; {{ $task->project->name }}
            </a>
            <h1 class="text-3xl font-bold text-gray-900">{{ $task->title }}</h1>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <a href="{{ route('admin.tasks.edit', $task) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Edit Task
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <!-- Task Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Description</h3>
            <div class="prose prose-sm max-w-none text-gray-700">
                {!! $task->description !!}
            </div>
        </div>

        <!-- Collaboration Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                <nav class="-mb-px flex space-x-8">
                    <button type="button" onclick="switchCollaborationTab('comments')" id="tab-comments" class="border-gray-600 text-gray-900 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Comments ({{ $task->comments->count() }})
                    </button>
                    <button type="button" onclick="switchCollaborationTab('files')" id="tab-files" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Files ({{ $task->getMedia('task_attachments')->count() }})
                    </button>
                    <button type="button" onclick="switchCollaborationTab('time')" id="tab-time" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Time Logs ({{ $task->timeLogs->count() }})
                    </button>
                </nav>
            </div>

            <div class="p-6">
                <!-- Previous content -->
                <div id="content-comments">
                    <form action="{{ route('pm.comments.store') }}" method="POST" enctype="multipart/form-data" class="mb-8">
                        @csrf
                        <input type="hidden" name="commentable_id" value="{{ $task->id }}">
                        <input type="hidden" name="commentable_type" value="task">
                        <textarea name="content" rows="3" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500 sm:text-sm" placeholder="Add a comment..."></textarea>
                        <div class="mt-3 flex items-center justify-between">
                            <div class="flex flex-col space-y-2">
                                <div class="flex items-center space-x-4">
                                    <label class="cursor-pointer text-xs text-gray-500 hover:text-gray-700">
                                        <input type="file" name="attachments[]" id="comment_attachments" multiple class="hidden" onchange="updateCommentFileList(this)">
                                        Attach file(s)
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="is_internal" value="1" class="rounded border-gray-300 text-brand-primary focus:ring-brand-primary">
                                        <span class="ml-2 text-xs text-gray-500">Internal Only</span>
                                    </label>
                                </div>
                                <ul id="comment_file_list" class="text-xs text-gray-600 space-y-1"></ul>
                                <script>
                                    function updateCommentFileList(input) {
                                        const list = document.getElementById('comment_file_list');
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
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-gray-600 hover:bg-gray-700">
                                Post
                            </button>
                        </div>
                    </form>

                    <div class="space-y-6">
                        @forelse($task->comments as $comment)
                        <div class="flex space-x-3">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-xs font-bold text-blue-600">
                                {{ substr($comment->user->name ?? 'U', 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center space-x-2">
                                    <div class="text-sm font-medium text-gray-900">{{ $comment->user->name ?? 'User' }}</div>
                                    @if($comment->is_internal)
                                    <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-gray-100 text-gray-500 font-bold uppercase">Internal</span>
                                    @endif
                                </div>
                                <div class="mt-1 text-sm text-gray-700">{{ $comment->content }}</div>
                                @if($comment->hasMedia('comment_attachments'))
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach($comment->getMedia('comment_attachments') as $media)
                                    <a href="{{ route('pm.attachments.download', $media) }}" target="_blank" class="text-xs text-gray-500 hover:text-gray-700 border border-gray-200 rounded px-2 py-1 bg-gray-50">
                                        📎 {{ $media->file_name }}
                                    </a>
                                    @endforeach
                                </div>
                                @endif
                                <div class="mt-2 text-xs text-gray-400">
                                    {{ $comment->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-center text-sm text-gray-500">No comments yet.</p>
                        @endforelse
                    </div>
                </div>

                <div id="content-files" class="hidden text-center py-6">
                     <form action="{{ route('pm.attachments.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col items-center space-y-4">
                        @csrf
                        <input type="hidden" name="model_id" value="{{ $task->id }}">
                        <input type="hidden" name="model_type" value="task">
                        
                        <div class="flex items-center justify-center w-full">
                            <label for="task-file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 font-semibold">Click to upload or drag and drop</p>
                                    <p class="text-xs text-gray-400">Any file (MAX. 10MB)</p>
                                </div>
                                <input id="task-file" name="files[]" type="file" multiple class="hidden" onchange="updateFileList(this)" />
                            </label>
                            <ul id="task_file_list" class="mt-2 text-sm text-gray-700 space-y-1 w-full text-left"></ul>
                            <script>
                                function updateFileList(input) {
                                    const list = document.getElementById('task_file_list');
                                    list.innerHTML = '';
                                    for (let i = 0; i < input.files.length; i++) {
                                        const li = document.createElement('li');
                                        li.className = 'flex items-center gap-2 bg-gray-100 px-2 py-1 rounded text-xs';
                                        li.innerHTML = `<svg class="w-3 h-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg> ${input.files[i].name}`;
                                        list.appendChild(li);
                                    }
                                }
                            </script>
                        </div>

                        <div class="flex items-center space-x-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_internal" class="rounded border-gray-300 text-brand-primary focus:ring-brand-primary" checked>
                                <span class="ml-2 text-sm text-gray-600">Internal Only (Hide from client)</span>
                            </label>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Upload File
                            </button>
                        </div>
                    </form>
                    <div class="mt-6 grid grid-cols-1 gap-2 text-left">
                        @foreach($task->getMedia('task_attachments') as $media)
                        <div class="p-2 border border-gray-200 rounded-lg flex items-center justify-between text-sm bg-white shadow-sm">
                            <div class="flex items-center space-x-2">
                                <span class="truncate max-w-xs">{{ $media->file_name }}</span>
                                @if($media->getCustomProperty('is_internal'))
                                <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-gray-100 text-gray-500 font-bold uppercase">Internal</span>
                                @endif
                            </div>
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('pm.attachments.download', $media) }}" target="_blank" class="text-brand-primary hover:text-brand-primary/80">View</a>
                                <a href="{{ route('pm.attachments.download', $media) }}" download class="text-blue-500 hover:text-blue-700">Download</a>
                                <form action="{{ route('pm.attachments.destroy', $media) }}" method="POST" onsubmit="return confirm('Delete this file?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs">Delete</button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div id="content-time" class="hidden">
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="text-sm font-medium text-gray-900">Time Tracking</h4>
                        @if($task->active_timer)
                            <form action="{{ route('pm.time-logs.stop', $task->active_timer) }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700">
                                    Stop Timer (Started {{ $task->active_timer->start_time->diffForHumans() }})
                                </button>
                            </form>
                        @else
                            <form action="{{ route('pm.time-logs.start') }}" method="POST">
                                @csrf
                                <input type="hidden" name="task_id" value="{{ $task->id }}">
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700">
                                    Start Timer
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="mb-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h5 class="text-xs font-bold text-gray-700 uppercase mb-4">Add Manual Entry</h5>
                        <form action="{{ route('pm.time-logs.store') }}" method="POST" class="grid grid-cols-2 gap-4">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Duration (Hours)</label>
                                <input type="number" name="duration_hours" value="0" min="0" class="block w-full rounded border-gray-300 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Minutes</label>
                                <input type="number" name="duration_minutes" value="0" min="0" max="59" class="block w-full rounded border-gray-300 text-sm">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs text-gray-500 mb-1">Date</label>
                                <input type="date" name="date" value="{{ date('Y-m-d') }}" class="block w-full rounded border-gray-300 text-sm">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs text-gray-500 mb-1">Description (Optional)</label>
                                <textarea name="description" rows="2" class="block w-full rounded border-gray-300 text-sm"></textarea>
                            </div>
                            <div class="col-span-2">
                                <button type="submit" class="w-full py-2 bg-gray-600 text-white text-xs font-bold rounded hover:bg-gray-700 transition-colors">
                                    Save Manual Entry
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="space-y-4">
                        @forelse($task->timeLogs->sortByDesc('created_at') as $log)
                        <div class="flex items-center justify-between p-3 border border-gray-100 rounded-lg {{ $log->end_time ? 'bg-white' : 'bg-green-50 animate-pulse' }}">
                            <div class="flex items-center space-x-3">
                                <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500">
                                    {{ substr($log->user->name ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $log->user->name ?? 'Unknown' }}</p>
                                    <p class="text-xs text-gray-500">{{ $log->start_time->format('M d, H:i') }} - {{ $log->end_time ? $log->end_time->format('H:i') : 'Active' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="text-sm font-semibold text-gray-900">
                                    {{ $log->end_time ? $task->formatDuration($log->duration_minutes) : 'Running...' }}
                                </span>
                                @if(auth()->id() == $log->user_id)
                                <form action="{{ route('pm.time-logs.destroy', $log) }}" method="POST" onsubmit="return confirm('Delete log?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-500 text-xs">Delete</button>
                                </form>
                                @endif
                            </div>
                        </div>
                        @empty
                        <p class="text-center text-sm text-gray-500 py-4">No time logged yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Details</h3>
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Status</span>
                    <span class="capitalize font-medium text-gray-900">{{ $task->status }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Priority</span>
                    <span class="capitalize font-medium text-red-600">{{ $task->priority }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Assigned To</span>
                    <span class="font-medium text-gray-900">{{ $task->assignee->name ?? 'None' }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Due Date</span>
                    <span class="font-medium text-gray-900">{{ $task->due_date ? $task->due_date->format('M d, Y') : 'No date' }}</span>
                </div>

                <div class="pt-3 border-t border-gray-100">
                    <h4 class="text-xs font-bold text-gray-500 uppercase mb-2">Client Approval</h4>
                    <form action="{{ route('admin.tasks.update-approval', $task) }}" method="POST" class="space-y-3">
                        @csrf @method('PATCH')
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Requires Approval</span>
                            <input type="checkbox" name="requires_client_approval" onchange="this.form.submit()" {{ $task->requires_client_approval ? 'checked' : '' }} class="h-4 w-4 text-brand-primary border-gray-300 rounded focus:ring-brand-primary">
                        </div>
                    </form>

                    @if($task->requires_client_approval)
                    <div class="mt-3 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Status</span>
                            <span class="font-medium capitalize 
                                @if($task->approval_status === 'approved') text-green-600
                                @elseif($task->approval_status === 'changes_requested') text-red-600
                                @else text-yellow-600 @endif">
                                {{ $task->approval_status ?: 'Pending Request' }}
                            </span>
                        </div>
                        @if($task->client_feedback)
                        <div class="text-xs bg-gray-50 p-2 rounded border border-gray-100">
                            <p class="font-bold text-gray-700">Client Feedback:</p>
                            <p class="text-gray-600 italic">{{ $task->client_feedback }}</p>
                        </div>
                        @endif
                        @if($task->approved_at)
                        <div class="text-xs text-gray-400">
                            Approved on {{ $task->approved_at->format('M d, Y H:i') }}
                        </div>
                        @endif
                    </div>
                    @endif
                </div>

                <div class="pt-3 border-t border-gray-100 flex justify-between text-sm">
                    <span class="text-gray-500">Total Time</span>
                    <span class="font-bold text-gray-900">{{ $task->formatDuration($task->total_time_spent) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function switchCollaborationTab(tab) {
    document.getElementById('content-comments').classList.toggle('hidden', tab !== 'comments');
    document.getElementById('content-files').classList.toggle('hidden', tab !== 'files');
    document.getElementById('content-time').classList.toggle('hidden', tab !== 'time');
    
    document.getElementById('tab-comments').classList.toggle('border-gray-600', tab === 'comments');
    document.getElementById('tab-files').classList.toggle('border-gray-600', tab === 'files');
    document.getElementById('tab-time').classList.toggle('border-gray-600', tab === 'time');
}
</script>
@endpush
