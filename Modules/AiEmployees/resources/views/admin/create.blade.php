@extends('admin::layouts.app')

@section('title', 'Hire AI Employee')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <a href="{{ route('admin.aiemployees.employees.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-900 flex items-center transition-colors">
            <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Back to AI Workforce
        </a>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="relative bg-gray-50 px-8 py-8 overflow-hidden border-b border-gray-200">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-brand-primary/10 via-gray-50 to-gray-50"></div>
            <div class="relative flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-primary text-white shadow-lg shadow-brand-primary/30">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Hire New AI Employee</h1>
                    <p class="text-sm text-gray-600 mt-1">Configure a new intelligent agent for your workforce.</p>
                </div>
            </div>
        </div>

        <!-- Form Body -->
        <div class="p-8">
            <form id="aiEmployeeForm" action="{{ route('admin.aiemployees.employees.store') }}" method="POST" class="space-y-8">
                @csrf

                <div class="grid grid-cols-1 gap-y-8 gap-x-6 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                        <label for="name" class="block text-sm font-semibold text-slate-900">Designation / Name</label>
                        <div class="mt-2">
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="block w-full rounded-lg border-0 py-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6 transition-all" placeholder="e.g. Architect AI Alpha" required>
                        </div>
                        @error('name') <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-1">
                        <label for="role" class="block text-sm font-semibold text-slate-900">Assigned Role</label>
                        <div class="mt-2">
                            <select id="role" name="role" class="block w-full rounded-lg border-0 py-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6 transition-all cursor-pointer" required>
                                <option value="" disabled selected>Select a structural role...</option>
                                <option value="general_manager">General Manager</option>
                                <option value="operations_manager">Operations Manager</option>
                                <option value="business_development">Business Development</option>
                                <option value="sales_marketing">Sales & Marketing</option>
                                <option value="social_media">Social Media & Growth</option>
                                <option value="engineering">Engineering</option>
                                <option value="designer">AI Designer</option>
                            </select>
                        </div>
                        @error('role') <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="description" class="block text-sm font-semibold text-slate-900">Internal Profile Notes</label>
                        <div class="mt-2">
                            <textarea id="description" name="description" rows="2" class="block w-full rounded-lg border-0 py-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6 transition-all" placeholder="Internal notes about this AI's specific purpose...">{{ old('description') }}</textarea>
                        </div>
                        @error('description') <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="system_prompt" class="block text-sm font-semibold text-slate-900 mb-2">Core Directive (System Prompt)</label>
                        <div class="mt-2">
                            <textarea id="system_prompt" name="system_prompt" class="system-prompt-editor">{{ old('system_prompt') }}</textarea>
                        </div>
                        <p class="mt-2 text-xs text-slate-500">This instruction set defines the personality, boundaries, and capabilities of the AI.</p>
                        @error('system_prompt') <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100">
                    <div class="flex items-center gap-x-4 bg-slate-50 p-4 rounded-xl border border-slate-200">
                        <div class="relative flex h-6 items-center">
                            <input id="is_active" name="is_active" type="checkbox" value="1" class="h-5 w-5 rounded border-slate-300 text-brand-primary focus:ring-brand-primary cursor-pointer" {{ old('is_active', true) ? 'checked' : '' }}>
                        </div>
                        <div class="text-sm leading-6">
                            <label for="is_active" class="font-semibold text-slate-900 cursor-pointer">Activate immediately</label>
                            <p class="text-slate-500">When active, the AI will be available to process background tasks and queues.</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-x-4 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.aiemployees.employees.index') }}" class="text-sm font-semibold leading-6 text-slate-900 hover:text-slate-600 transition-colors">Cancel</a>
                    <button type="submit" class="rounded-lg bg-brand-primary px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-primary/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary transition-all">
                        Initialize AI
                    </button>
                </div>
            </form>
        </div>
    </div>
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
    });
</script>
@endpush
@endsection
