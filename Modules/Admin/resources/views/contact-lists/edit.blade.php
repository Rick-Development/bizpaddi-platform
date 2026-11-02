@extends('admin::layouts.app')

@section('title', 'Edit Contact List')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">Edit Contact List</h1>
        <p class="mt-1 text-sm text-gray-400">Update list details</p>
    </div>

    <form action="{{ route('admin.contact-lists.update', $contactList) }}" method="POST" class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl p-6 space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-300 mb-1">List Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $contactList->name) }}" required class="w-full px-3 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-300 mb-1">Description</label>
            <textarea name="description" rows="3" class="w-full px-3 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-[#0a1612] text-white border-white/10">{{ old('description', $contactList->description) }}</textarea>
        </div>

        <div>
            <label class="flex items-center space-x-2">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $contactList->is_active) ? 'checked' : '' }} class="rounded border-white/10 text-emerald-400 focus:ring-emerald-500">
                <span class="text-sm font-medium text-gray-300">Active</span>
            </label>
        </div>

        <div class="flex justify-end gap-x-3 pt-4">
            <a href="{{ route('admin.contact-lists.index') }}" class="px-4 py-2 border border-white/10 rounded-md text-sm font-medium text-gray-300 hover:bg-white/[0.03]">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-md text-sm font-medium hover:bg-emerald-500">
                Update List
            </button>
        </div>
    </form>
</div>
@endsection
