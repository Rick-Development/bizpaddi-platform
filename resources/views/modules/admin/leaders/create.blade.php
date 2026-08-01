@extends('admin::layouts.app')

@section('title', 'Create Leader')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Add Team Leader</h1>
</div>

<form action="{{ route('admin.leaders.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
        <div class="px-4 py-6 sm:p-8">
            <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <div class="sm:col-span-4">
                    <label for="name" class="block text-sm font-medium text-gray-900">Name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-4">
                    <label for="position" class="block text-sm font-medium text-gray-900">Position *</label>
                    <input type="text" name="position" id="position" value="{{ old('position') }}" required
                           placeholder="e.g., CEO, CTO, Lead Developer"
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('position')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6">
                    <label for="bio" class="block text-sm font-medium text-gray-900">Bio</label>
                    <textarea name="bio" id="bio" rows="4"
                              class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">{{ old('bio') }}</textarea>
                    @error('bio')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="email" class="block text-sm font-medium text-gray-900">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('email')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="linkedin" class="block text-sm font-medium text-gray-900">LinkedIn URL</label>
                    <input type="url" name="linkedin" id="linkedin" value="{{ old('linkedin') }}"
                           placeholder="https://linkedin.com/in/..."
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('linkedin')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6">
                    <label for="photo" class="block text-sm font-medium text-gray-900">Photo</label>
                    <input type="file" name="photo" id="photo" accept="image/*"
                           class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50">
                    <p class="mt-1 text-sm text-gray-500">PNG, JPG up to 2MB</p>
                    @error('photo')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="order" class="block text-sm font-medium text-gray-900">Order *</label>
                    <input type="number" name="order" id="order" value="{{ old('order', 0) }}" required min="0"
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('order')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
            <a href="{{ route('admin.leaders.index') }}" class="text-sm font-semibold text-gray-900">Cancel</a>
            <button type="submit" class="rounded-md bg-brand-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700">Create Leader</button>
        </div>
    </div>
</form>
@endsection
