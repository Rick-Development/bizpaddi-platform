@extends('admin::layouts.app')

@section('title', 'AI Workforce')

@section('content')
<style>
    #toast-container {
        position: fixed;
        top: 1.5rem;
        right: 1.5rem;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        pointer-events: none;
    }
    .toast-message {
        background: #1e293b;
        border: 1px solid #475569;
        color: #f8fafc;
        padding: 0.875rem 1.25rem;
        border-radius: 0.75rem;
        font-size: 0.875rem;
        font-weight: 600;
        box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        opacity: 0;
        transform: translateY(-1rem);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: auto;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        min-width: 300px;
    }
    .toast-success {
        border-color: #10b981;
        background: #064e3b;
        color: #d1fae5;
    }
    .toast-error {
        border-color: #ef4444;
        background: #7f1d1d;
        color: #fee2e2;
    }
    .toast-show {
        opacity: 1;
        transform: translateY(0);
    }
</style>
<div id="toast-container"></div>

<!-- Header Section -->
<div class="relative overflow-hidden rounded-2xl bg-white px-6 py-10 shadow-sm border border-gray-200 sm:px-12 sm:py-16 mb-8">
    <!-- Background glow effect -->
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-brand-primary/5 via-white to-white"></div>
    
    <div class="relative flex flex-col sm:flex-row sm:items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                {{ isset($currentRole) && $currentRole ? str_replace('_', ' ', Str::title($currentRole)) . 's' : 'AI Workforce Command Center' }}
            </h1>
            <p class="mt-3 max-w-2xl text-lg text-gray-600">
                Manage, configure, and monitor your autonomous AI employees. These agents operate 24/7 to scale your business.
            </p>
        </div>
        <div class="mt-6 sm:mt-0 flex-shrink-0 flex items-center gap-3">
            <form action="{{ route('admin.aiemployees.run-routines', ['role' => $currentRole]) }}" method="POST" class="inline-block form-run-routines">
                @csrf
                <button type="submit" class="inline-flex items-center justify-center rounded-xl border border-emerald-300 bg-emerald-50 text-emerald-700 px-5 py-3 text-sm font-semibold hover:bg-emerald-100 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2 text-emerald-600 animate-spin" style="animation-duration: 4s;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    Run Routines Now
                </button>
            </form>
            <a href="{{ route('admin.aiemployees.employees.create') }}" class="group relative inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-brand-primary to-brand-primary/80 px-6 py-3 text-sm font-semibold text-white shadow-lg transition-all duration-200 hover:from-brand-primary/90 hover:to-brand-primary/70 hover:shadow-brand-primary/30 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 focus:ring-offset-slate-900">
                <svg class="mr-2 h-5 w-5 transition-transform group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Hire New AI
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 mb-8 flex items-center shadow-sm">
        <div class="flex-shrink-0">
            <svg class="h-6 w-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
        </div>
    </div>
@endif

