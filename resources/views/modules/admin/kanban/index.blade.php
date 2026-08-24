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
    <form id="kanban-filters-form" class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm flex flex-wrap items-center gap-3 text-sm" onsubmit="event.preventDefault();">
        <!-- Keyword Search -->
        <div class="w-full sm:w-auto flex-1 sm:flex-none">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-gray-400 text-xs"></i>
                </span>
                <input type="text" name="search" placeholder="Search tasks..." class="w-full sm:w-48 pl-9 pr-3 py-2 rounded-lg border-gray-300 text-sm focus:ring-gray-500 focus:border-gray-500">
            </div>
        </div>

        <!-- Project Selection -->
        <select name="project_id" class="rounded-lg border-gray-300 text-sm focus:ring-gray-500 focus:border-gray-500">
            <option value="">All Projects</option>
            @foreach($projects as $p)
                <option value="{{ $p->id }}" {{ $project && $project->id == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
            @endforeach
        </select>

        <!-- Assignee Filter -->
        <select name="assigned_to" class="rounded-lg border-gray-300 text-sm focus:ring-gray-500 focus:border-gray-500">
            <option value="">All Assignees</option>
            @foreach($assignees as $member)
                <option value="{{ $member->id }}">{{ $member->name }}</option>
            @endforeach
        </select>

        <!-- Priority Filter -->
        <select name="priority" class="rounded-lg border-gray-300 text-sm focus:ring-gray-500 focus:border-gray-500">
            <option value="">All Priorities</option>
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="high">High</option>
            <option value="urgent">Urgent</option>
        </select>

        <!-- Date Filter Option -->
        <select name="date_filter" id="date_filter" class="rounded-lg border-gray-300 text-sm focus:ring-gray-500 focus:border-gray-500">
            <option value="">All Time</option>
            <option value="today">Assigned Today</option>
            <option value="yesterday">Assigned Yesterday</option>
            <option value="this_week">Assigned This Week</option>
            <option value="this_month">Assigned This Month</option>
            <option value="custom">Custom Date Range</option>
        </select>

        <!-- Custom Date Range Pickers -->
        <div id="custom-date-container" class="hidden flex items-center gap-2">
            <input type="date" name="start_date" class="rounded-lg border-gray-300 text-sm focus:ring-gray-500 focus:border-gray-500 py-1.5">
            <span class="text-gray-400 text-xs">to</span>
            <input type="date" name="end_date" class="rounded-lg border-gray-300 text-sm focus:ring-gray-500 focus:border-gray-500 py-1.5">
        </div>

        <!-- Reset Button -->
        <button type="button" id="reset-filters" class="text-xs font-semibold text-gray-400 hover:text-gray-900 ml-auto cursor-pointer p-1.5 hover:bg-gray-50 rounded-lg transition-all">
            Reset Filters
        </button>
    </form>
</div>

<div id="kanban-board-container" class="transition-opacity duration-200">
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
    boardContainer.classList.add('opacity-50');

    const formData = new FormData(filtersForm);
    const params = new URLSearchParams(formData).toString();
    const url = `{{ route('admin.kanban.index') }}?${params}`;

    // Update the "+ New Task" button link
    const projectId = formData.get('project_id');
    if (projectId) {
        newTaskBtn.href = `{{ route('admin.tasks.create') }}?project_id=${projectId}`;
    } else {
        newTaskBtn.href = `{{ route('admin.tasks.create') }}`;
    }

    // Update address bar status without page refresh
    window.history.pushState({}, '', url);

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        boardContainer.innerHTML = html;
        initializeSortable();
        boardContainer.classList.remove('opacity-50');
    })
    .catch(error => {
        console.error('Error fetching tasks:', error);
        boardContainer.classList.remove('opacity-50');
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
