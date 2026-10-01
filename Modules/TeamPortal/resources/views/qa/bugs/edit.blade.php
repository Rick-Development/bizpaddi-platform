@extends('teamportal::layouts.app')
@section('title', 'Edit Bug Report')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <a href="{{ route('team.qa.bugs.show', $bug) }}" class="text-sm text-gray-500 hover:text-gray-800 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Back to Bug Report
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Edit Bug Report</h1>
    </div>

    <form action="{{ route('team.qa.bugs.update', $bug) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')

        {{-- Basic Info --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-5">
            <h2 class="text-sm font-bold text-gray-700 uppercase tracking-wide pb-2 border-b border-gray-100">Basic Information</h2>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Bug Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $bug->title) }}" required
                    class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm focus:ring-brand-primary focus:border-brand-primary">
                @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Severity</label>
                    <select name="severity" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                        @foreach(['low' => '🟢 Low','medium' => '🟡 Medium','high' => '🟠 High','critical' => '🔴 Critical'] as $val => $label)
                        <option value="{{ $val }}" {{ old('severity', $bug->severity) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Priority</label>
                    <select name="priority" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                        @foreach(['low','medium','high','urgent'] as $val)
                        <option value="{{ $val }}" {{ old('priority', $bug->priority) === $val ? 'selected' : '' }}>{{ ucfirst($val) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                        @foreach(['open' => 'Open','in_progress' => 'In Progress','resolved' => 'Resolved','closed' => 'Closed','wont_fix' => "Won't Fix"] as $val => $label)
                        <option value="{{ $val }}" {{ old('status', $bug->status) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Project</label>
                    <select name="project_id" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                        <option value="">Select project…</option>
                        @foreach($projects as $proj)
                        <option value="{{ $proj->id }}" {{ old('project_id', $bug->project_id) == $proj->id ? 'selected' : '' }}>{{ $proj->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Related Task</label>
                    <select name="task_id" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                        <option value="">Select task…</option>
                        @foreach($tasks as $task)
                        <option value="{{ $task->id }}" {{ old('task_id', $bug->task_id) == $task->id ? 'selected' : '' }}>{{ $task->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Environment</label>
                    <input type="text" name="environment" value="{{ old('environment', $bug->environment) }}"
                        class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm focus:ring-brand-primary focus:border-brand-primary">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Browser</label>
                    <input type="text" name="browser" value="{{ old('browser', $bug->browser) }}"
                        class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm focus:ring-brand-primary focus:border-brand-primary">
                </div>
            </div>
        </div>

        {{-- Details --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-5">
            <h2 class="text-sm font-bold text-gray-700 uppercase tracking-wide pb-2 border-b border-gray-100">Bug Details</h2>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                <textarea name="description" rows="4" required
                    class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm focus:ring-brand-primary focus:border-brand-primary resize-none">{{ old('description', $bug->description) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Steps to Reproduce</label>
                <textarea name="steps_to_reproduce" rows="4"
                    class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm focus:ring-brand-primary focus:border-brand-primary resize-none">{{ old('steps_to_reproduce', $bug->steps_to_reproduce) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Expected Result</label>
                    <textarea name="expected_result" rows="3"
                        class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm focus:ring-brand-primary focus:border-brand-primary resize-none">{{ old('expected_result', $bug->expected_result) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Actual Result</label>
                    <textarea name="actual_result" rows="3"
                        class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm focus:ring-brand-primary focus:border-brand-primary resize-none">{{ old('actual_result', $bug->actual_result) }}</textarea>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Resolution Notes</label>
                <textarea name="resolution_notes" rows="3"
                    class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm focus:ring-brand-primary focus:border-brand-primary resize-none"
                    placeholder="How was this resolved? (fill when marking as resolved)">{{ old('resolution_notes', $bug->resolution_notes) }}</textarea>
            </div>
        </div>

        {{-- Add more screenshots --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
            <h2 class="text-sm font-bold text-gray-700 uppercase tracking-wide pb-2 border-b border-gray-100">Add More Screenshots</h2>
            @if($bug->getMedia('screenshots')->count())
            <div class="grid grid-cols-4 gap-2 mb-3">
                @foreach($bug->getMedia('screenshots') as $media)
                <a href="{{ $media->getUrl() }}" target="_blank" class="block rounded-xl overflow-hidden border border-gray-200">
                    <img src="{{ $media->getUrl() }}" class="w-full h-16 object-cover">
                </a>
                @endforeach
            </div>
            @endif
            <div class="border-2 border-dashed border-gray-200 rounded-xl p-5 text-center cursor-pointer" onclick="document.getElementById('screenshots').click()">
                <p class="text-sm text-gray-500">Click to add more screenshots</p>
                <input type="file" id="screenshots" name="screenshots[]" multiple accept="image/*" class="hidden">
            </div>
        </div>

        <div class="flex gap-3 justify-end">
            <a href="{{ route('team.qa.bugs.show', $bug) }}" class="px-5 py-2.5 rounded-xl border border-gray-300 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">Cancel</a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-gray-900 hover:bg-black text-white text-sm font-semibold shadow transition">Save Changes</button>
        </div>
    </form>
</div>
@endsection
