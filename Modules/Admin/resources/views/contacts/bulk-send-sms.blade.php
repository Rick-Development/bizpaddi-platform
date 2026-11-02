@extends('admin::layouts.app')

@section('title', 'Bulk Send SMS')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">Bulk SMS to {{ $contacts->count() }} Contacts</h1>
        <p class="mt-1 text-sm text-gray-400">Send text messages to all selected contacts.</p>
    </div>

    <form action="{{ route('admin.contacts.process-bulk-sms') }}" method="POST" class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl p-6 space-y-6">
        @csrf
        <input type="hidden" name="ids" value="{{ $contacts->pluck('id')->implode(',') }}">

        <div>
            <label class="block text-sm font-medium text-gray-300 mb-1">Recipients ({{ $contacts->whereNotNull('phone')->count() }} with valid phone numbers)</label>
            <div class="w-full px-3 py-2 border border-white/10 rounded-lg bg-white/[0.03] text-gray-400 text-sm max-h-24 overflow-y-auto">
                {{ $contacts->whereNotNull('phone')->pluck('phone')->implode(', ') ?: 'No phone numbers available' }}
            </div>
        </div>

        <div>
            <label for="template_id" class="block text-sm font-medium text-gray-300 mb-1">Use Template (Optional)</label>
            <select id="template_id" class="w-full px-3 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-[#0a1612] text-white border-white/10">
                <option value="">Select a template...</option>
                @foreach($templates ?? [] as $template)
                    <option value="{{ $template->id }}" data-content="{{ $template->content }}">{{ $template->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-300 mb-1">Message <span class="text-red-500">*</span></label>
            <textarea name="message" id="sms_message" rows="5" maxlength="160" required class="w-full px-3 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-[#0a1612] text-white border-white/10" placeholder="Type your SMS message here...">{{ old('message') }}</textarea>
            <p class="mt-2 text-xs text-gray-400">Character count: <span id="char-count">0</span> / 160</p>
        </div>

        <div class="flex justify-end gap-x-3 pt-4">
            <a href="{{ route('admin.contacts.index') }}" class="px-4 py-2 border border-white/10 rounded-md text-sm font-medium text-gray-300 hover:bg-white/[0.03]">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-md text-sm font-medium hover:bg-emerald-500 shadow-sm transition-all" {{ $contacts->whereNotNull('phone')->isEmpty() ? 'disabled' : '' }}>
                <i class="fa-solid fa-comment-sms mr-2"></i>
                Send Bulk SMS
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.getElementById('sms_message');
        const charCount = document.getElementById('char-count');
        const templateSelect = document.getElementById('template_id');
        
        function updateCount() {
            charCount.textContent = textarea.value.length;
        }

        if (textarea) {
            textarea.addEventListener('input', updateCount);
            updateCount();
        }

        if (templateSelect && textarea) {
            templateSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const content = selectedOption.getAttribute('data-content');
                
                if (content) {
                    textarea.value = content;
                    updateCount();
                }
            });
        }
    });
</script>
@endpush
@endsection
