@extends('admin::layouts.app')

@section('title', 'Edit Testimonial')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Edit Testimonial</h1>
</div>

<form action="{{ route('admin.testimonials.update', $testimonial) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')
    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
        <div class="px-4 py-6 sm:p-8">
            <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <div class="sm:col-span-4">
                    <label for="author_name" class="block text-sm font-medium text-gray-900">Author Name *</label>
                    <input type="text" name="author_name" id="author_name" value="{{ old('author_name', $testimonial->author_name) }}" required
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('author_name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-4">
                    <label for="author_position" class="block text-sm font-medium text-gray-900">Position / Company</label>
                    <input type="text" name="author_position" id="author_position" value="{{ old('author_position', $testimonial->author_position) }}"
                           placeholder="e.g., CEO, TechCorp Inc"
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('author_position')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6">
                    <label for="content" class="block text-sm font-medium text-gray-900">Testimonial Content *</label>
                    <textarea name="content" id="content" rows="4" required
                              class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">{{ old('content', $testimonial->content) }}</textarea>
                    @error('content')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="rating" class="block text-sm font-medium text-gray-900">Rating *</label>
                    <select name="rating" id="rating" required
                            class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                        <option value="5" {{ old('rating', $testimonial->rating) == 5 ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5 stars)</option>
                        <option value="4" {{ old('rating', $testimonial->rating) == 4 ? 'selected' : '' }}>⭐⭐⭐⭐ (4 stars)</option>
                        <option value="3" {{ old('rating', $testimonial->rating) == 3 ? 'selected' : '' }}>⭐⭐⭐ (3 stars)</option>
                        <option value="2" {{ old('rating', $testimonial->rating) == 2 ? 'selected' : '' }}>⭐⭐ (2 stars)</option>
                        <option value="1" {{ old('rating', $testimonial->rating) == 1 ? 'selected' : '' }}>⭐ (1 star)</option>
                    </select>
                    @error('rating')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                @if($testimonial->getFirstMediaUrl('avatar'))
                <div class="sm:col-span-6">
                    <label class="block text-sm font-medium text-gray-900">Current Avatar</label>
                    <div class="mt-2">
                        <img src="{{ $testimonial->getFirstMediaUrl('avatar') }}" alt="Current avatar" class="h-16 w-16 rounded-full shadow">
                    </div>
                </div>
                @endif

                <div class="sm:col-span-6">
                    <label for="avatar" class="block text-sm font-medium text-gray-900">
                        {{ $testimonial->getFirstMediaUrl('avatar') ? 'Replace Avatar' : 'Avatar Photo' }}
                    </label>
                    <input type="file" name="avatar" id="avatar" accept="image/*"
                           class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50">
                    <p class="mt-1 text-sm text-gray-500">PNG, JPG up to 1MB</p>
                    @error('avatar')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $testimonial->is_featured) ? 'checked' : '' }}
                               class="h-4 w-4 rounded border-gray-300 text-brand-primary focus:ring-brand-primary">
                        <label for="is_featured" class="ml-2 block text-sm text-gray-900">Featured (show on homepage)</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
            <a href="{{ route('admin.testimonials.index') }}" class="text-sm font-semibold text-gray-900">Cancel</a>
            <button type="submit" class="rounded-md bg-brand-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700">Update Testimonial</button>
        </div>
    </div>
</form>
@endsection
