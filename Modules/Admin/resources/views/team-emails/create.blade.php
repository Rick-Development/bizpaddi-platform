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
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-sm font-semibold text-gray-700">Select Template (Optional)</label>
                    <span class="text-xs text-gray-500 font-medium">Team & Staff Only</span>
                </div>
                <select id="template_selector" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent text-sm">
                    <option value="">-- Choose a team template --</option>
                    @foreach($templates->groupBy('category') as $category => $items)
                        <optgroup label="{{ $category ?: 'General Announcements' }}">
                            @foreach($items as $template)
                                <option value="{{ $template->id }}">{{ $template->name }}</option>
                            @endforeach
                        </optgroup>
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

        <!-- Available Shortcuts / Placeholders -->
        <div class="rounded-xl border border-gray-200 bg-gray-50/80 p-4 space-y-3">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                <span class="text-xs font-bold text-gray-700 uppercase tracking-wider flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-brand-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    Available Shortcuts (Click to Insert)
                </span>
                <span class="text-[11px] text-gray-500">Click any shortcut tag below to insert it at your cursor</span>
            </div>

            <div class="flex flex-wrap gap-2">
                <!-- Performance Tags -->
                <button type="button" onclick="insertShortcut('&#123;&#123;score&#125;&#125;')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-mono font-semibold bg-white hover:bg-brand-primary hover:text-white border border-gray-200 text-gray-800 shadow-sm transition-all hover:scale-105" title="Member Performance Score %">
                    <span>&#123;&#123;score&#125;&#125;</span>
                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-rose-50 text-rose-700 font-sans">Score %</span>
                </button>

                <button type="button" onclick="insertShortcut('&#123;&#123;performance_url&#125;&#125;')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-mono font-semibold bg-white hover:bg-brand-primary hover:text-white border border-gray-200 text-gray-800 shadow-sm transition-all hover:scale-105" title="Performance Scorecard Link">
                    <span>&#123;&#123;performance_url&#125;&#125;</span>
                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-rose-50 text-rose-700 font-sans">Scorecard</span>
                </button>

                <!-- Project Tags -->
                <button type="button" onclick="insertShortcut('&#123;&#123;project_name&#125;&#125;')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-mono font-semibold bg-white hover:bg-brand-primary hover:text-white border border-gray-200 text-gray-800 shadow-sm transition-all hover:scale-105" title="Project Name">
                    <span>&#123;&#123;project_name&#125;&#125;</span>
                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-blue-50 text-blue-700 font-sans">Project</span>
                </button>

                <button type="button" onclick="insertShortcut('&#123;&#123;due_date&#125;&#125;')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-mono font-semibold bg-white hover:bg-brand-primary hover:text-white border border-gray-200 text-gray-800 shadow-sm transition-all hover:scale-105" title="Due Date">
                    <span>&#123;&#123;due_date&#125;&#125;</span>
                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-amber-50 text-amber-700 font-sans">Due Date</span>
                </button>

                <button type="button" onclick="insertShortcut('&#123;&#123;pending_percentage&#125;&#125;')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-mono font-semibold bg-white hover:bg-brand-primary hover:text-white border border-gray-200 text-gray-800 shadow-sm transition-all hover:scale-105" title="Pending %">
                    <span>&#123;&#123;pending_percentage&#125;&#125;</span>
                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-amber-50 text-amber-700 font-sans">Pending %</span>
                </button>

                <button type="button" onclick="insertShortcut('&#123;&#123;project_url&#125;&#125;')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-mono font-semibold bg-white hover:bg-brand-primary hover:text-white border border-gray-200 text-gray-800 shadow-sm transition-all hover:scale-105" title="Project URL">
                    <span>&#123;&#123;project_url&#125;&#125;</span>
                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-blue-50 text-blue-700 font-sans">Link</span>
                </button>

                <!-- Recipient Info Tags -->
                <button type="button" onclick="insertShortcut('&#123;&#123;name&#125;&#125;')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-mono font-semibold bg-white hover:bg-brand-primary hover:text-white border border-gray-200 text-gray-800 shadow-sm transition-all hover:scale-105" title="Member Full Name">
                    <span>&#123;&#123;name&#125;&#125;</span>
                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-green-50 text-green-700 font-sans">Member Name</span>
                </button>

                <button type="button" onclick="insertShortcut('&#123;&#123;first_name&#125;&#125;')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-mono font-semibold bg-white hover:bg-brand-primary hover:text-white border border-gray-200 text-gray-800 shadow-sm transition-all hover:scale-105" title="Member First Name">
                    <span>&#123;&#123;first_name&#125;&#125;</span>
                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-green-50 text-green-700 font-sans">First Name</span>
                </button>

                <button type="button" onclick="insertShortcut('&#123;&#123;department&#125;&#125;')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-mono font-semibold bg-white hover:bg-brand-primary hover:text-white border border-gray-200 text-gray-800 shadow-sm transition-all hover:scale-105" title="Department">
                    <span>&#123;&#123;department&#125;&#125;</span>
                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-purple-50 text-purple-700 font-sans">Department</span>
                </button>

                <button type="button" onclick="insertShortcut('&#123;&#123;role&#125;&#125;')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-mono font-semibold bg-white hover:bg-brand-primary hover:text-white border border-gray-200 text-gray-800 shadow-sm transition-all hover:scale-105" title="Role">
                    <span>&#123;&#123;role&#125;&#125;</span>
                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-purple-50 text-purple-700 font-sans">Role</span>
                </button>

                <!-- Company & General -->
                <button type="button" onclick="insertShortcut('&#123;&#123;site_name&#125;&#125;')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-mono font-semibold bg-white hover:bg-brand-primary hover:text-white border border-gray-200 text-gray-800 shadow-sm transition-all hover:scale-105" title="Company Name">
                    <span>&#123;&#123;site_name&#125;&#125;</span>
                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-gray-100 text-gray-700 font-sans">Company</span>
                </button>

                <button type="button" onclick="insertShortcut('&#123;&#123;date&#125;&#125;')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-mono font-semibold bg-white hover:bg-brand-primary hover:text-white border border-gray-200 text-gray-800 shadow-sm transition-all hover:scale-105" title="Current Date">
                    <span>&#123;&#123;date&#125;&#125;</span>
                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-gray-100 text-gray-700 font-sans">Date</span>
                </button>

                <button type="button" onclick="insertShortcut('&#123;&#123;dashboard_url&#125;&#125;')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-mono font-semibold bg-white hover:bg-brand-primary hover:text-white border border-gray-200 text-gray-800 shadow-sm transition-all hover:scale-105" title="Dashboard Link">
                    <span>&#123;&#123;dashboard_url&#125;&#125;</span>
                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-gray-100 text-gray-700 font-sans">Dashboard</span>
                </button>
            </div>
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

    window.insertShortcut = function(tag) {
        try {
            $('#email_message').summernote('focus');
            $('#email_message').summernote('insertText', tag);
        } catch (e) {
            const textarea = document.getElementById('email_message');
            if (textarea) {
                textarea.focus();
                const start = textarea.selectionStart || 0;
                const end = textarea.selectionEnd || 0;
                textarea.value = textarea.value.substring(0, start) + tag + textarea.value.substring(end);
            }
        }
    };

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
