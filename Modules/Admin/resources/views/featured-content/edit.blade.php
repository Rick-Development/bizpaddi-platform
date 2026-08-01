@extends('admin::layouts.app')

@section('title', 'Edit Featured Content')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Edit Featured Content</h1>
</div>

<form action="{{ route('admin.featured-content.update', $featuredContent) }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')
    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
        <div class="px-4 py-6 sm:p-8">
            <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <div class="sm:col-span-6">
                    <label for="title" class="block text-sm font-medium text-gray-900">Title *</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $featuredContent->title) }}" required
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('title')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6">
                    <label for="excerpt" class="block text-sm font-medium text-gray-900">Excerpt</label>
                    <textarea name="excerpt" id="excerpt" rows="3"
                              class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">{{ old('excerpt', $featuredContent->excerpt) }}</textarea>
                    @error('excerpt')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="type" class="block text-sm font-medium text-gray-900">Type *</label>
                    <select name="type" id="type" required
                            class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                        <option value="service" {{ old('type', $featuredContent->type) === 'service' ? 'selected' : '' }}>Service</option>
                        <option value="project" {{ old('type', $featuredContent->type) === 'project' ? 'selected' : '' }}>Project</option>
                        <option value="news" {{ old('type', $featuredContent->type) === 'news' ? 'selected' : '' }}>News</option>
                    </select>
                    @error('type')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="order" class="block text-sm font-medium text-gray-900">Order *</label>
                    <input type="number" name="order" id="order" value="{{ old('order', $featuredContent->order) }}" required min="0"
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('order')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6">
                    <label for="link" class="block text-sm font-medium text-gray-900">Link</label>
                    <input type="text" name="link" id="link" value="{{ old('link', $featuredContent->link) }}"
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('link')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $featuredContent->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 rounded border-gray-300 text-brand-primary focus:ring-brand-primary">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">Active</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
            <a href="{{ route('admin.featured-content.index') }}" class="text-sm font-semibold text-gray-900">Cancel</a>
            <button type="submit" class="rounded-md bg-brand-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700">Update</button>
        </div>
    </div>
</form>
@endsection
