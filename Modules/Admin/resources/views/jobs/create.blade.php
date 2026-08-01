@extends('admin::layouts.app')

@section('title', 'Post Job')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Post New Job</h1>
</div>

<form action="{{ route('admin.jobs.store') }}" method="POST" class="space-y-6">
    @csrf
    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
        <div class="px-4 py-6 sm:p-8">
            <div class="grid max-w-4xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <div class="sm:col-span-4">
                    <label for="title" class="block text-sm font-medium text-gray-900">Job Title *</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('title')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="department" class="block text-sm font-medium text-gray-900">Department *</label>
                    <input type="text" name="department" id="department" value="{{ old('department') }}" required
                           placeholder="e.g., Engineering, Marketing"
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('department')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="location" class="block text-sm font-medium text-gray-900">Location *</label>
                    <input type="text" name="location" id="location" value="{{ old('location') }}" required
                           placeholder="e.g., Remote, New York, London"
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('location')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="type" class="block text-sm font-medium text-gray-900">Job Type *</label>
                    <select name="type" id="type" required
                            class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                        <option value="full-time" {{ old('type') === 'full-time' ? 'selected' : '' }}>Full Time</option>
                        <option value="part-time" {{ old('type') === 'part-time' ? 'selected' : '' }}>Part Time</option>
                        <option value="contract" {{ old('type') === 'contract' ? 'selected' : '' }}>Contract</option>
                        <option value="remote" {{ old('type') === 'remote' ? 'selected' : '' }}>Remote</option>
                    </select>
                    @error('type')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6">
                    <label for="description" class="block text-sm font-medium text-gray-900">Description *</label>
                    <textarea name="description" id="description" rows="6" required
                              class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">{{ old('description') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Overview of the role and responsibilities</p>
                    @error('description')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6">
                    <label for="requirements" class="block text-sm font-medium text-gray-900">Requirements *</label>
                    <textarea name="requirements" id="requirements" rows="6" required
                              class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">{{ old('requirements') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Skills, qualifications, and experience needed</p>
                    @error('requirements')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6">
                    <div class="relative flex gap-x-3">
                        <div class="flex h-6 items-center">
                            <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                   class="h-4 w-4 rounded border-gray-300 text-brand-primary focus:ring-brand-primary">
                        </div>
                        <div class="text-sm leading-6">
                            <label for="is_active" class="font-medium text-gray-900">Active Listing</label>
                            <p class="text-gray-500">Make this job visible on the careers page immediately.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-x-4 border-t border-gray-100 px-4 py-4 sm:px-8">
            <a href="{{ route('admin.jobs.index') }}" class="px-4 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">Cancel</a>
            <button type="submit" class="px-5 py-2.5 bg-brand-primary text-white rounded-xl text-sm font-medium hover:bg-brand-primary/90 shadow-lg shadow-brand-primary/30 transition-all hover:-translate-y-0.5">Post Job</button>
        </div>
    </div>
</form>
@endsection
