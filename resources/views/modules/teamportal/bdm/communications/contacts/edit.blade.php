@extends('teamportal::layouts.app')

@section('title', 'Edit Contact')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Contact</h1>
        <p class="mt-1 text-sm text-gray-600">Update contact details</p>
    </div>

    <form action="{{ route('team.bdm.contacts.update', $contact) }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                <input type="text" name="first_name" value="{{ old('first_name', $contact->first_name) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                <input type="text" name="last_name" value="{{ old('last_name', $contact->last_name) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
            <input type="email" name="email" value="{{ old('email', $contact->email) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone', $contact->phone) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
            </div>

            <div>
                <label for="company" class="block text-sm font-medium text-gray-700 mb-1">Company</label>
                <input type="text" name="company" id="company" value="{{ old('company', $contact->company) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                @error('company')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role / Designation</label>
                <input type="text" name="role" id="role" value="{{ old('role', $contact->role) }}" placeholder="e.g., CEO, Manager, Developer"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                @error('role')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                <option value="active" {{ old('status', $contact->status) == 'active' ? 'selected' : '' }}>Active</option>
                <option value="unsubscribed" {{ old('status', $contact->status) == 'unsubscribed' ? 'selected' : '' }}>Unsubscribed</option>
                <option value="bounced" {{ old('status', $contact->status) == 'bounced' ? 'selected' : '' }}>Bounced</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Subscribe to Lists</label>
            <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-200 rounded-lg p-3">
                @foreach($lists as $list)
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="list_ids[]" value="{{ $list->id }}" 
                        {{ $contact->lists->contains($list->id) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-[#FB4C4B] focus:ring-[#FB4C4B]">
                    <span class="text-sm text-gray-700">{{ $list->name }}</span>
                </label>
                @endforeach
                @if($lists->isEmpty())
                    <p class="text-sm text-gray-500 text-center py-2">No active contact lists found.</p>
                @endif
            </div>
        </div>

        <div class="flex justify-end gap-x-3 pt-4">
            <a href="{{ route('team.bdm.contacts.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-[#FB4C4B] text-white rounded-md text-sm font-medium hover:bg-[#D93837]">
                Update Contact
            </button>
        </div>
    </form>
</div>
@endsection
