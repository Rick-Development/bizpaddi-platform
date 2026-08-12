@extends('teamportal::layouts.app')

@section('title', 'Send SMS')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('team.bdm.contacts.show', $contact) }}" class="text-gray-400 hover:text-gray-600">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Send SMS to {{ $contact->first_name ?: ($contact->phone ?: 'Contact') }}</h1>
            <p class="mt-1 text-sm text-gray-600">Send a text message directly to this contact's phone.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">{{ session('error') }}</div>
    @endif

    @if(!$contact->phone)
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-r-lg">
        <p class="text-sm text-yellow-700">
            This contact does not have a phone number.
            <a href="{{ route('team.bdm.contacts.edit', $contact) }}" class="font-medium underline hover:text-yellow-600">Update contact</a>
        </p>
    </div>
    @endif

    <form action="{{ route('team.bdm.contacts.process-sms', $contact) }}" method="POST" class="space-y-5">
        @csrf

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-5">

            {{-- To --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">To</label>
                <input type="text" value="{{ $contact->phone ?: 'No phone number' }}" disabled
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500">
            </div>

            {{-- Template picker --}}
            @if($templates->isNotEmpty())
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Use a Template <span class="text-gray-400 font-normal">(optional)</span></label>
                <select id="template-select" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                    <option value="">— Select a template —</option>
                    @foreach($templates as $tmpl)
                        <option value="{{ $tmpl->content }}">{{ $tmpl->name }}</option>
                    @endforeach
                </select>
            </div>
            @else
            <div class="text-sm text-gray-500 bg-gray-50 rounded-lg px-3 py-2 border border-dashed border-gray-200">
                No active SMS templates found.
                <a href="{{ route('team.bdm.sms-templates.create') }}" class="text-[#FB4C4B] hover:underline ml-1">Create one &rarr;</a>
            </div>
            @endif

            {{-- Message --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Message <span class="text-red-500">*</span></label>
                <textarea name="message" id="sms-message" rows="5" maxlength="160" required
                    {{ !$contact->phone ? 'disabled' : '' }}
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent resize-none"
                    placeholder="Type your SMS message here (max 160 characters)...">{{ old('message') }}</textarea>
                <p class="mt-1 text-xs text-gray-500">
                    Character count: <span id="char-count" class="font-medium text-gray-700">0</span> / 160
                </p>
            </div>

        </div>

        <div class="flex justify-end gap-x-3">
            <a href="{{ route('team.bdm.contacts.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" {{ !$contact->phone ? 'disabled' : '' }}
                class="inline-flex items-center px-4 py-2 bg-[#FB4C4B] text-white rounded-md text-sm font-medium hover:bg-[#D93837] disabled:opacity-50 disabled:cursor-not-allowed">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.43 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 01-.923 1.785A5.969 5.969 0 006 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337z" />
                </svg>
                Send SMS
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const textarea = document.getElementById('sms-message');
    const charCount = document.getElementById('char-count');
    const templateSelect = document.getElementById('template-select');

    function updateCount() {
        const len = textarea.value.length;
        charCount.textContent = len;
        charCount.className = len > 160 ? 'font-medium text-red-600' : 'font-medium text-gray-700';
    }

    if (textarea) {
        textarea.addEventListener('input', updateCount);
        updateCount();
    }

    if (templateSelect) {
        templateSelect.addEventListener('change', function () {
            const content = this.value;
            if (content && textarea) {
                textarea.value = content;
                updateCount();
                textarea.focus();
            }
        });
    }
});
</script>
@endsection
