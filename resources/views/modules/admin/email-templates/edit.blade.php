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

    <form action="{{ route('admin.email-templates.update', $emailTemplate) }}" method="POST" class="space-y-6">
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
            
            <div class="mt-2 text-sm text-gray-500">
                Available variables: @{{ first_name }}, @{{ last_name }}, @{{ email }}, @{{ company }}
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
