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
                                    <button type="button" class="mt-2 text-xs font-semibold text-brand-primary bg-brand-primary/5 px-2.5 py-1.5 rounded-lg border border-brand-primary/10 inline-flex items-center gap-1 hover:bg-brand-primary/10 transition-all btn-view-output" data-id="{{ $task->id }}" data-agent="{{ $task->employee->name ?? 'AI' }}" data-role="{{ str_replace('_', ' ', Str::title($task->employee->role ?? '')) }}" data-task="{{ $task->task }}">
                                        📄 View Complete Response
                                    </button>
                                    <div class="hidden" id="full-output-{{ $task->id }}">{{ $out['result'] }}</div>
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
                            <div class="inline-flex gap-2 items-center">
                                <a href="{{ route('admin.aiemployees.tasks.show', $task) }}" class="rounded-lg bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 px-3 py-1.5 text-xs font-bold transition-all">View</a>
                                
                                @if($task->status === 'failed')
                                    <form action="{{ route('admin.aiemployees.tasks.retry', $task) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 px-3 py-1.5 text-xs font-bold transition-all">Retry</button>
                                    </form>
                                @endif

                                @if(in_array($task->status, ['processing', 'pending']))
                                    <button type="button"
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-orange-50 hover:bg-orange-100 text-orange-700 border border-orange-200 px-3 py-1.5 text-xs font-bold transition-all btn-stop-task"
                                        data-id="{{ $task->id }}"
                                        data-stop-url="{{ route('admin.aiemployees.tasks.stop', $task) }}"
                                        title="Stop this running task">
                                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                            <rect x="4" y="4" width="12" height="12" rx="1"/>
                                        </svg>
                                        Stop
                                    </button>
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

<!-- View Output Modal -->
<div id="outputModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex min-h-screen items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" id="closeModalBackdrop"></div>
        
        <!-- Modal Content Box -->
        <div class="relative transform overflow-hidden rounded-2xl bg-white border border-slate-200 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl flex flex-col max-h-[85vh]">
            <!-- Header -->
            <div class="border-b border-slate-100 bg-slate-50 px-6 py-4 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-900" id="modalAgentName">AI Employee Task Output</h3>
                    <p class="text-xs text-slate-500 font-medium" id="modalTaskMeta"></p>
                </div>
                <button type="button" class="text-slate-400 hover:text-slate-600 transition-colors" id="closeModalX">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <!-- Body -->
            <div class="p-6 overflow-y-auto flex-1 font-sans text-sm text-slate-700 leading-relaxed" id="modalOutputContent">
                <!-- Content gets dynamically loaded here -->
            </div>
            <!-- Footer -->
            <div class="border-t border-slate-100 bg-slate-50 px-6 py-4 flex items-center justify-end">
                <button type="button" class="rounded-xl border border-slate-300 hover:bg-slate-100 text-slate-700 px-5 py-2.5 text-sm font-semibold transition-all" id="closeModalBtn">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {
        // ── Stop Task ─────────────────────────────────────────────────────────
        $(document).on('click', '.btn-stop-task', function() {
            const btn      = $(this);
            const taskId   = btn.data('id');
            const stopUrl  = btn.data('stop-url');
            const row      = btn.closest('tr');

            if (!confirm('Stop this running task? It will be marked as failed and the AI will no longer process it.')) return;

            btn.prop('disabled', true).html(
                `<svg class="h-3 w-3 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> Stopping…`
            );

            $.ajax({
                url: stopUrl,
                method: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        // Update status badge in the row
                        row.find('span.uppercase').css({ background: '#fee2e2', color: '#b91c1c' }).text('FAILED');
                        // Remove stop + edit buttons, show retry instead
                        btn.closest('.inline-flex')
                           .find('.btn-stop-task').remove();
                        btn.closest('.inline-flex')
                           .find('a[href*="edit"]').removeClass('pointer-events-none opacity-50');
                        btn.closest('.inline-flex')
                           .find('.btn-delete-task').prop('disabled', false);

                        // Insert Retry button before Edit
                        const retryHtml = `
                            <form action="${stopUrl.replace('/stop', '/retry')}" method="POST" class="inline">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 px-3 py-1.5 text-xs font-bold transition-all">Retry</button>
                            </form>`;
                        row.find('a[href*="edit"]').before(retryHtml);

                        // Show brief toast
                        const toast = $('<div class="fixed bottom-6 right-6 z-50 rounded-xl bg-orange-600 text-white px-5 py-3 text-sm font-semibold shadow-xl flex items-center gap-2">⏹ Task stopped successfully.</div>');
                        $('body').append(toast);
                        setTimeout(() => toast.fadeOut(400, function() { $(this).remove(); }), 3000);
                    }
                },
                error: function(xhr) {
                    btn.prop('disabled', false).html(`<svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><rect x="4" y="4" width="12" height="12" rx="1"/></svg> Stop`);
                    alert(xhr.responseJSON?.message || 'Failed to stop the task.');
                }
            });
        });

        // ── Delete Task ───────────────────────────────────────────────────────
        $(document).on('click', '.btn-delete-task', function() {
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

        // Modal Event Handlers
        $(document).on('click', '.btn-view-output', function() {
            const taskId = $(this).data('id');
            const agentName = $(this).data('agent');
            const agentRole = $(this).data('role');
            const taskText = $(this).data('task');
            
            const statusLabel = $(this).closest('tr').find('span.uppercase').text().trim() || 'Pending';
            const rawContent = $(`#full-output-${taskId}`).html() || '';
            
            let formatted = '';
            if (!rawContent) {
                formatted = `
                    <div class="py-8 text-center text-slate-500">
                        <div class="text-4xl mb-3">⏳</div>
                        <p class="font-bold text-base text-slate-700">No output generated yet.</p>
                        <p class="text-sm text-slate-400 mt-1">This task is currently in state: <span class="px-2 py-0.5 rounded text-xs font-bold bg-slate-100 text-slate-600 uppercase tracking-wider">${statusLabel}</span>.</p>
                    </div>
                `;
            } else {
                // Format text (convert markdown-style format to HTML)
                formatted = rawContent
                    .replace(/\\n/g, '\n')
                    .replace(/\n\n/g, '<br><br>')
                    .replace(/\n/g, '<br>')
                    .replace(/- \*\*(.*?)\*\*(.*?)(<br>|$)/g, '<li class="ml-4"><strong class="text-brand-primary">$1</strong>$2</li>')
                    .replace(/- (.*?)(<br>|$)/g, '<li class="ml-4 text-slate-600">$1</li>');

                if (formatted.includes('<li')) {
                    formatted = `<ul class="list-disc mb-4 space-y-1 pl-4">${formatted}</ul>`;
                }
            }

            $('#modalAgentName').text(agentName);
            $('#modalTaskMeta').text(`${agentRole} | Task: "${taskText.substring(0, 60)}..."`);
            $('#modalOutputContent').html(formatted);
            
            $('#outputModal').removeClass('hidden').addClass('flex');
            $('body').addClass('overflow-hidden');
        });

        function closeModal() {
            $('#outputModal').addClass('hidden').removeClass('flex');
            $('body').removeClass('overflow-hidden');
        }

        $('#closeModalBtn, #closeModalX, #closeModalBackdrop').on('click', closeModal);
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') closeModal();
        });
    });
</script>
@endpush
@endsection
