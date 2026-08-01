@extends('admin::layouts.app')

@section('title', 'Create Press Release')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Create Press Release</h1>
    <p class="mt-2 text-sm text-gray-700">Add a new investor press release or announcement</p>
</div>

<form action="{{ route('admin.investor.press-releases.store') }}" method="POST" class="space-y-6">
    @csrf
    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
        <div class="px-4 py-6 sm:p-8">
            <div class="grid max-w-4xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <div class="sm:col-span-6">
                    <label for="title" class="block text-sm font-medium text-gray-900">Title *</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('title')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-4">
                    <label for="slug" class="block text-sm font-medium text-gray-900">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    <p class="mt-1 text-sm text-gray-500">Leave empty to auto-generate from title</p>
                    @error('slug')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="category" class="block text-sm font-medium text-gray-900">Category *</label>
                    <select name="category" id="category" required
                            class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                        <option value="">Select Category</option>
                        <option value="earnings" {{ old('category') == 'earnings' ? 'selected' : '' }}>Earnings</option>
                        <option value="corporate" {{ old('category') == 'corporate' ? 'selected' : '' }}>Corporate</option>
                        <option value="product" {{ old('category') == 'product' ? 'selected' : '' }}>Product</option>
                        <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('category')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="published_at" class="block text-sm font-medium text-gray-900">Published Date</label>
                    <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}"
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('published_at')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6">
                    <label for="excerpt" class="block text-sm font-medium text-gray-900">Excerpt</label>
                    <textarea name="excerpt" id="excerpt" rows="3"
                              class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">{{ old('excerpt') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Brief summary for previews and listings</p>
                    @error('excerpt')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6">
                    <label for="content" class="block text-sm font-medium text-gray-900">Content *</label>
                    <textarea name="content" id="content" rows="15" required
                              class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">{{ old('content') }}</textarea>
                    @error('content')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6 border-t border-gray-200 pt-6">
                    <h3 class="text-base font-semibold text-gray-900 mb-4">Options</h3>
                </div>

                <div class="sm:col-span-6">
                    <div class="flex items-start">
                        <div class="flex h-6 items-center">
                            <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                                   class="h-4 w-4 rounded border-gray-300 text-brand-primary focus:ring-brand-primary">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_featured" class="font-medium text-gray-900">Featured Release</label>
                            <p class="text-gray-500">Highlight this press release on the investor relations page</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
            <a href="{{ route('admin.investor.press-releases.index') }}" class="text-sm font-semibold text-gray-900">Cancel</a>
            <button type="submit" class="rounded-md bg-brand-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700">Create Press Release</button>
        </div>
    </div>
</form>
@endsection
