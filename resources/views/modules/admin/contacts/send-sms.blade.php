@extends('admin::layouts.app')

@section('title', 'Send SMS')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">Send SMS to {{ $contact->first_name ?: ($contact->phone ?: 'Contact') }}</h1>
        <p class="mt-1 text-sm text-gray-400">Send a text message directly to this contact's phone.</p>
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
                    <a href="{{ route('admin.contacts.edit', $contact) }}" class="font-medium underline hover:text-yellow-600">Update contact</a>
                </p>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('admin.contacts.process-sms', $contact) }}" method="POST" class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl p-6 space-y-6">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-300 mb-1">To</label>
            <input type="text" value="{{ $contact->phone ?: 'No phone number' }}" disabled class="w-full px-3 py-2 border border-white/10 rounded-lg bg-white/[0.03] text-gray-400">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-300 mb-1">Message <span class="text-red-500">*</span></label>
            <textarea name="message" rows="4" maxlength="160" required {{ !$contact->phone ? 'disabled' : '' }} class="w-full px-3 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="Type your SMS message here (max 160 characters)...">{{ old('message') }}</textarea>
            <p class="mt-2 text-xs text-gray-400">Character count: <span id="char-count">0</span> / 160</p>
        </div>

        <div class="flex justify-end gap-x-3 pt-4">
            <a href="{{ route('admin.contacts.index') }}" class="px-4 py-2 border border-white/10 rounded-md text-sm font-medium text-gray-300 hover:bg-white/[0.03]">
                Cancel
            </a>
            <button type="submit" {{ !$contact->phone ? 'disabled' : '' }} class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-md text-sm font-medium hover:bg-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed">
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
