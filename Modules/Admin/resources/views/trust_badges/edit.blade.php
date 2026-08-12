<x-admin::layouts.master>
    <x-slot name="title">Edit Trust Badge</x-slot>

    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                Edit Trust Badge
            </h2>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0">
            <a href="{{ route('admin.trust-badges.index') }}" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                Cancel
            </a>
        </div>
    </div>

    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
        <form action="{{ route('admin.trust-badges.update', $trustBadge) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="px-4 py-6 sm:p-8">
                <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    
                    <div class="sm:col-span-4">
                        <label for="title" class="block text-sm font-medium leading-6 text-gray-900">Badge Title</label>
                        <div class="mt-2">
                            <input type="text" name="title" id="title" value="{{ old('title', $trustBadge->title) }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6" required>
                        </div>
                        @error('title')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="col-span-full">
                        <label for="image_path" class="block text-sm font-medium leading-6 text-gray-900">Badge Icon/Image</label>
                        @if($trustBadge->image_path)
                            <div class="mt-2 mb-4">
                                <p class="text-sm text-gray-500 mb-2">Current Image:</p>
                                <img src="{{ asset('storage/' . $trustBadge->image_path) }}" alt="{{ $trustBadge->title }}" class="h-20 w-auto object-contain bg-gray-100 rounded">
                            </div>
                        @endif
                        <div class="mt-2">
                            <input type="file" name="image_path" id="image_path" accept="image/*" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                            <p class="mt-1 text-sm text-gray-500">Leave blank to keep current image. PNG, JPG, GIF up to 5MB.</p>
                        </div>
                        @error('image_path')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="sort_order" class="block text-sm font-medium leading-6 text-gray-900">Sort Order</label>
                        <div class="mt-2">
                            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $trustBadge->sort_order) }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6">
                        </div>
                        @error('sort_order')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="sm:col-span-6">
                        <div class="relative flex gap-x-3">
                            <div class="flex h-6 items-center">
                                <input id="is_active" name="is_active" type="checkbox" value="1" class="h-4 w-4 rounded border-gray-300 text-brand-primary focus:ring-brand-primary" {{ $trustBadge->is_active ? 'checked' : '' }}>
                            </div>
                            <div class="text-sm leading-6">
                                <label for="is_active" class="font-medium text-gray-900">Active</label>
                                <p class="text-gray-500">If unchecked, this badge will not be displayed on the website.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
                <button type="submit" class="rounded-md bg-brand-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">Update</button>
            </div>
        </form>
    </div>
</x-admin::layouts.master>
