@extends('admin::layouts.app')

@section('title', 'Bulk Send Email')

@push('styles')
<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    /* Prevent leaked email template styles from turning our inputs white */
    input[type="text"], 
    input[type="email"], 
    textarea, 
    select,
    h1, h2, h3, h4, h5, h6,
    p, span, div:not(.note-editable *) {
        color: #1a202c !important; /* Force a dark color */
        -webkit-text-fill-color: #1a202c !important;
    }
    
    label {
        color: #374151 !important;
    }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Bulk Email to {{ $contacts->count() }} Contacts</h1>
        <p class="mt-1 text-sm text-gray-600">Send personalized emails to all selected contacts.</p>
    </div>

    <form action="{{ route('admin.contacts.process-bulk-email') }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-6">
        @csrf
        <input type="hidden" name="ids" value="{{ $contacts->pluck('id')->implode(',') }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Recipients</label>
                <div class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 text-sm max-h-24 overflow-y-auto">
                    {{ $contacts->pluck('email')->implode(', ') }}
                </div>
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
            <p class="mt-2 text-xs text-gray-500 italic">Available placeholders: @{{ first_name }}, @{{ last_name }}, @{{ email }}, @{{ company }}</p>
        </div>

        <div class="flex justify-end gap-x-3 pt-4">
            <a href="{{ route('admin.contacts.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-[#FB4C4B] text-white rounded-md text-sm font-medium hover:bg-[#D93837] shadow-sm transition-all">
                <i class="fa-solid fa-paper-plane mr-2"></i>
                Send Bulk Email
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
    const emailTemplates = {!! json_encode($templates->keyBy('id')) !!};

    $(document).ready(function() {
        // Override Summernote's built-in sanitizer with a passthrough so that
        // full email HTML (DOCTYPE, <style>, !important, MSO conditionals) is
        // saved exactly as written without any stripping.
        $.summernote.options.sanitize = function(html) { return html; };

        $('#email_message').summernote({
            height: 500,
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
