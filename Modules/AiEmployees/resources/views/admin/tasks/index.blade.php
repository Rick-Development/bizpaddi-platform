@extends('admin::layouts.app')

@section('title', 'AI Task Assignments')

@section('content')
<!-- Header -->
<div class="relative overflow-hidden rounded-2xl bg-white px-6 py-8 shadow-sm border border-gray-200 sm:px-12 mb-8">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-brand-primary/5 via-white to-white"></div>
    <div class="relative flex flex-col sm:flex-row sm:items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">Task Assignments</h1>
            <p class="mt-2 text-md text-gray-600">Assign new mandates to active AI employees and track their execution live.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.aiemployees.tasks.create') }}" class="inline-flex items-center justify-center rounded-xl bg-brand-primary hover:bg-brand-primary/95 text-white px-5 py-3 text-sm font-semibold shadow-md shadow-brand-primary/20 transition-all">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                Assign New Task
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 mb-6 flex items-center shadow-sm">
        <svg class="h-5 w-5 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="ml-3 text-sm font-medium text-emerald-800">{{ session('success') }}</p>
    </div>
@endif

@if(session('error'))
    <div class="rounded-xl bg-red-50 border border-red-200 p-4 mb-6 flex items-center shadow-sm">
        <svg class="h-5 w-5 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="ml-3 text-sm font-medium text-red-800">{{ session('error') }}</p>
    </div>
@endif

<!-- Filters -->
<div class="bg-white rounded-2xl border border-slate-200 p-5 mb-8 shadow-sm">
    <form method="GET" action="{{ route('admin.aiemployees.tasks.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div>
            <label for="employee_id" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">AI Employee</label>
            <select name="employee_id" id="employee_id" class="block w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-brand-primary focus:outline-none focus:ring-1 focus:ring-brand-primary bg-white">
                <option value="">All Employees</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                        {{ $emp->name }} ({{ str_replace('_', ' ', Str::title($emp->role)) }})
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="status" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Status</label>
            <select name="status" id="status" class="block w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-brand-primary focus:outline-none focus:ring-1 focus:ring-brand-primary bg-white">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 rounded-xl bg-slate-900 hover:bg-slate-800 text-white px-4 py-2.5 text-sm font-semibold transition-all">Filter</button>
            <a href="{{ route('admin.aiemployees.tasks.index') }}" class="rounded-xl border border-slate-300 hover:bg-slate-50 text-slate-700 px-4 py-2.5 text-sm font-semibold transition-all text-center">Reset</a>
        </div>
    </form>
</div>

<!-- Task List -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">AI Employee</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Task Assignment</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Assigned</th>
                    <th scope="col" class="px-6 py-4 class-right text-xs font-bold text-slate-400 uppercase tracking-wider text-right pr-6">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse($tasks as $task)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="whitespace-nowrap px-6 py-5">
                            <div class="flex items-center space-x-3">
                                <div class="h-8 w-8 rounded-lg bg-slate-100 flex items-center justify-center font-bold text-brand-primary text-xs uppercase">
                                    {{ substr($task->employee->name ?? 'AI', 0, 2) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-slate-900">{{ $task->employee->name ?? 'Deleted AI' }}</div>
                                    <div class="text-xs text-slate-500 capitalize">{{ str_replace('_', ' ', $task->employee->role ?? '') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="text-sm text-slate-800 font-medium line-clamp-2 max-w-lg" title="{{ $task->task }}">
                                {{ $task->task }}
                            </div>
                            @if($task->status === 'completed' && $task->output)
                                @php $out = json_decode($task->output, true); @endphp
                                @if(isset($out['result']))
                                    <div class="mt-2 text-xs font-semibold text-brand-primary bg-brand-primary/5 px-2.5 py-1.5 rounded-lg border border-brand-primary/10 inline-block line-clamp-1 max-w-md">
                                        📄 {{ Str::limit($out['result'], 80) }}
                                    </div>
                                @endif
                            @elseif($task->status === 'failed' && $task->output)
                                @php $out = json_decode($task->output, true); @endphp
                                <div class="mt-2 text-xs font-semibold text-red-700 bg-red-50 px-2.5 py-1.5 rounded-lg border border-red-100 inline-block line-clamp-1 max-w-md">
                                    ⚠️ {{ $out['text'] ?? 'Failed to execute task.' }}
                                </div>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-5">
                            @php
                                $statusColors = [
                                    'pending'    => 'background: #fef3c7; color: #d97706;',
                                    'processing' => 'background: #dbeafe; color: #2563eb;',
                                    'completed'  => 'background: #dcfce7; color: #15803d;',
                                    'failed'     => 'background: #fee2e2; color: #b91c1c;',
                                ];
                            @endphp
                            <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-bold uppercase tracking-wider" style="{{ $statusColors[$task->status] ?? '' }}">
                                {{ $task->status }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-6 py-5 text-sm text-slate-500 font-medium">
                            {{ $task->created_at->diffForHumans() }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-5 text-right text-sm font-medium pr-6">
                            <div class="inline-flex gap-2">
                                @if($task->status === 'failed')
                                    <form action="{{ route('admin.aiemployees.tasks.retry', $task) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 px-3 py-1.5 text-xs font-bold transition-all">Retry</button>
                                    </form>
                                @endif
                                
                                <a href="{{ route('admin.aiemployees.tasks.edit', $task) }}" class="rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-700 px-3 py-1.5 text-xs font-bold transition-all {{ $task->status === 'processing' ? 'pointer-events-none opacity-50' : '' }}">Edit</a>
                                
                                <button type="button" class="rounded-lg bg-red-50 hover:bg-red-100 text-red-600 border border-red-100 px-3 py-1.5 text-xs font-bold transition-all btn-delete-task" data-id="{{ $task->id }}" {{ $task->status === 'processing' ? 'disabled' : '' }}>Delete</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            <div class="text-3xl mb-3">📋</div>
                            <p class="text-sm font-semibold">No assigned tasks found.</p>
                            <p class="text-xs text-slate-400 mt-1">Assign a new task to get started.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($tasks->hasPages())
        <div class="bg-slate-50 px-6 py-4 border-t border-slate-100">
            {{ $tasks->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {
        $('.btn-delete-task').on('click', function() {
            const taskId = $(this).data('id');
            const row = $(this).closest('tr');
            
            if (confirm('Are you sure you want to remove this task assignment?')) {
                $.ajax({
                    url: `/admin/aiemployees/tasks/${taskId}`,
                    method: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            row.fadeOut(300, function() {
                                $(this).remove();
                            });
                        }
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON?.message || 'Failed to delete the task.');
                    }
                });
            }
        });
    });
</script>
@endpush
@endsection
