@extends('admin::layouts.app')

@section('title', 'Email Team')

@section('content')
<div id="admin-page-container" class="max-w-4xl mx-auto" x-data="{ recipientType: '{{ old('recipient_type', 'all_active') }}', searchQuery: '' }">
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Email Team</h1>
            <p class="mt-1 text-sm text-gray-500">Send an email announcement to specific members, roles, departments, or all active team members.</p>
        </div>
        <div>
            <a href="{{ route('admin.team-members.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 shadow-sm transition-all">
                <i class="fa-solid fa-arrow-left mr-2"></i> Back to Team
            </a>
        </div>
    </div>

    <form action="{{ route('admin.team-emails.send') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Recipient Group <span class="text-red-500">*</span></label>
                <select name="recipient_type" x-model="recipientType" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent text-sm">
                    <option value="all_active">All Active Team Members</option>
                    <option value="department">By Department</option>
                    <option value="role">By Role</option>
                    <option value="project">By Project</option>
                    <option value="selected_users">Specific Team Member(s)</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Select Template (Optional)</label>
                <select id="template_selector" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent text-sm">
                    <option value="">-- Choose a template --</option>
                    @foreach($templates as $template)
                        <option value="{{ $template->id }}">{{ $template->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Conditional Field: Department -->
        <div x-show="recipientType === 'department'" x-transition class="bg-gray-50 rounded-xl p-4 border border-gray-100">
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Department <span class="text-red-500">*</span></label>
            <select name="department" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent text-sm bg-white">
                <option value="">-- Choose a Department --</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->name }}" {{ old('department') == $dept->name ? 'selected' : '' }}>{{ $dept->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Conditional Field: Role -->
        <div x-show="recipientType === 'role'" x-transition class="bg-gray-50 rounded-xl p-4 border border-gray-100">
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Role <span class="text-red-500">*</span></label>
            <select name="role" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent text-sm bg-white">
                <option value="">-- Choose a Role --</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $role->name)) }}</option>
                @endforeach
            </select>
        </div>

        <!-- Conditional Field: Project -->
        <div x-show="recipientType === 'project'" x-transition class="bg-gray-50 rounded-xl p-4 border border-gray-100">
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Project <span class="text-red-500">*</span></label>
            <select name="project_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent text-sm bg-white">
                <option value="">-- Choose a Project --</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Conditional Field: Specific Team Members -->
        <div x-show="recipientType === 'selected_users'" x-transition class="bg-gray-50 rounded-xl p-4 border border-gray-100 space-y-3">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <label class="block text-sm font-semibold text-gray-700">Select Team Members <span class="text-red-500">*</span></label>
                <div class="relative max-w-xs w-full">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-gray-400 text-xs"></i>
                    </span>
                    <input type="text" x-model="searchQuery" placeholder="Filter members..." class="w-full pl-9 pr-3 py-1.5 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-brand-primary focus:border-transparent bg-white">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 max-h-60 overflow-y-auto p-1 bg-white border border-gray-200 rounded-lg shadow-inner">
                @foreach($teamMembers as $member)
                    <label x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="flex items-center gap-2 p-2 rounded-md hover:bg-gray-50 cursor-pointer border border-transparent hover:border-gray-100 transition-all">
                        <input type="checkbox" name="users[]" value="{{ $member->id }}" class="rounded text-brand-primary focus:ring-brand-primary h-4 w-4" {{ is_array(old('users')) && in_array($member->id, old('users')) ? 'checked' : '' }}>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-gray-900 truncate">{{ $member->name }}</p>
                            <p class="text-[10px] text-gray-500 truncate">{{ $member->email }}</p>
                        </div>
                    </label>
                @endforeach
            </div>
            <div class="flex gap-2">
                <button type="button" @click="document.querySelectorAll('input[name=\'users[]\']').forEach(el => el.checked = true)" class="text-[11px] font-semibold text-brand-primary hover:underline">Select All</button>
                <span class="text-gray-300">|</span>
                <button type="button" @click="document.querySelectorAll('input[name=\'users[]\']').forEach(el => el.checked = false)" class="text-[11px] font-semibold text-brand-primary hover:underline">Clear All</button>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Subject <span class="text-red-500">*</span></label>
            <input type="text" name="subject" id="email_subject" value="{{ old('subject') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent text-sm" placeholder="Enter email subject">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Message <span class="text-red-500">*</span></label>
            <textarea name="message" id="email_message" rows="12" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent text-sm">{{ old('message') }}</textarea>
        </div>

        <div class="flex justify-end gap-x-3 pt-4 border-t border-gray-100">
            <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-brand-primary text-white rounded-xl text-sm font-bold hover:bg-brand-primary/80 shadow-md hover:shadow-lg transition-all active:scale-95">
                <i class="fa-solid fa-paper-plane mr-2"></i>
                Send Announcement
            </button>
        </div>
    </form>
</div>

@push('styles')
<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .note-editor { border: 1px solid #d1d5db !important; border-radius: 0.75rem !important; overflow: hidden; background: white; }
    .note-editor .note-toolbar { background: #f9fafb; border-bottom: 1px solid #e5e7eb; }
</style>
@endpush

@push('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
    const emailTemplates = {!! json_encode($templates->keyBy('id')) !!};

    $(document).ready(function() {
        $.summernote.options.sanitize = function(html) { return html; };

        // Initialize Summernote
        $('#email_message').summernote({
            placeholder: 'Write your announcement content here...',
            height: 400,
            iframe: true,
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

        // Template Selector
        $('#template_selector').on('change', function() {
            const templateId = $(this).val();
            if (templateId && emailTemplates[templateId]) {
                const template = emailTemplates[templateId];
                if (template.subject) $('#email_subject').val(template.subject);
                if (template.content) {
                    $('#email_message').summernote('code', template.content);
                }
            }
        });
    });
</script>
@endpush
@endsection
