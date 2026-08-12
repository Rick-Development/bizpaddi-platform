@extends('teamportal::layouts.app')

@section('title', 'Create SMS Template')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('team.bdm.sms-templates.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create SMS Template</h1>
            <p class="mt-1 text-sm text-gray-600">Create a reusable SMS message template</p>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('team.bdm.sms-templates.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Template Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent"
                    placeholder="e.g. Welcome Message, Follow-up SMS">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Message Content <span class="text-red-500">*</span></label>
                <textarea name="content" rows="5" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent resize-y"
                    placeholder="Write your SMS message here...">{{ old('content') }}</textarea>
                <p class="mt-1 text-xs text-gray-500">
                    Available variables: {{ '{{first_name}}' }}, {{ '{{last_name}}' }}, {{ '{{company}}' }}, {{ '{{phone}}' }} &bull;
                    <span id="char-count" class="font-medium text-gray-700">0</span> / 160 characters
                </p>
            </div>

            <div>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-[#FB4C4B] focus:ring-[#FB4C4B]">
                    <span class="text-sm font-medium text-gray-700">Active (available for use in campaigns and bulk sends)</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end gap-x-3">
            <a href="{{ route('team.bdm.sms-templates.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-[#FB4C4B] text-white rounded-md text-sm font-medium hover:bg-[#D93837]">
                Create Template
            </button>
        </div>
    </form>
</div>

<script>
    const textarea = document.querySelector('textarea[name="content"]');
    const counter = document.getElementById('char-count');
    function updateCount() {
        const len = textarea.value.length;
        counter.textContent = len;
        counter.className = len > 160 ? 'font-medium text-red-600' : 'font-medium text-gray-700';
    }
    textarea.addEventListener('input', updateCount);
    updateCount();
</script>
@endsection
