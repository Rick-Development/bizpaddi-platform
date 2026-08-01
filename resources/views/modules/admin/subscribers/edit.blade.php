@extends('admin::layouts.app')

@section('title', 'Edit Subscriber')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Edit Subscriber</h1>
</div>

<form action="{{ route('admin.subscribers.update', $subscriber) }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')
    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
        <div class="px-4 py-6 sm:p-8">
            <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <div class="sm:col-span-4">
                    <label for="email" class="block text-sm font-medium text-gray-900">Email Address *</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $subscriber->email) }}" required
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('email')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-4">
                    <label for="name" class="block text-sm font-medium text-gray-900">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $subscriber->name) }}"
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6">
                    <div class="relative flex gap-x-3">
                        <div class="flex h-6 items-center">
                            <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', $subscriber->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 rounded border-gray-300 text-brand-primary focus:ring-brand-primary">
                        </div>
                        <div class="text-sm leading-6">
                            <label for="is_active" class="font-medium text-gray-900">Active Subscriber</label>
                            <p class="text-gray-500">Subscriber will receive newsletters and updates.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
            <a href="{{ route('admin.subscribers.index') }}" class="text-sm font-semibold text-gray-900">Cancel</a>
            <button type="submit" class="rounded-md bg-brand-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700">Update Subscriber</button>
        </div>
    </div>
</form>
@endsection
