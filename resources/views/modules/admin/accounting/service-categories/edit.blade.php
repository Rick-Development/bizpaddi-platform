@extends('admin::layouts.app')

@section('title', 'Edit Service Category')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-x-4">
        <a href="{{ route('admin.accounting.service-categories.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Service Category</h1>
            <p class="mt-1 text-sm text-gray-600">Update category details</p>
        </div>
    </div>
</div>

<div class="max-w-2xl">
    <form action="{{ route('admin.accounting.service-categories.update', $serviceCategory) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-sm border border-gray-200">
        @csrf
        @method('PUT')
        
        <div class="p-6 space-y-6">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Category Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $serviceCategory->name) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-colors @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image -->
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Category Image</label>
                
                @if($serviceCategory->image)
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $serviceCategory->image) }}" alt="{{ $serviceCategory->name }}" class="h-32 w-auto object-cover rounded-lg border border-gray-200">
                    </div>
                @endif

                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-brand-primary transition-colors">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-brand-primary hover:text-brand-secondary focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-brand-primary">
                                <span>Upload a new file</span>
                                <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                    </div>
                </div>
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-colors @error('description') border-red-500 @enderror">{{ old('description', $serviceCategory->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Color -->
            <div>
                <label for="color" class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                <div class="flex items-center gap-x-3">
                    <input type="color" name="color" id="color" value="{{ old('color', $serviceCategory->color) }}"
                        class="h-10 w-20 rounded border border-gray-300 cursor-pointer">
                    <span class="text-sm text-gray-600">Choose a color to identify this category</span>
                </div>
                @error('color')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Sort Order -->
            <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $serviceCategory->sort_order) }}" min="0"
                    class="w-32 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-colors @error('sort_order') border-red-500 @enderror">
                <p class="mt-1 text-sm text-gray-500">Lower numbers appear first</p>
                @error('sort_order')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-lg flex items-center justify-end gap-x-3">
            <a href="{{ route('admin.accounting.service-categories.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-brand-primary rounded-lg hover:bg-brand-primary/90 transition-colors">
                Update Category
            </button>
        </div>
    </form>
</div>
@endsection
