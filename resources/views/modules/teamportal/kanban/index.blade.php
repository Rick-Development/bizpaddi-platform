@extends('teamportal::layouts.app')

@section('title', 'My Task Board')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">My Task Board</h1>
    <p class="text-sm text-gray-500 mt-1">Manage your tasks with drag and drop</p>
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
                        </div>
                        <h4 class="text-sm font-bold text-gray-900 mb-1 group-hover:text-black transition-colors">
                            <a href="{{ route('team.tasks.show', $task) }}">{{ $task->title }}</a>
                        </h4>
                        <p class="text-[10px] text-gray-500 mb-2 truncate">{{ $task->project->name }}</p>
                        
                        <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                            <div class="flex items-center gap-2">
                                @if($task->assignee)
                                <div class="h-6 w-6 rounded-full bg-brand-primary/10 flex items-center justify-center text-[10px] text-brand-primary font-bold ring-2 ring-white" title="Assigned to: {{ $task->assignee->name }}">
                                    {{ substr($task->assignee->name, 0, 1) }}
                                </div>
                                @endif
                                <div class="text-[10px] flex items-center gap-1.5 {{ $task->due_date && $task->due_date->isPast() && !in_array($task->status, ['done','completed']) ? 'text-red-500 font-semibold' : 'text-gray-400' }} font-medium font-mono">
                                    @if($task->due_date)
                                        @if(!in_array($task->status, ['done','completed']))
                                            @if($task->due_date->isPast())
                                                <span class="relative flex h-2 w-2 shrink-0">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-red-600 animate-intense-blink"></span>
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
                            </div>
                            @if($task->requires_client_approval)
                            <div class="flex items-center gap-1">
                                <span class="text-[8px] font-bold text-blue-500 uppercase tracking-tighter">Client Review</span>
                            </div>
                            @endif
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
const isPM = @json(auth()->user()->isProjectManager());

document.querySelectorAll('.kanban-column').forEach(column => {
    const isDoneColumn = column.dataset.status === 'done';
    new Sortable(column, {
        group: {
            name: 'kanban',
            put: (isDoneColumn && !isPM) ? false : true
        },
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

    fetch('{{ route("team.kanban.update-order") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            console.log('Order updated');
        } else {
            alert(data.message || 'Error updating task status');
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(error.message || 'Only Project Managers can mark tasks as Completed.');
        window.location.reload();
    });
}
</script>
@endpush

@push('styles')
<style>
    @keyframes intenseBlink {
        0%, 100% { opacity: 0.15; transform: scale(0.85); }
        50% { opacity: 1; transform: scale(1.15); }
    }
    .animate-intense-blink {
        animation: intenseBlink 0.6s infinite ease-in-out;
    }
</style>
@endpush
