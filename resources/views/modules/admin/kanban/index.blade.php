@extends('admin::layouts.app')

@section('title', 'Kanban Board')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Task Board</h1>
        <p class="text-sm text-gray-500 mt-1">Manage project tasks with drag and drop</p>
    </div>
    <div class="flex items-center gap-3">
        <form action="{{ route('admin.kanban.index') }}" method="GET" class="flex items-center gap-2">
            <select name="project_id" onchange="this.form.submit()" class="rounded-lg border-gray-300 text-sm focus:ring-gray-500">
                <option value="">All Projects</option>
                @foreach($projects as $p)
                    <option value="{{ $p->id }}" {{ $project && $project->id == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
        </form>
        <a href="{{ route('admin.tasks.create', ['project_id' => $project ? $project->id : null]) }}" class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-black transition-all">
            + New Task
        </a>
    </div>
</div>

<div class="flex gap-4 overflow-x-auto pb-8 min-h-[calc(100vh-250px)]">
    @foreach($columns as $status => $label)
    <div class="flex-shrink-0 w-80">
        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 flex flex-col h-full">
            <div class="flex items-center justify-between mb-4 px-1">
                <h3 class="font-bold text-gray-900 flex items-center gap-2">
                    {{ $label }}
                    <span class="bg-white text-gray-400 text-[10px] px-2 py-0.5 rounded-full border border-gray-100">
                        {{ isset($groupedTasks[$status]) ? $groupedTasks[$status]->count() : 0 }}
                    </span>
                </h3>
            </div>
            
            <div id="column-{{ $status }}" data-status="{{ $status }}" class="kanban-column space-y-3 flex-1 min-h-[200px]">
                @if(isset($groupedTasks[$status]))
                    @foreach($groupedTasks[$status] as $task)
                    <div data-id="{{ $task->id }}" class="kanban-task bg-white p-4 rounded-xl border border-gray-100 shadow-sm cursor-grab active:cursor-grabbing hover:border-gray-300 transition-all group">
                        <div class="flex items-start justify-between mb-2">
                            <span class="px-2 py-0.5 rounded text-[8px] font-bold uppercase 
                                @if($task->priority == 'urgent') bg-red-100 text-red-700
                                @elseif($task->priority == 'high') bg-orange-100 text-orange-700
                                @elseif($task->priority == 'medium') bg-blue-100 text-blue-700
                                @else bg-gray-100 text-gray-700 @endif">
                                {{ $task->priority }}
                            </span>
                            @if($task->milestone)
                            <span class="px-2 py-0.5 rounded text-[8px] font-bold uppercase bg-purple-100 text-purple-700 truncate max-w-[100px]" title="Milestone: {{ $task->milestone->title }}">
                                🏔️ {{ Str::limit($task->milestone->title, 10) }}
                            </span>
                            @endif
                            @if($task->requires_client_approval)
                            <span class="text-[10px] text-gray-400" title="Requires Client Approval">🛡️</span>
                            @endif
                        </div>
                        <h4 class="text-sm font-bold text-gray-900 mb-1 group-hover:text-black transition-colors">
                            <a href="{{ route('admin.tasks.show', $task) }}">{{ $task->title }}</a>
                        </h4>
                        <p class="text-[10px] text-gray-500 line-clamp-2 mb-3">{{ Str::limit(strip_tags($task->description), 100) }}</p>
                        
                        <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                            <div class="flex -space-x-1">
                               @if($task->assignee)
                               <div class="h-6 w-6 rounded-full bg-gray-100 flex items-center justify-center text-[10px] font-bold text-gray-500 border-2 border-white ring-1 ring-gray-100" title="{{ $task->assignee->name }}">
                                   {{ substr($task->assignee->name, 0, 1) }}
                               </div>
                               @else
                               <div class="h-6 w-6 rounded-full bg-gray-50 border border-dashed border-gray-200 flex items-center justify-center text-[10px] text-gray-300">
                                   ?
                               </div>
                               @endif
                            </div>
                            <div class="text-[10px] flex items-center gap-1.5 {{ $task->due_date && $task->due_date->isPast() && !in_array($task->status, ['done','completed']) ? 'text-red-500 font-semibold' : 'text-gray-400' }} font-medium font-mono">
                                @if($task->due_date)
                                    @if(!in_array($task->status, ['done','completed']))
                                        @if($task->due_date->isPast())
                                            <span class="relative flex h-2 w-2 shrink-0">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-red-600"></span>
                                            </span>
                                        @elseif($task->due_date->diffInHours(now(), false) >= -48)
                                            <span class="relative flex h-2 w-2 shrink-0">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                                            </span>
                                        @endif
                                    @endif
                                    {{ $task->due_date->format('M d') }} at {{ $task->due_date->format('g:i A') }}
                                @else
                                    No due date
                                @endif
                            </div>
                        </div>
                        @if($task->status_changed_at)
                        <div class="mt-2.5 pt-2.5 border-t border-gray-50 flex items-center text-[9px] text-gray-400 font-mono">
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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.querySelectorAll('.kanban-column').forEach(column => {
    new Sortable(column, {
        group: 'kanban',
        animation: 150,
        draggable: '.kanban-task',
        ghostClass: 'opacity-50',
        onEnd: function (evt) {
            updateTaskOrder();
        }
    });
});

function updateTaskOrder() {
    let data = {
        _token: '{{ csrf_token() }}',
        tasks: []
    };

    document.querySelectorAll('.kanban-column').forEach(column => {
        const status = column.dataset.status;
        column.querySelectorAll('.kanban-task').forEach((task, index) => {
            data.tasks.push({
                id: task.dataset.id,
                status: status,
                order: index
            });
        });
    });

    fetch('{{ route("admin.kanban.update-order") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Order updated');
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
@endpush
