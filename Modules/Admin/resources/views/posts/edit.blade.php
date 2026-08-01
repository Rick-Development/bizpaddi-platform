@extends('admin::layouts.app')

@section('title', 'Edit Post')

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

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Edit Blog Post</h1>
</div>

<form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')
    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
        <div class="px-4 py-6 sm:p-8">
            <div class="grid max-w-4xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <div class="sm:col-span-4">
                    <label for="title" class="block text-sm font-medium text-gray-900">Title *</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}" required
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('title')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-4">
                    <label for="slug" class="block text-sm font-medium text-gray-900">Slug *</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $post->slug) }}" required
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('slug')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="category_id" class="block text-sm font-medium text-gray-900">Category</label>
                    <select name="category_id" id="category_id"
                            class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6">
                    <label for="excerpt" class="block text-sm font-medium text-gray-900">Excerpt *</label>
                    <textarea name="excerpt" id="excerpt" rows="3" required
                              class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">{{ old('excerpt', $post->excerpt) }}</textarea>
                    @error('excerpt')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6">
                    <label for="content" class="block text-sm font-medium text-gray-900">Content *</label>
                    <textarea name="content" id="content" rows="12" required
                              class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">{{ old('content', $post->content) }}</textarea>
                    @error('content')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                @if($post->featured_image)
                <div class="sm:col-span-6">
                    <label class="block text-sm font-medium text-gray-900">Current Featured Image</label>
                    <div class="mt-2 text-gray-400 text-xs">Path: {{ $post->featured_image }}</div>
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="h-48 w-auto rounded-lg shadow object-cover">
                    </div>
                </div>
                @endif

                <div class="sm:col-span-6">
                    <label for="featured_image" class="block text-sm font-medium text-gray-900">
                        {{ $post->featured_image ? 'Replace Featured Image' : 'Featured Image' }}
                    </label>
                    <input type="file" name="featured_image" id="featured_image" accept="image/*"
                           class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50">
                    <p class="mt-1 text-sm text-gray-500">PNG, JPG up to 5MB</p>
                    @error('featured_image')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6 border-t border-gray-200 pt-6">
                    <h3 class="text-base font-semibold text-gray-900 mb-4">SEO Settings</h3>
                </div>

                <div class="sm:col-span-4">
                    <label for="meta_title" class="block text-sm font-medium text-gray-900">Meta Title</label>
                    <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $post->meta_title) }}"
                           maxlength="60"
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    <p class="mt-1 text-sm text-gray-500">Recommended: 50-60 characters</p>
                    @error('meta_title')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6">
                    <label for="meta_description" class="block text-sm font-medium text-gray-900">Meta Description *</label>
                    <textarea name="meta_description" id="meta_description" rows="3" required
                              maxlength="160"
                              class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">{{ old('meta_description', $post->meta_description) }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Recommended: 150-160 characters</p>
                    @error('meta_description')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-4">
                    <label for="meta_keywords" class="block text-sm font-medium text-gray-900">Meta Keywords</label>
                    <input type="text" name="meta_keywords" id="meta_keywords" value="{{ old('meta_keywords', $post->meta_keywords) }}"
                           placeholder="keyword1, keyword2, keyword3"
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    <p class="mt-1 text-sm text-gray-500">Comma-separated keywords for SEO</p>
                    @error('meta_keywords')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6 border-t border-gray-200 pt-6">
                    <h3 class="text-base font-semibold text-gray-900 mb-4">Publishing</h3>
                </div>

                <div class="sm:col-span-3">
                    <label for="status" class="block text-sm font-medium text-gray-900">Status *</label>
                    <select name="status" id="status" required
                            class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                        <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="scheduled" {{ old('status', $post->status) === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    </select>
                    @error('status')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="published_at" class="block text-sm font-medium text-gray-900">Publish Date</label>
                    <input type="datetime-local" name="published_at" id="published_at" 
                           value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}"
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('published_at')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                @if($post->reading_time)
                <div class="sm:col-span-6">
                    <p class="text-sm text-gray-500">Reading time: {{ $post->reading_time }} min | Views: {{ number_format($post->views_count) }}</p>
                </div>
                @endif
            </div>
        </div>

        <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
            <a href="{{ route('admin.posts.index') }}" class="text-sm font-semibold text-gray-900">Cancel</a>
            <button type="submit" class="rounded-md bg-brand-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700">Update Post</button>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<!-- jQuery (required for Summernote) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Summernote for Content
    $('#content').summernote({
        height: 400,
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

    // Initialize Summernote for Excerpt
    $('#excerpt').summernote({
        height: 150,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol']],
            ['insert', ['link']]
        ]
    });

    // Initialize Summernote for Meta Description
    $('#meta_description').summernote({
        height: 120,
        toolbar: [
            ['style', ['bold', 'italic']]
        ],
        callbacks: {
            onKeyup: function(e) {
                var text = $(this).summernote('code').replace(/<[^>]*>/g, '');
                if (text.length > 160) {
                    alert('Meta description should be 160 characters or less');
                }
            }
        }
    });

    // Auto-generate slug from title
    $('#title').on('input', function() {
        var title = $(this).val();
        var slug = title.toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
        $('#slug').val(slug);
    });
});
</script>
@endpush
