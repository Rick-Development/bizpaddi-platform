@extends('admin::layouts.app')

@section('title', 'Configure AI Employee')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        @php
            $backUrl = ($employee->is_sub_agent && $employee->parent_id)
                ? route('admin.aiemployees.employees.edit', $employee->parent_id)
                : route('admin.aiemployees.employees.index', ['role' => $employee->role]);
            $backLabel = ($employee->is_sub_agent && $employee->parent_id)
                ? 'Back to ' . ($employee->parent->name ?? 'Primary Agent')
                : 'Back to AI Workforce';
        @endphp
        <a href="{{ $backUrl }}" class="text-sm font-medium text-slate-500 hover:text-slate-900 flex items-center transition-colors">
            <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            {{ $backLabel }}
        </a>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="relative bg-gray-50 px-8 py-8 overflow-hidden border-b border-gray-200">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-brand-primary/10 via-gray-50 to-gray-50"></div>
            <div class="relative flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-primary text-white shadow-lg shadow-brand-primary/30">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Configure {{ $employee->name }}</h1>
                        <p class="text-sm text-gray-600 mt-1">Update parameters and core directives for this agent.</p>
                    </div>
                </div>
                
                @if($employee->is_active)
                <div class="hidden sm:flex items-center rounded-full bg-emerald-400/10 px-3 py-1 text-sm font-medium text-emerald-400 ring-1 ring-inset ring-emerald-400/20 border border-emerald-400/20 shadow-sm shadow-emerald-500/10">
                    <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Online
                </div>
                @else
                <div class="hidden sm:flex items-center rounded-full bg-slate-400/10 px-3 py-1 text-sm font-medium text-slate-400 ring-1 ring-inset ring-slate-400/20 border border-slate-400/20">
                    <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                    Offline
                </div>
                @endif
            </div>
        </div>

        <!-- Form Body -->
        <div class="p-8">
            <form id="aiEmployeeForm" action="{{ route('admin.aiemployees.employees.update', $employee) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')
                <input type="hidden" name="parent_id" value="{{ $employee->parent_id }}">
                <input type="hidden" name="is_sub_agent" value="{{ $employee->is_sub_agent ? '1' : '0' }}">

                <div class="grid grid-cols-1 gap-y-8 gap-x-6 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                        <label for="name" class="block text-sm font-semibold text-slate-900">Designation / Name</label>
                        <div class="mt-2">
                            <input type="text" name="name" id="name" value="{{ old('name', $employee->name) }}" class="block w-full rounded-lg border-0 py-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6 transition-all" required>
                        </div>
                        @error('name') <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-1">
                        <label for="role" class="block text-sm font-semibold text-slate-900">Assigned Role</label>
                        <div class="mt-2">
                            <select id="role" name="role" class="block w-full rounded-lg border-0 py-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6 transition-all cursor-pointer" required>
                                <option value="general_manager" {{ old('role', $employee->role) == 'general_manager' ? 'selected' : '' }}>General Manager</option>
                                <option value="operations_manager" {{ old('role', $employee->role) == 'operations_manager' ? 'selected' : '' }}>Operations Manager</option>
                                <option value="business_development" {{ old('role', $employee->role) == 'business_development' ? 'selected' : '' }}>Business Development</option>
                                <option value="sales_marketing" {{ old('role', $employee->role) == 'sales_marketing' ? 'selected' : '' }}>Sales & Marketing</option>
                                <option value="social_media" {{ old('role', $employee->role) == 'social_media' ? 'selected' : '' }}>Social Media & Growth</option>
                                <option value="engineering" {{ old('role', $employee->role) == 'engineering' ? 'selected' : '' }}>Engineering</option>
                                <option value="designer" {{ old('role', $employee->role) == 'designer' ? 'selected' : '' }}>AI Designer</option>
                            </select>
                        </div>
                        @error('role') <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="description" class="block text-sm font-semibold text-slate-900">Internal Profile Notes</label>
                        <div class="mt-2">
                            <textarea id="description" name="description" rows="2" class="block w-full rounded-lg border-0 py-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6 transition-all">{{ old('description', $employee->description) }}</textarea>
                        </div>
                        @error('description') <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="system_prompt" class="block text-sm font-semibold text-slate-900 mb-2">Core Directive (System Prompt)</label>
                        <div class="mt-2">
                            <textarea id="system_prompt" name="system_prompt" class="system-prompt-editor">{{ old('system_prompt', $employee->system_prompt) }}</textarea>
                        </div>
                        <p class="mt-2 text-xs text-slate-500">Careful: Altering these instructions will immediately change how this AI agent behaves across the system.</p>
                        @error('system_prompt') <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100">
                    <div class="flex items-center gap-x-4 bg-slate-50 p-4 rounded-xl border border-slate-200">
                        <div class="relative flex h-6 items-center">
                            <input id="is_active" name="is_active" type="checkbox" value="1" class="h-5 w-5 rounded border-slate-300 text-brand-primary focus:ring-brand-primary cursor-pointer" {{ old('is_active', $employee->is_active) ? 'checked' : '' }}>
                        </div>
                        <div class="text-sm leading-6">
                            <label for="is_active" class="font-semibold text-slate-900 cursor-pointer">Activate Agent</label>
                            <p class="text-slate-500">If unchecked, this AI will be safely powered down and will ignore all background tasks.</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-x-4 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.aiemployees.employees.index', ['role' => $employee->role]) }}" class="text-sm font-semibold leading-6 text-slate-900 hover:text-slate-600 transition-colors">Discard Changes</a>
                    <button type="submit" class="rounded-lg bg-brand-primary px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-primary/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary transition-all">
                        Update Configuration
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if(!$employee->is_sub_agent)
    <!-- Sub-Intelligences Section -->
    <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-100 bg-slate-50/50 backdrop-blur-sm">
            <h3 class="text-lg font-bold text-slate-900">Specialized Sub-Intelligences</h3>
            <p class="text-xs text-slate-500 mt-1">Configure and train the specialized sub-intelligences that audit, check, and assist this Primary AI.</p>
        </div>
        <div class="p-8">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                @forelse($employee->subAgents as $sub)
                    <div class="flex items-start justify-between p-4 rounded-xl border border-slate-200 hover:border-brand-primary/50 transition-all bg-white">
                        <div class="flex-1 min-w-0 pr-4">
                            <div class="flex items-center space-x-2">
                                <span id="status-dot-{{ $sub->id }}" class="h-2 w-2 rounded-full {{ $sub->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                                <h4 class="text-sm font-bold text-slate-950 truncate">{{ $sub->name }}</h4>
                            </div>
                            <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ strip_tags($sub->system_prompt) }}</p>
                        </div>
                        <div class="flex items-center space-x-3 flex-shrink-0">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer employee-status-toggle" data-id="{{ $sub->id }}" {{ $sub->is_active ? 'checked' : '' }}>
                                <div class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                            </label>
                            <a href="{{ route('admin.aiemployees.employees.edit', $sub) }}" class="rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-700 px-3 py-1.5 text-xs font-bold transition-all">Configure / Train</a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-6 text-slate-500">
                        <p class="text-sm font-semibold">No specialized sub-intelligences seeded for this employee.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    @endif
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .note-editor .note-toolbar { background: #f9fafb; border-bottom: 1px solid #e5e7eb; }
    .note-editor.note-frame { border: 1px solid #d1d5db; border-radius: 0.5rem; }
    .note-editor.note-airframe { border: 1px solid #d1d5db; border-radius: 0.5rem; }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        $('#system_prompt').summernote({
            placeholder: 'Define rules, goals, and constraints here...',
            tabsize: 2,
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        $('#aiEmployeeForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const submitBtn = form.find('button[type="submit"]');
            const originalText = submitBtn.text();
            
            submitBtn.prop('disabled', true).html('<svg class="animate-spin h-5 w-5 mr-3 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...');

            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    if (response.success && response.redirect) {
                        window.location.href = response.redirect;
                    }
                },
                error: function(xhr) {
                    submitBtn.prop('disabled', false).text(originalText);
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorMsg = 'Validation Errors:\n';
                        for (let key in errors) {
                            errorMsg += '- ' + errors[key][0] + '\n';
                        }
                        alert(errorMsg);
                    } else {
                        alert('An error occurred while saving.');
                    }
                }
            });
        });

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
                        const dot = $(`#status-dot-${employeeId}`);
                        if (isChecked) {
                            dot.removeClass('bg-slate-400').addClass('bg-emerald-500 animate-pulse');
                        } else {
                            dot.removeClass('bg-emerald-500 animate-pulse').addClass('bg-slate-400');
                        }
                    } else {
                        alert('Failed to update status.');
                    }
                },
                error: function() {
                    alert('Error updating status.');
                }
            });
        });
    });
</script>
@endpush
@endsection
