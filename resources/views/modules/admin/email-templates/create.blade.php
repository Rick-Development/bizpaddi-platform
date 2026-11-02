@extends('admin::layouts.app')

@section('title', 'Create Email Template')

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
        <h1 class="text-2xl font-bold text-white">Create Email Template</h1>
        <p class="mt-1 text-sm text-gray-400">Design a reusable email template</p>
    </div>

    <form action="{{ route('admin.email-templates.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl p-6">
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Template Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-[#0a1612] text-white border-white/10">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Subject Line (Default)</label>
                    <input type="text" name="subject" value="{{ old('subject') }}" class="w-full px-3 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-[#0a1612] text-white border-white/10">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Category</label>
                    <input type="text" name="category" value="{{ old('category') }}" placeholder="e.g. Newsletter, Transactional" class="w-full px-3 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-[#0a1612] text-white border-white/10">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Feature Graphic (Thumbnail)</label>
                    <input type="file" name="thumbnail" accept="image/*" class="w-full px-3 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-[#0a1612] text-white border-white/10">
                    <p class="mt-1 text-xs text-gray-400">Supported formats: JPEG, PNG, JPG, GIF, SVG. Max size: 2MB.</p>
                </div>
                
                <div>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="is_active" value="1" checked class="rounded border-white/10 text-emerald-400 focus:ring-emerald-500">
                        <span class="text-sm font-medium text-gray-300">Active</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Template Content <span class="text-red-500">*</span></h2>
            
            <textarea name="content" id="template-content" required>{{ old('content') }}</textarea>
            
            <div class="mt-4 p-4 bg-white/[0.03] rounded-xl border border-white/10 space-y-3">
                <span class="text-xs font-semibold text-gray-300 uppercase tracking-wide">Available Variables (Click to insert):</span>
                
                <div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase block mb-1">Global Variables</span>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['site_name', 'site_url', 'name'] as $var)
                            @php $varTag = '{{' . $var . '}}'; @endphp
                            <code onclick="insertVariable('{{ $varTag }}');" class="px-2 py-0.5 text-xs bg-[#10231C]/60 text-emerald-400 border border-white/10 rounded font-mono cursor-pointer hover:bg-red-50 hover:border-red-200 transition-all font-semibold" title="Click to insert">{{ $varTag }}</code>
                        @endforeach
                    </div>
                </div>

                <div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase block mb-1">Attendance & Clocking</span>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['checkin_url', 'checkout_url', 'start_time', 'end_time', 'duration', 'completed_tasks', 'points_earned'] as $var)
                            @php $varTag = '{{' . $var . '}}'; @endphp
                            <code onclick="insertVariable('{{ $varTag }}');" class="px-2 py-0.5 text-xs bg-[#10231C]/60 text-emerald-400 border border-white/10 rounded font-mono cursor-pointer hover:bg-red-50 hover:border-red-200 transition-all font-semibold" title="Click to insert">{{ $varTag }}</code>
                        @endforeach
                    </div>
                </div>

                <div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase block mb-1">Tasks & Projects</span>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['assignee_name', 'task_title', 'task_description', 'task_due_date', 'task_url', 'reason', 'reviews_url', 'milestone_title', 'project_name', 'project_url', 'due_date', 'pending_percentage'] as $var)
                            @php $varTag = '{{' . $var . '}}'; @endphp
                            <code onclick="insertVariable('{{ $varTag }}');" class="px-2 py-0.5 text-xs bg-[#10231C]/60 text-emerald-400 border border-white/10 rounded font-mono cursor-pointer hover:bg-red-50 hover:border-red-200 transition-all font-semibold" title="Click to insert">{{ $varTag }}</code>
                        @endforeach
                    </div>
                </div>

                <div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase block mb-1">Reports & Metrics</span>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['total_members', 'on_time', 'late', 'absent', 'projects_summary', 'projects_status', 'invoices_total', 'leads_count', 'avg_score', 'table_rows', 'projects_audit', 'pm_name', 'new_leads', 'invoices_count', 'open_tickets', 'active_clock_ins'] as $var)
                            @php $varTag = '{{' . $var . '}}'; @endphp
                            <code onclick="insertVariable('{{ $varTag }}');" class="px-2 py-0.5 text-xs bg-[#10231C]/60 text-emerald-400 border border-white/10 rounded font-mono cursor-pointer hover:bg-red-50 hover:border-red-200 transition-all font-semibold" title="Click to insert">{{ $varTag }}</code>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-x-3">
            <a href="{{ route('admin.email-templates.index') }}" class="px-4 py-2 border border-white/10 rounded-md text-sm font-medium text-gray-300 hover:bg-white/[0.03]">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-md text-sm font-medium hover:bg-emerald-500">
                Create Template
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
