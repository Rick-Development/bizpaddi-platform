@extends('admin::layouts.app')

@section('title', 'Edit Training Document')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.aiemployees.knowledge.index') }}" class="inline-flex items-center text-sm font-semibold text-brand-primary hover:text-brand-primary/80 transition-colors mb-4">
            <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
            Back to Knowledge Base
        </a>
        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">Edit Training Document</h1>
        <p class="mt-2 text-md text-gray-600">Update the guidelines or context used to train the AI workforce.</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.aiemployees.knowledge.update', $document) }}">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Title & Category Grid -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="title" class="block text-sm font-bold text-slate-700 mb-2">Document Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $document->title) }}" class="block w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-brand-primary focus:outline-none focus:ring-1 focus:ring-brand-primary @error('title') border-red-300 @enderror" placeholder="e.g., Brand Guidelines 2026">
                        @error('title')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-bold text-slate-700 mb-2">Category / Topic</label>
                        <input type="text" name="category" id="category" value="{{ old('category', $document->category) }}" class="block w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-brand-primary focus:outline-none focus:ring-1 focus:ring-brand-primary @error('category') border-red-300 @enderror" placeholder="e.g., brand, marketing, operations">
                        @error('category')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Content Summernote / Textarea -->
                <div>
                    <label for="content" class="block text-sm font-bold text-slate-700 mb-2">Document Content</label>
                    <textarea name="content" id="content" rows="12" class="block w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-brand-primary focus:outline-none focus:ring-1 focus:ring-brand-primary @error('content') border-red-300 @enderror">{{ old('content', $document->content) }}</textarea>
                    @error('content')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.aiemployees.knowledge.index') }}" class="rounded-xl border border-slate-300 hover:bg-slate-50 text-slate-700 px-5 py-3 text-sm font-semibold transition-all">Cancel</a>
                <button type="submit" class="rounded-xl bg-brand-primary hover:bg-brand-primary/95 text-white px-5 py-3 text-sm font-semibold shadow-md shadow-brand-primary/20 transition-all">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Incorporate Summernote standard styling if available, otherwise vanilla fallback -->
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .note-editor.note-frame {
        border: 1px solid #cbd5e1 !important;
        border-radius: 0.75rem !important;
        overflow: hidden;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        $('#content').summernote({
            placeholder: 'Type or paste training guidelines here...',
            tabsize: 2,
            height: 350,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>
@endpush
@endsection
