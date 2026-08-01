@extends('admin::layouts.app')

@section('title', 'Send Email')

@push('styles')
<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    /* Professional Reset: Force everything in the admin layout to stay dark */
    /* Using 'html body' to make the selector "stronger" (higher specificity) */
    html body #admin-page-container,
    html body #admin-page-container h1,
    html body #admin-page-container h2,
    html body #admin-page-container p,
    html body #admin-page-container label,
    html body #admin-page-container input,
    html body #admin-page-container select,
    html body #admin-page-container span {
        /* color: #1a202c !important;
        -webkit-text-fill-color: #1a202c !important; */
    }

    /* Ensure the editor itself stays white background */
    .note-editor { background-color: #fff !important; }
</style>
@endpush

@section('content')
<div id="admin-page-container" class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Send Email to {{ $contact->first_name ?: $contact->email }}</h1>
        <p class="mt-1 text-sm text-gray-600">Compose and send an individual email to this contact.</p>
    </div>

    <form action="{{ route('admin.contacts.process-email', $contact) }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">To</label>
                <input type="text" value="{{ $contact->email }}" disabled class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Select Template (Optional)</label>
                <select id="template_selector" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent text-sm">
                    <option value="">-- Choose a template --</option>
                    @foreach($templates as $template)
                        <option value="{{ $template->id }}">{{ $template->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Subject <span class="text-red-500">*</span></label>
            <input type="text" name="subject" id="email_subject" value="{{ old('subject') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent" placeholder="Enter email subject">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Message <span class="text-red-500">*</span></label>
            <textarea name="message" id="email_message" rows="15" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent" placeholder="Type your message here...">{{ old('message') }}</textarea>
        </div>

        <div class="flex justify-end gap-x-3 pt-4">
            <a href="{{ route('admin.contacts.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-[#FB4C4B] text-white rounded-md text-sm font-medium hover:bg-[#D93837] shadow-sm transition-all">
                <i class="fa-solid fa-paper-plane mr-2"></i>
                Send Email
            </button>
        </div>
    </form>
</div>

@push('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
    // Safe template data injection
    const emailTemplates = {!! json_encode($templates->keyBy('id')) !!};

    $(document).ready(function() {
        // Override Summernote's built-in sanitizer with a passthrough so that
        // full email HTML (DOCTYPE, <style>, !important, MSO conditionals) is
        // saved exactly as written without any stripping.
        $.summernote.options.sanitize = function(html) { return html; };

        // Initialize Summernote
        $('#email_message').summernote({
            height: 500,
            iframe: true, // This ISOLATES the editor so styles don't leak out
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

        // Template Selector Logic
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
