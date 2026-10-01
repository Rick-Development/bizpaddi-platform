@extends('teamportal::layouts.app')

@section('title', 'Send SMS')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Send SMS to {{ $contact->first_name ?: ($contact->phone ?: 'Contact') }}</h1>
        <p class="mt-1 text-sm text-gray-600">Send a text message directly to this contact's phone.</p>
    </div>

    @if(!$contact->phone)
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fa-solid fa-triangle-exclamation text-yellow-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">
                    This contact does not have a phone number associated with their profile. 
                    <a href="{{ route('team.bdm.contacts.edit', $contact) }}" class="font-medium underline hover:text-yellow-600">Update contact</a>
                </p>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('team.bdm.contacts.process-sms', $contact) }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-6">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">To</label>
            <input type="text" value="{{ $contact->phone ?: 'No phone number' }}" disabled class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Message <span class="text-red-500">*</span></label>
            <textarea name="message" rows="4" maxlength="160" required {{ !$contact->phone ? 'disabled' : '' }} class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent" placeholder="Type your SMS message here (max 160 characters)...">{{ old('message') }}</textarea>
            <p class="mt-2 text-xs text-gray-500">Character count: <span id="char-count">0</span> / 160</p>
        </div>

        <div class="flex justify-end gap-x-3 pt-4">
            <a href="{{ route('team.bdm.contacts.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" {{ !$contact->phone ? 'disabled' : '' }} class="inline-flex items-center px-4 py-2 bg-[#FB4C4B] text-white rounded-md text-sm font-medium hover:bg-[#D93837] disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fa-solid fa-comment-sms mr-2"></i>
                Send SMS
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.querySelector('textarea[name="message"]');
        const charCount = document.getElementById('char-count');
        
        if (textarea) {
            textarea.addEventListener('input', function() {
                charCount.textContent = this.value.length;
            });
            // Initial count
            charCount.textContent = textarea.value.length;
        }
    });
</script>
@endsection
