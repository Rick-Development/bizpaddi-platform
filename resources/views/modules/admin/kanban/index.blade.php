@extends('admin::layouts.app')

@section('title', 'Kanban Board')

@section('content')
<div class="mb-8 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Task Board</h1>
            <p class="text-sm text-gray-500 mt-1">Manage project tasks with drag and drop</p>
        </div>
        <div>
            <a id="new-task-btn" href="{{ route('admin.tasks.create', ['project_id' => $project ? $project->id : null]) }}" class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-black transition-all inline-block shadow-sm">
                + New Task
            </a>
        </div>
    </div>

    <!-- AJAX Filters Form -->
    <form id="kanban-filters-form" class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm space-y-3" onsubmit="event.preventDefault();">
        <!-- Top Row: Search tasks... | All Projects | All Assignees -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
            <!-- Keyword Search -->
            <div class="md:col-span-4 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" name="search" placeholder="Search tasks..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:ring-gray-900 focus:border-gray-900 shadow-sm">
            </div>

            <!-- Project Selection -->
            <div class="md:col-span-4">
                <select name="project_id" class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm focus:ring-gray-900 focus:border-gray-900 shadow-sm bg-white">
                    <option value="">All Projects</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ $project && $project->id == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Assignee Filter -->
            <div class="md:col-span-4">
                <select name="assigned_to" class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm focus:ring-gray-900 focus:border-gray-900 shadow-sm bg-white">
                    <option value="">All Assignees</option>
                    @foreach($assignees as $member)
                        <option value="{{ $member->id }}">{{ $member->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Second Row: All Priorities | All Time (with Custom Date) | Reset Filters -->
        <div class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-gray-100">
            <div class="flex flex-wrap items-center gap-3">
                <!-- Priority Filter -->
                <div class="w-full sm:w-44">
                    <select name="priority" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm focus:ring-gray-900 focus:border-gray-900 shadow-sm bg-white">
                        <option value="">All Priorities</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>

                <!-- Date Filter Option -->
                <div class="w-full sm:w-44">
                    <select name="date_filter" id="date_filter" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm focus:ring-gray-900 focus:border-gray-900 shadow-sm bg-white">
                        <option value="">All Time</option>
                        <option value="today">Assigned Today</option>
                        <option value="yesterday">Assigned Yesterday</option>
                        <option value="this_week">Assigned This Week</option>
                        <option value="this_month">Assigned This Month</option>
                        <option value="custom">Custom Date Range</option>
                    </select>
                </div>

                <!-- Custom Date Range Pickers -->
                <div id="custom-date-container" class="hidden flex items-center gap-2">
                    <input type="date" name="start_date" class="rounded-xl border border-gray-300 px-3 py-1.5 text-sm focus:ring-gray-900 focus:border-gray-900 shadow-sm bg-white">
                    <span class="text-gray-400 text-xs font-medium">to</span>
                    <input type="date" name="end_date" class="rounded-xl border border-gray-300 px-3 py-1.5 text-sm focus:ring-gray-900 focus:border-gray-900 shadow-sm bg-white">
                </div>
            </div>

            <!-- Reset Button -->
            <button type="button" id="reset-filters" class="text-xs font-semibold text-gray-500 hover:text-gray-900 cursor-pointer px-3 py-1.5 rounded-xl hover:bg-gray-100 transition-all flex items-center gap-1.5 border border-transparent hover:border-gray-200">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Reset Filters
            </button>
        </div>
    </form>
</div>

<div id="kanban-board-container" class="relative transition-opacity duration-200">
    <!-- Loading Spinner Overlay -->
    <div id="kanban-loader" class="hidden absolute inset-0 bg-white/70 backdrop-blur-xs z-50 flex items-center justify-center rounded-xl min-h-[300px]">
        <div class="flex flex-col items-center gap-2 bg-white/80 p-4 rounded-xl shadow-sm border border-gray-100">
            <svg class="animate-spin h-8 w-8 text-gray-900" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-xs font-semibold text-gray-700">Loading tasks...</span>
        </div>
    </div>
    @include('admin::kanban.board')
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
function initializeSortable() {
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

// AJAX Filtering Setup
const filtersForm = document.getElementById('kanban-filters-form');
const boardContainer = document.getElementById('kanban-board-container');
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

dateFilterSelect.addEventListener('change', toggleCustomDate);
toggleCustomDate(); // Run once on startup

function fetchFilteredTasks() {
    // Show loading spinner
    loader.classList.remove('hidden');

    const formData = new FormData(filtersForm);
    const searchParams = new URLSearchParams(formData);

    // Update the "+ New Task" button link
    const projectId = formData.get('project_id');
    if (projectId) {
        newTaskBtn.href = `{{ route('admin.tasks.create') }}?project_id=${projectId}`;
    } else {
        newTaskBtn.href = `{{ route('admin.tasks.create') }}`;
    }

    // Post filters to fetch results and keep URL completely clean
    fetch('{{ route("admin.kanban.filter") }}', {
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
        // Find existing board and update
        const boardEl = document.querySelector('.flex.gap-4.overflow-x-auto');
        if (boardEl) boardEl.remove();
        
        // Append new HTML structure after the loader
        loader.insertAdjacentHTML('afterend', html);
        
        initializeSortable();
        loader.classList.add('hidden');
    })
    .catch(error => {
        console.error('Error fetching tasks:', error);
        loader.classList.add('hidden');
    });
}

// Bind change event to select selectors and dates
filtersForm.querySelectorAll('select, input[type="date"]').forEach(el => {
    el.addEventListener('change', fetchFilteredTasks);
});

// Debounced filter for search input
let debounceTimer;
filtersForm.querySelector('input[name="search"]').addEventListener('input', function() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(fetchFilteredTasks, 250);
});

// Reset Button Logic
document.getElementById('reset-filters').addEventListener('click', function() {
    filtersForm.reset();
    toggleCustomDate();
    fetchFilteredTasks();
});
</script>
@endpush
