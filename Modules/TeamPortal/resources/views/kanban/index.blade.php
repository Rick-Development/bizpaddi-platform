@extends('teamportal::layouts.app')

@section('title', 'Task Board')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Task Board</h1>
            <p class="text-sm text-gray-400 mt-1">Manage project tasks with real-time drag and drop</p>
        </div>
        @if(auth()->user()->isProjectManager() || auth()->user()->isAdmin())
        <div>
            <a href="{{ isset($project) && $project ? route('team.pm.tasks.create', $project) : route('team.pm.projects.index') }}" id="new-task-btn" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-sm font-bold transition-all shadow-lg shadow-emerald-950/50 active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                + New Task
            </a>
        </div>
        @endif
    </div>

    <!-- Filter Bar Card -->
    <form id="kanban-filters-form" class="bg-[#10231C]/60 backdrop-blur-sm rounded-2xl border border-white/10 p-5 shadow-xl space-y-4" onsubmit="event.preventDefault();">
        <!-- Top Row: Search tasks... | All Projects | All Assignees -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
            <!-- Keyword Search -->
            <div class="md:col-span-4 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" name="search" placeholder="Search tasks..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-white/10 bg-[#08110e]/80 text-white placeholder-gray-500 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 shadow-inner">
            </div>

            <!-- Project Selection -->
            <div class="md:col-span-4">
                <select name="project_id" class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 shadow-inner">
                    <option value="" class="bg-[#10231C] text-white">All Projects</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ isset($project) && $project && $project->id == $p->id ? 'selected' : '' }} class="bg-[#10231C] text-white">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Assignee Filter -->
            <div class="md:col-span-4">
                <select name="assigned_to" class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 shadow-inner">
                    <option value="" class="bg-[#10231C] text-white">All Assignees</option>
                    @foreach($assignees as $member)
                        <option value="{{ $member->id }}" class="bg-[#10231C] text-white">{{ $member->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Second Row: All Priorities | All Time (with Custom Date) | Reset Filters -->
        <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-white/5">
            <div class="flex flex-wrap items-center gap-3">
                <!-- Priority Filter -->
                <div class="w-full sm:w-44">
                    <select name="priority" class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 shadow-inner">
                        <option value="" class="bg-[#10231C] text-white">All Priorities</option>
                        <option value="low" class="bg-[#10231C] text-white">Low</option>
                        <option value="medium" class="bg-[#10231C] text-white">Medium</option>
                        <option value="high" class="bg-[#10231C] text-white">High</option>
                        <option value="urgent" class="bg-[#10231C] text-white">Urgent</option>
                    </select>
                </div>

                <!-- Date Filter Option -->
                <div class="w-full sm:w-44">
                    <select name="date_filter" id="date_filter" class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 shadow-inner">
                        <option value="" class="bg-[#10231C] text-white">All Time</option>
                        <option value="today" class="bg-[#10231C] text-white">Assigned Today</option>
                        <option value="yesterday" class="bg-[#10231C] text-white">Assigned Yesterday</option>
                        <option value="this_week" class="bg-[#10231C] text-white">Assigned This Week</option>
                        <option value="this_month" class="bg-[#10231C] text-white">Assigned This Month</option>
                        <option value="custom" class="bg-[#10231C] text-white">Custom Date Range</option>
                    </select>
                </div>

                <!-- Due Date / Deadline Filter -->
                <div class="w-full sm:w-52">
                    <select name="due_filter" id="due_filter" class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 shadow-inner font-medium">
                        <option value="" class="bg-[#10231C] text-white">All Deadlines</option>
                        <option value="overdue" {{ request('due_filter') == 'overdue' ? 'selected' : '' }} class="bg-[#10231C] text-white">🚨 Overdue Tasks</option>
                        <option value="due_soon" {{ request('due_filter') == 'due_soon' ? 'selected' : '' }} class="bg-[#10231C] text-white">⏰ Due Soon (Next 48h)</option>
                        <option value="due_today" {{ request('due_filter') == 'due_today' ? 'selected' : '' }} class="bg-[#10231C] text-white">📅 Due Today</option>
                        <option value="due_this_week" {{ request('due_filter') == 'due_this_week' ? 'selected' : '' }} class="bg-[#10231C] text-white">📆 Due This Week</option>
                        <option value="has_due_date" {{ request('due_filter') == 'has_due_date' ? 'selected' : '' }} class="bg-[#10231C] text-white">📌 Has Due Date</option>
                        <option value="no_due_date" {{ request('due_filter') == 'no_due_date' ? 'selected' : '' }} class="bg-[#10231C] text-white">⚪ No Due Date</option>
                    </select>
                </div>

                <!-- Custom Date Range Pickers -->
                <div id="custom-date-container" class="hidden flex items-center gap-2">
                    <input type="date" name="start_date" class="rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3 py-1.5 text-sm focus:ring-2 focus:ring-emerald-500 shadow-inner">
                    <span class="text-gray-400 text-xs font-medium">to</span>
                    <input type="date" name="end_date" class="rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3 py-1.5 text-sm focus:ring-2 focus:ring-emerald-500 shadow-inner">
                </div>
            </div>

            <!-- Reset Button -->
            <button type="button" id="reset-filters" class="text-xs font-semibold text-gray-400 hover:text-white cursor-pointer px-3 py-1.5 rounded-xl hover:bg-white/10 transition-all flex items-center gap-1.5 border border-white/5 hover:border-white/15">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Reset Filters
            </button>
        </div>
    </form>

    <!-- Kanban Board Wrapper with Loading Overlay -->
    <div id="kanban-board-container" class="relative min-h-[400px]">
        <div id="kanban-loader" class="hidden absolute inset-0 bg-[#08110e]/80 backdrop-blur-sm z-20 flex items-center justify-center rounded-2xl">
            <div class="flex flex-col items-center gap-3 bg-[#10231C] p-5 rounded-2xl shadow-2xl border border-white/10">
                <svg class="animate-spin h-8 w-8 text-emerald-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-xs font-bold text-gray-300">Loading tasks...</span>
            </div>
        </div>
        @include('teamportal::kanban.board')
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
const isPM = @json(auth()->user()->isProjectManager());

function initializeSortable() {
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
}

// Run initial sortable binding
initializeSortable();

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

// AJAX Filtering Setup
const filtersForm = document.getElementById('kanban-filters-form');
const loader = document.getElementById('kanban-loader');
const dateFilterSelect = document.getElementById('date_filter');
const customDateContainer = document.getElementById('custom-date-container');
const newTaskBtn = document.getElementById('new-task-btn');

function toggleCustomDate() {
    if (dateFilterSelect.value === 'custom') {
        customDateContainer.classList.remove('hidden');
    } else {
        customDateContainer.classList.add('hidden');
    }
}

if (dateFilterSelect) {
    dateFilterSelect.addEventListener('change', toggleCustomDate);
    toggleCustomDate();
}

function fetchFilteredTasks() {
    if (!loader || !filtersForm) return;

    loader.classList.remove('hidden');

    const formData = new FormData(filtersForm);
    const searchParams = new URLSearchParams(formData);

    const projectId = formData.get('project_id');
    if (newTaskBtn) {
        if (projectId) {
            newTaskBtn.href = `/dashboard/team/pm/projects/${projectId}/tasks/create`;
        } else {
            newTaskBtn.href = `{{ route('team.pm.projects.index') }}`;
        }
    }

    fetch('{{ route("team.kanban.filter") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(Object.fromEntries(searchParams))
    })
    .then(response => response.text())
    .then(html => {
        const boardEl = document.querySelector('#kanban-board-container .flex.gap-4.overflow-x-auto');
        if (boardEl) boardEl.remove();
        
        loader.insertAdjacentHTML('afterend', html);
        
        initializeSortable();
        loader.classList.add('hidden');
    })
    .catch(error => {
        console.error('Error fetching tasks:', error);
        loader.classList.add('hidden');
    });
}

if (filtersForm) {
    filtersForm.querySelectorAll('select, input[type="date"]').forEach(el => {
        el.addEventListener('change', fetchFilteredTasks);
    });

    let debounceTimer;
    const searchInput = filtersForm.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(fetchFilteredTasks, 250);
        });
    }

    const resetBtn = document.getElementById('reset-filters');
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            filtersForm.reset();
            toggleCustomDate();
            fetchFilteredTasks();
        });
    }
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
