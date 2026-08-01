@extends('admin::layouts.app')

@section('title', 'Edit Industry')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Edit Industry</h1>
</div>

<form action="{{ route('admin.industries.update', $industry) }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')
    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
        <div class="px-4 py-6 sm:p-8">
            <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <div class="sm:col-span-4">
                    <label for="name" class="block text-sm font-medium text-gray-900">Industry Name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $industry->name) }}" required
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-4">
                    <label for="slug" class="block text-sm font-medium text-gray-900">Slug *</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $industry->slug) }}" required
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    <p class="mt-1 text-sm text-gray-500">URL-friendly version (lowercase, hyphens)</p>
                    @error('slug')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
            <a href="{{ route('admin.industries.index') }}" class="text-sm font-semibold text-gray-900">Cancel</a>
            <button type="submit" class="rounded-md bg-brand-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700">Update Industry</button>
        </div>
    </div>
</form>
@endsection