<!-- AI Employee Cards Grid -->
<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
    @forelse($employees as $employee)
        <div id="employee-card-{{ $employee->id }}" class="employee-card group relative flex flex-col justify-between overflow-hidden rounded-2xl bg-white border border-slate-200 shadow-sm transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <!-- Glassmorphism Header -->
            <div class="relative px-6 py-5 border-b border-slate-100 bg-slate-50/50 backdrop-blur-sm">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-brand-primary to-brand-primary/80 text-white shadow-inner">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23-.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 group-hover:text-brand-primary transition-colors">{{ $employee->name }}</h3>
                            <p class="text-xs font-medium text-slate-500 capitalize">{{ str_replace('_', ' ', $employee->role) }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer employee-status-toggle" data-id="{{ $employee->id }}" {{ $employee->is_active ? 'checked' : '' }}>
                            <div class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-5 flex-1 bg-white">
                <p class="text-sm text-slate-600 line-clamp-3">
                    {{ $employee->description ?? 'No specific description provided. Operating under standard protocols.' }}
                </p>
                
                @if(!$employee->is_sub_agent && $employee->subAgents()->exists())
                <div class="mt-3 pt-3 border-t border-slate-100 flex justify-between items-center text-xs font-bold text-slate-400 uppercase tracking-wide">
                    <span>Sub-Intelligences</span>
                    <span class="bg-brand-primary/10 border border-brand-primary/20 text-brand-primary px-2.5 py-0.5 rounded-full font-bold">
                        {{ $employee->subAgents()->count() }} Active
                    </span>
                </div>
                @endif
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('admin.aiemployees.employees.edit', $employee) }}" class="text-sm font-semibold text-brand-primary hover:text-brand-primary/80 transition-colors flex items-center">
                    Configure Profile 
                    <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </a>
                
                <form action="{{ route('admin.aiemployees.employees.destroy', $employee) }}" method="POST" class="inline-block form-delete-employee" data-id="{{ $employee->id }}" data-confirm="Are you sure you want to terminate this AI Employee? This action cannot be undone.">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors rounded-md p-1 hover:bg-red-50" title="Terminate Employee">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="col-span-full rounded-2xl border-2 border-dashed border-slate-300 p-12 text-center bg-white shadow-sm">
            <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <h3 class="mt-2 text-sm font-semibold text-slate-900">No AI Employees Found</h3>
            <p class="mt-1 text-sm text-slate-500">Get started by hiring your first AI agent for this role.</p>
            <div class="mt-6">
                <a href="{{ route('admin.aiemployees.employees.create') }}" class="inline-flex items-center rounded-lg bg-brand-primary px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-primary/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                    </svg>
                    Hire New AI
                </a>
            </div>
        </div>
    @endforelse
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {
        // Toast Helper
        function showToast(message, type = 'success') {
            const container = $('#toast-container');
            if (container.length === 0) {
                $('body').append('<div id="toast-container"></div>');
            }
            const toast = $('<div class="toast-message"></div>');
            toast.addClass(type === 'success' ? 'toast-success' : 'toast-error');
            toast.text(message);
            $('#toast-container').append(toast);
            
            // Trigger animation
            setTimeout(() => toast.addClass('toast-show'), 10);
            
            // Remove after 3.5s
            setTimeout(() => {
                toast.removeClass('toast-show');
                setTimeout(() => toast.remove(), 300);
            }, 3500);
        }

        // Run Routines form AJAX handler
        $('.form-run-routines').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const button = form.find('button[type="submit"]');
            const originalHtml = button.html();

            button.prop('disabled', true).addClass('opacity-50 cursor-not-allowed').html(`
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Running...
            `);

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                success: function(response) {
                    button.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed').html(originalHtml);
                    if (response.success) {
                        showToast(response.message, 'success');
                    } else {
                        showToast(response.message || 'Error triggering routines.', 'error');
                    }
                },
                error: function(xhr) {
                    button.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed').html(originalHtml);
                    let message = 'Error triggering routines.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    showToast(message, 'error');
                }
            });
        });

        // Delete Employee form AJAX handler
        $('.form-delete-employee').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const employeeId = form.data('id');
            const card = $(`#employee-card-${employeeId}`);
            const confirmMessage = form.data('confirm');
            
            if (confirm(confirmMessage)) {
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            showToast(response.message || 'AI Employee terminated successfully.', 'success');
                            card.fadeOut(400, function() {
                                card.remove();
                                // If list is now empty, reload to show empty state
                                if ($('.employee-card').length === 0) {
                                    window.location.reload();
                                }
                            });
                        } else {
                            showToast('Failed to delete the AI employee.', 'error');
                        }
                    },
                    error: function(xhr) {
                        let message = 'Failed to delete the AI employee.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        showToast(message, 'error');
                    }
                });
            }
        });

        // Status Toggle AJAX handler
        $('.employee-status-toggle').on('change', function() {
            const employeeId = $(this).data('id');
            const isChecked = $(this).is(':checked');
            
            $.ajax({
                url: `/admin/aiemployees/employees/${employeeId}/toggle-status`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        showToast(response.message || 'Status updated successfully.', 'success');
                    } else {
                        showToast('Failed to update status.', 'error');
                    }
                },
                error: function() {
                    showToast('Error updating status.', 'error');
                }
            });
        });
    });
</script>
@endpush
@endsection
