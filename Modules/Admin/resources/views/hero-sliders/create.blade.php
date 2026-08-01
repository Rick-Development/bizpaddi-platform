@extends('admin::layouts.app')

@section('title', 'Create Hero Slider')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold leading-tight tracking-tight text-gray-900">Create Hero Slider</h1>
    <p class="mt-2 text-sm text-gray-600">Add a new slider to the homepage</p>
</div>

<form action="{{ route('admin.hero-sliders.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
        <div class="px-4 py-6 sm:p-8">
            <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <!-- Title -->
                <div class="sm:col-span-6">
                    <label for="title" class="block text-sm font-medium leading-6 text-gray-900">Title *</label>
                    <div class="mt-2">
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6">
                    </div>
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subtitle -->
                <div class="sm:col-span-6">
                    <label for="subtitle" class="block text-sm font-medium leading-6 text-gray-900">Subtitle</label>
                    <div class="mt-2">
                        <input type="text" name="subtitle" id="subtitle" value="{{ old('subtitle') }}"
                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6">
                    </div>
                    @error('subtitle')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="sm:col-span-6">
                    <label for="description" class="block text-sm font-medium leading-6 text-gray-900">Description</label>
                    <div class="mt-2">
                        <textarea name="description" id="description" rows="3"
                                  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6">{{ old('description') }}</textarea>
                    </div>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Button Text -->
                <div class="sm:col-span-3">
                    <label for="button_text" class="block text-sm font-medium leading-6 text-gray-900">Button Text</label>
                    <div class="mt-2">
                        <input type="text" name="button_text" id="button_text" value="{{ old('button_text') }}"
                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6">
                    </div>
                    @error('button_text')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Button Link -->
                <div class="sm:col-span-3">
                    <label for="button_link" class="block text-sm font-medium leading-6 text-gray-900">Button Link</label>
                    <div class="mt-2">
                        <input type="text" name="button_link" id="button_link" value="{{ old('button_link') }}"
                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6">
                    </div>
                    @error('button_link')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Background Image -->
                <div class="sm:col-span-6">
                    <label for="bg_image" class="block text-sm font-medium leading-6 text-gray-900">Background Image</label>
                    <div class="mt-2">
                        <input type="file" name="bg_image" id="bg_image" accept="image/*"
                               class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                    </div>
                    <p class="mt-1 text-sm text-gray-500">PNG, JPG, GIF up to 2MB</p>
                    @error('bg_image')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Order -->
                <div class="sm:col-span-3">
                    <label for="order" class="block text-sm font-medium leading-6 text-gray-900">Order *</label>
                    <div class="mt-2">
                        <input type="number" name="order" id="order" value="{{ old('order', 0) }}" required min="0"
                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6">
                    </div>
                    @error('order')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Is Active -->
                <div class="sm:col-span-6">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="h-4 w-4 rounded border-gray-300 text-brand-primary focus:ring-brand-primary">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">Active</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
            <a href="{{ route('admin.hero-sliders.index') }}" class="text-sm font-semibold leading-6 text-gray-900">Cancel</a>
            <button type="submit" class="rounded-md bg-brand-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">
                Create Slider
            </button>
        </div>
    </div>
</form>
@endsection
