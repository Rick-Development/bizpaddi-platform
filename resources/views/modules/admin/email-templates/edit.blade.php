@extends('admin::layouts.app')

@section('title', 'Edit Email Template')

@section('content')
<style>
    /* Prevent leaked email template styles from turning our inputs white */
    input[type="text"], 
    input[type="email"], 
    textarea, 
    select,
    h1, h2, h3, h4, h5, h6,
    p, span, div:not(.note-editable *) {
    }
    
    label {
        color: #374151 !important;
    }
</style>

<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Email Template</h1>
        <p class="mt-1 text-sm text-gray-600">Update template content and details</p>
    </div>

    <form action="{{ route('admin.email-templates.update', $emailTemplate) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Template Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $emailTemplate->name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subject Line (Default)</label>
                    <input type="text" name="subject" value="{{ old('subject', $emailTemplate->subject) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <input type="text" name="category" value="{{ old('category', $emailTemplate->category) }}" placeholder="e.g. Newsletter, Transactional" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Feature Graphic (Thumbnail)</label>
                    @if($emailTemplate->thumbnail)
                        <div class="mb-3">
                            <img src="{{ $emailTemplate->thumbnail }}" alt="Feature Graphic Preview" class="h-32 w-auto object-cover rounded-lg border border-gray-200">
                        </div>
                    @endif
                    <input type="file" name="thumbnail" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                    <p class="mt-1 text-xs text-gray-500">Supported formats: JPEG, PNG, JPG, GIF, SVG. Max size: 2MB.</p>
                </div>
                
                <div>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $emailTemplate->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-[#FB4C4B] focus:ring-[#FB4C4B]">
                        <span class="text-sm font-medium text-gray-700">Active</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Template Content <span class="text-red-500">*</span></h2>
            
            <textarea name="content" id="template-content" required>{{ old('content', $emailTemplate->content) }}</textarea>
            
            @php
                $category = strtolower($emailTemplate->category ?? 'system');
                $name = strtolower($emailTemplate->name ?? '');

                $vars = ['site_name', 'site_url', 'name'];

                if (str_contains($category, 'attendance')) {
                    $vars = array_merge($vars, ['checkin_url', 'checkout_url', 'start_time', 'end_time', 'duration', 'completed_tasks', 'points_earned']);
                }

                if (str_contains($category, 'task') || str_contains($category, 'project') || str_contains($category, 'milestone')) {
                    $vars = array_merge($vars, ['assignee_name', 'task_title', 'task_description', 'task_due_date', 'task_url', 'reason', 'reviews_url', 'milestone_title', 'project_name', 'project_url', 'due_date', 'pending_percentage', 'employee_name', 'tasks_list']);
                }

                if (str_contains($category, 'report') || str_contains($category, 'summary')) {
                    $vars = array_merge($vars, ['total_members', 'on_time', 'late', 'absent', 'projects_summary', 'projects_status', 'invoices_total', 'leads_count', 'avg_score', 'table_rows', 'projects_audit', 'pm_name', 'projects_count', 'avg_response_hours', 'pending_reviews', 'resolved_blockers', 'overdue_count', 'new_leads', 'invoices_count', 'open_tickets', 'active_clock_ins']);
                }

                if (str_contains($category, 'pm') || str_contains($category, 'manager') || str_contains($category, 'project manager alerts')) {
                    $vars = array_merge($vars, ['checked_in_names', 'late_names', 'absent_names', 'employee_name', 'violation_type', 'violation_details', 'tasks_list', 'reason', 'task_title', 'task_url', 'project_name', 'project_url', 'due_date']);
                }

                if (str_contains($name, 'blocked') || str_contains($name, 'blocker')) {
                    $vars[] = 'reason';
                }

                $vars = array_unique($vars);
            @endphp

            <div class="mt-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                <span class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Available Variables (Click to select & copy):</span>
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach($vars as $var)
                        @php $varTag = '{{' . $var . '}}'; @endphp
                        <code onclick="insertVariable('{{ $varTag }}');" class="px-2.5 py-1 text-xs bg-white text-[#FB4C4B] border border-gray-200 rounded-lg font-mono cursor-pointer hover:bg-red-50 hover:border-red-200 transition-all select-all font-semibold" title="Click to insert">{{ $varTag }}</code>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-x-3">
            <a href="{{ route('admin.email-templates.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-[#FB4C4B] text-white rounded-md text-sm font-medium hover:bg-[#D93837]">
                Update Template
            </button>
        </div>
    </form>
</div>

<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

@push('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
function insertVariable(val) {
    $('#template-content').summernote('insertText', val);
}

$(document).ready(function() {
    // Override Summernote's built-in sanitizer with a passthrough so that
    // full email HTML (DOCTYPE, <style>, !important, MSO conditionals) is
    // saved exactly as written without any stripping.
    $.summernote.options.sanitize = function(html) { return html; };

    $('#template-content').summernote({
        height: 400,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'hr']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });
});
</script>
@endpush
@endsection
