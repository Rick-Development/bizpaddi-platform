@extends('admin::layouts.app')

@section('title', 'Edit Subsidiary')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Edit Subsidiary</h1>
</div>

<form action="{{ route('admin.subsidiaries.update', $subsidiary) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')
    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
        <div class="px-4 py-6 sm:p-8">
            <div class="grid max-w-4xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <div class="sm:col-span-4">
                    <label for="name" class="block text-sm font-medium text-gray-900">Company Name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $subsidiary->name) }}" required
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-4">
                    <label for="location" class="block text-sm font-medium text-gray-900">Location</label>
                    <input type="text" name="location" id="location" value="{{ old('location', $subsidiary->location) }}"
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('location')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-4">
                    <label for="website" class="block text-sm font-medium text-gray-900">Website URL</label>
                    <input type="url" name="website" id="website" value="{{ old('website', $subsidiary->website) }}"
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('website')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6">
                    <label for="description" class="block text-sm font-medium text-gray-900">Description *</label>
                    <textarea name="description" id="description" rows="4" required
                              class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">{{ old('description', $subsidiary->description) }}</textarea>
                    @error('description')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6">
                    <label for="logo" class="block text-sm font-medium text-gray-900">Company Logo</label>
                    @if($subsidiary->hasMedia('logo'))
                        <div class="mb-2">
                            <img src="{{ $subsidiary->getFirstMediaUrl('logo') }}" alt="Current Logo" class="h-16 w-16 rounded-full object-cover">
                        </div>
                    @endif
                    <input type="file" name="logo" id="logo" accept="image/*"
                           class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-brand-primary file:text-white hover:file:bg-red-700">
                    <p class="mt-1 text-sm text-gray-500">Upload to replace current logo</p>
                    @error('logo')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
            <a href="{{ route('admin.subsidiaries.index') }}" class="text-sm font-semibold text-gray-900">Cancel</a>
            <button type="submit" class="rounded-md bg-brand-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700">Update Subsidiary</button>
        </div>
    </div>
</form>
@endsection
