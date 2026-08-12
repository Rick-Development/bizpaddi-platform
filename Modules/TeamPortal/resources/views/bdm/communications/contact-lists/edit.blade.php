@extends('teamportal::layouts.app')

@section('title', 'Edit Contact List')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Contact List</h1>
        <p class="mt-1 text-sm text-gray-600">Update list details</p>
    </div>

    <form action="{{ route('team.bdm.contact-lists.update', $contactList) }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">List Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $contactList->name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">{{ old('description', $contactList->description) }}</textarea>
        </div>

        <div>
            <label class="flex items-center space-x-2">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $contactList->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-[#FB4C4B] focus:ring-[#FB4C4B]">
                <span class="text-sm font-medium text-gray-700">Active</span>
            </label>
        </div>

        <div class="flex justify-end gap-x-3 pt-4">
            <a href="{{ route('team.bdm.contact-lists.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-[#FB4C4B] text-white rounded-md text-sm font-medium hover:bg-[#D93837]">
                Update List
            </button>
        </div>
    </form>
</div>
@endsection
