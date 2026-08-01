@extends('admin::layouts.app')

@section('title', 'Edit Legal Page')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Edit Legal Page</h1>
</div>

<form action="{{ route('admin.legal-pages.update', $legalPage) }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')
    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
        <div class="px-4 py-6 sm:p-8">
            <div class="grid max-w-4xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <div class="sm:col-span-4">
                    <label for="title" class="block text-sm font-medium text-gray-900">Title *</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $legalPage->title) }}" required
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('title')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="effective_date" class="block text-sm font-medium text-gray-900">Effective Date</label>
                    <input type="date" name="effective_date" id="effective_date" 
                           value="{{ old('effective_date', $legalPage->effective_date?->format('Y-m-d')) }}"
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('effective_date')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6">
                    <label for="content" class="block text-sm font-medium text-gray-900">Content *</label>
                    <textarea name="content" id="content" rows="20" required
                              class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">{{ old('content', $legalPage->content) }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Updating content will increment the version number.</p>
                    @error('content')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
            <a href="{{ route('admin.legal-pages.index') }}" class="text-sm font-semibold text-gray-900">Cancel</a>
            <button type="submit" class="rounded-md bg-brand-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700">Update Page</button>
        </div>
    </div>
</form>
@endsection

@push('styles')
<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .note-editor.note-frame {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
    }
    .note-toolbar {
        background-color: #f9fafb;
        border-bottom: 1px solid #d1d5db;
    }
</style>
@endpush

@push('scripts')
<!-- jQuery (required for Summernote) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
$(document).ready(function() {
    console.log('Initializing Summernote on #content');
    $('#content').summernote({
        height: 600,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });
});
</script>
@endpush
