@extends('teamportal::layouts.app')
@section('title', 'Report a Bug')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <a href="{{ route('team.qa.bugs.index') }}" class="text-sm text-gray-500 hover:text-gray-800 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Back to Bug Reports
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Report a Bug</h1>
        <p class="text-sm text-gray-500 mt-1">Document a bug you found during testing</p>
    </div>

    <form action="{{ route('team.qa.bugs.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Basic Info --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-5">
            <h2 class="text-sm font-bold text-gray-700 uppercase tracking-wide pb-2 border-b border-gray-100">Basic Information</h2>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Bug Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required
                    class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm focus:ring-brand-primary focus:border-brand-primary @error('title') border-red-400 @enderror"
                    placeholder="Short, descriptive summary of the bug">
                @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Severity <span class="text-red-500">*</span></label>
                    <select name="severity" required class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                        <option value="low" {{ old('severity') === 'low' ? 'selected' : '' }}>🟢 Low</option>
                        <option value="medium" {{ old('severity', 'medium') === 'medium' ? 'selected' : '' }}>🟡 Medium</option>
                        <option value="high" {{ old('severity') === 'high' ? 'selected' : '' }}>🟠 High</option>
                        <option value="critical" {{ old('severity') === 'critical' ? 'selected' : '' }}>🔴 Critical</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Priority <span class="text-red-500">*</span></label>
                    <select name="priority" required class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                        <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Project</label>
                    <select name="project_id" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                        <option value="">Select project…</option>
                        @foreach($projects as $proj)
                        <option value="{{ $proj->id }}" {{ old('project_id') == $proj->id ? 'selected' : '' }}>{{ $proj->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Related Task</label>
                    <select name="task_id" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                        <option value="">Select task…</option>
                        @foreach($tasks as $task)
                        <option value="{{ $task->id }}" {{ old('task_id') == $task->id ? 'selected' : '' }}>{{ $task->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Environment</label>
                    <input type="text" name="environment" value="{{ old('environment') }}"
                        placeholder="e.g. staging, production, local"
                        class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm focus:ring-brand-primary focus:border-brand-primary">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Browser</label>
                    <input type="text" name="browser" value="{{ old('browser') }}"
                        placeholder="e.g. Chrome 120, Safari 17"
                        class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm focus:ring-brand-primary focus:border-brand-primary">
                </div>
            </div>
        </div>

        {{-- Description --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-5">
            <h2 class="text-sm font-bold text-gray-700 uppercase tracking-wide pb-2 border-b border-gray-100">Bug Details</h2>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                <textarea name="description" rows="4" required
                    class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm focus:ring-brand-primary focus:border-brand-primary resize-none"
                    placeholder="Describe the bug in detail…">{{ old('description') }}</textarea>
                @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Steps to Reproduce</label>
                <textarea name="steps_to_reproduce" rows="4"
                    class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm focus:ring-brand-primary focus:border-brand-primary resize-none"
                    placeholder="1. Go to...&#10;2. Click on...&#10;3. Observe...">{{ old('steps_to_reproduce') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Expected Result</label>
                    <textarea name="expected_result" rows="3"
                        class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm focus:ring-brand-primary focus:border-brand-primary resize-none"
                        placeholder="What should have happened?">{{ old('expected_result') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Actual Result</label>
                    <textarea name="actual_result" rows="3"
                        class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm focus:ring-brand-primary focus:border-brand-primary resize-none"
                        placeholder="What actually happened?">{{ old('actual_result') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Screenshots --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
            <h2 class="text-sm font-bold text-gray-700 uppercase tracking-wide pb-2 border-b border-gray-100">Screenshots</h2>
            <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-brand-primary transition cursor-pointer" onclick="document.getElementById('screenshots').click()">
                <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                </svg>
                <p class="text-sm text-gray-500">Click to upload screenshots</p>
                <p class="text-xs text-gray-400 mt-1">PNG, JPG, GIF, WebP up to 5MB each</p>
                <input type="file" id="screenshots" name="screenshots[]" multiple accept="image/*" class="hidden" onchange="previewImages(this)">
            </div>
            <div id="screenshot-preview" class="grid grid-cols-4 gap-2 hidden"></div>
        </div>

        {{-- Submit --}}
        <div class="flex gap-3 justify-end">
            <a href="{{ route('team.qa.bugs.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-300 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">Cancel</a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-semibold shadow transition">Submit Bug Report</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function previewImages(input) {
    const preview = document.getElementById('screenshot-preview');
    preview.innerHTML = '';
    if (input.files && input.files.length) {
        preview.classList.remove('hidden');
        Array.from(input.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = e => {
                const div = document.createElement('div');
                div.className = 'relative';
                div.innerHTML = `<img src="${e.target.result}" class="w-full h-20 object-cover rounded-lg border border-gray-200">`;
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    } else {
        preview.classList.add('hidden');
    }
}
</script>
@endpush
