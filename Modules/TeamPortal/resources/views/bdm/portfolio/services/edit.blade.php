@extends('teamportal::layouts.app')

@section('title', 'Edit Service')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Edit Service</h1>
</div>

<form action="{{ route('team.bdm.services.update', $service) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')
    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
        <div class="px-4 py-6 sm:p-8">
            <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <div class="sm:col-span-4">
                    <label for="name" class="block text-sm font-medium text-gray-900">Name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $service->name) }}" required
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-4">
                    <label for="slug" class="block text-sm font-medium text-gray-900">Slug *</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $service->slug) }}" required
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    <p class="mt-1 text-sm text-gray-500">URL-friendly version (e.g., web-development)</p>
                    @error('slug')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6">
                    <label for="description" class="block text-sm font-medium text-gray-900">Description</label>
                    <textarea name="description" id="description" rows="4"
                              class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">{{ old('description', $service->description) }}</textarea>
                    @error('description')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                @if($service->getFirstMediaUrl('icon'))
                <div class="sm:col-span-6">
                    <label class="block text-sm font-medium text-gray-900">Current Icon</label>
                    <div class="mt-2">
                        <img src="{{ $service->getFirstMediaUrl('icon') }}" alt="Current icon" class="h-16 w-16 rounded-lg shadow">
                    </div>
                </div>
                @endif

                <div class="sm:col-span-6">
                    <label for="icon" class="block text-sm font-medium text-gray-900">
                        {{ $service->getFirstMediaUrl('icon') ? 'Replace Icon' : 'Icon' }}
                    </label>
                    <input type="file" name="icon" id="icon" accept="image/*"
                           class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50">
                    <p class="mt-1 text-sm text-gray-500">PNG, JPG, SVG up to 1MB</p>
                    @error('icon')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="order" class="block text-sm font-medium text-gray-900">Order *</label>
                    <input type="number" name="order" id="order" value="{{ old('order', $service->order) }}" required min="0"
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('order')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 rounded border-gray-300 text-brand-primary focus:ring-brand-primary">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">Active</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
            <a href="{{ route('team.bdm.services.index') }}" class="text-sm font-semibold text-gray-900">Cancel</a>
            <button type="submit" class="rounded-md bg-brand-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700">Update Service</button>
        </div>
    </div>
</form>

@push('styles')
<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .note-editor .note-toolbar { background: #f9fafb; border-bottom: 1px solid #e5e7eb; }
    .note-editor.note-frame { border: 1px solid #d1d5db !important; border-radius: 0.5rem !important; overflow: hidden; background: white; }
</style>
@endpush

@push('scripts')
<!-- jQuery (required for Summernote) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        $('#description').summernote({
            height: 300,
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
