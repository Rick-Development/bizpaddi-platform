@extends('admin::layouts.app')
@section('title', 'Edit Bug #' . $bug->id)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Bug #{{ $bug->id }}</h1>
            <p class="text-sm text-gray-500 mt-1">Modify bug details, priority, severity or assignment</p>
        </div>
        <a href="{{ route('admin.bugs.show', $bug) }}" class="text-sm text-gray-500 hover:text-gray-900">
            Cancel
        </a>
    </div>

    <form action="{{ route('admin.bugs.update', $bug) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-6">
        @csrf @method('PUT')

        {{-- Title --}}
        <div>
            <label for="title" class="block text-sm font-semibold text-gray-700 mb-1">Bug Title <span class="text-red-500">*</span></label>
            <input type="text" name="title" id="title" value="{{ old('title', $bug->title) }}" required
                class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm focus:ring-brand-primary focus:border-brand-primary">
            @error('title')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Project & Task --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="project_id" class="block text-sm font-semibold text-gray-700 mb-1">Project</label>
                <select name="project_id" id="project_id" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                    <option value="">Select Project</option>
                    @foreach($projects as $project)
                    <option value="{{ $project->id }}" {{ old('project_id', $bug->project_id) == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
                @error('project_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="task_id" class="block text-sm font-semibold text-gray-700 mb-1">Linked Task</label>
                <select name="task_id" id="task_id" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                    <option value="">None / Standalone</option>
                    @foreach($tasks as $task)
                    <option value="{{ $task->id }}" {{ old('task_id', $bug->task_id) == $task->id ? 'selected' : '' }}>{{ $task->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Severity, Priority, Status --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label for="severity" class="block text-sm font-semibold text-gray-700 mb-1">Severity <span class="text-red-500">*</span></label>
                <select name="severity" id="severity" required class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                    <option value="low" {{ old('severity', $bug->severity) === 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ old('severity', $bug->severity) === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ old('severity', $bug->severity) === 'high' ? 'selected' : '' }}>High</option>
                    <option value="critical" {{ old('severity', $bug->severity) === 'critical' ? 'selected' : '' }}>Critical</option>
                </select>
            </div>

            <div>
                <label for="priority" class="block text-sm font-semibold text-gray-700 mb-1">Priority <span class="text-red-500">*</span></label>
                <select name="priority" id="priority" required class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                    <option value="low" {{ old('priority', $bug->priority) === 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ old('priority', $bug->priority) === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ old('priority', $bug->priority) === 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ old('priority', $bug->priority) === 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>

            <div>
                <label for="status" class="block text-sm font-semibold text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                <select name="status" id="status" required class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                    <option value="open" {{ old('status', $bug->status) === 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ old('status', $bug->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="resolved" {{ old('status', $bug->status) === 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ old('status', $bug->status) === 'closed' ? 'selected' : '' }}>Closed</option>
                    <option value="wont_fix" {{ old('status', $bug->status) === 'wont_fix' ? 'selected' : '' }}>Won't Fix</option>
                </select>
            </div>
        </div>

        {{-- Assignee & Deadline / Timeframe --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="assigned_to" class="block text-sm font-semibold text-gray-700 mb-1">Assign Developer / Team Member</label>
                <select name="assigned_to" id="assigned_to" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                    <option value="">Unassigned</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('assigned_to', $bug->assigned_to) == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ ucfirst($user->role ?? 'Team') }})</option>
                    @endforeach
                </select>
                @error('assigned_to')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="due_date" class="block text-sm font-semibold text-gray-700 mb-1">
                    Deadline / Timeframe <span class="text-xs font-normal text-gray-500">(Full Date & Time)</span>
                </label>
                <input type="datetime-local" name="due_date" id="due_date"
                    value="{{ old('due_date', $bug->due_date ? $bug->due_date->format('Y-m-d\TH:i') : '') }}"
                    class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                <p class="text-xs text-gray-400 mt-1">Specify target date and time for fixing this bug</p>
                @error('due_date')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Description --}}
        <div>
            <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
            <textarea name="description" id="description" rows="4" required class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:ring-brand-primary focus:border-brand-primary">{{ old('description', $bug->description) }}</textarea>
            @error('description')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Steps to reproduce --}}
        <div>
            <label for="steps_to_reproduce" class="block text-sm font-semibold text-gray-700 mb-1">Steps to Reproduce</label>
            <textarea name="steps_to_reproduce" id="steps_to_reproduce" rows="3" class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:ring-brand-primary focus:border-brand-primary font-mono text-xs">{{ old('steps_to_reproduce', $bug->steps_to_reproduce) }}</textarea>
        </div>

        {{-- Expected vs Actual --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="expected_result" class="block text-sm font-semibold text-gray-700 mb-1">Expected Result</label>
                <textarea name="expected_result" id="expected_result" rows="2" class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:ring-brand-primary focus:border-brand-primary">{{ old('expected_result', $bug->expected_result) }}</textarea>
            </div>
            <div>
                <label for="actual_result" class="block text-sm font-semibold text-gray-700 mb-1">Actual Result</label>
                <textarea name="actual_result" id="actual_result" rows="2" class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:ring-brand-primary focus:border-brand-primary">{{ old('actual_result', $bug->actual_result) }}</textarea>
            </div>
        </div>

        {{-- Resolution Notes --}}
        <div>
            <label for="resolution_notes" class="block text-sm font-semibold text-gray-700 mb-1">Resolution Notes</label>
            <textarea name="resolution_notes" id="resolution_notes" rows="2" placeholder="Details about fix, commit, or verification..." class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:ring-brand-primary focus:border-brand-primary">{{ old('resolution_notes', $bug->resolution_notes) }}</textarea>
        </div>

        {{-- Environment & Browser --}}
        @php
            $environments = ['Production', 'Staging', 'Testing / QA', 'Sandbox', 'Development / Local', 'UAT', 'Other'];
            $currentEnv = old('environment', $bug->environment);
            $browsers = ['Google Chrome', 'Safari', 'Mozilla Firefox', 'Microsoft Edge', 'Opera', 'Brave', 'Mobile Safari (iOS)', 'Mobile Chrome (Android)', 'Mobile App (iOS)', 'Mobile App (Android)', 'Other'];
            $currentBrowser = old('browser', $bug->browser);
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="environment" class="block text-sm font-semibold text-gray-700 mb-1">Environment</label>
                <select name="environment" id="environment"
                    class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                    <option value="">Select Environment…</option>
                    @foreach($environments as $env)
                    <option value="{{ $env }}" {{ strcasecmp($currentEnv ?? '', $env) === 0 ? 'selected' : '' }}>{{ $env }}</option>
                    @endforeach
                    @if($currentEnv && !in_array(strtolower($currentEnv), array_map('strtolower', $environments)))
                    <option value="{{ $currentEnv }}" selected>{{ $currentEnv }}</option>
                    @endif
                </select>
            </div>
            <div>
                <label for="browser" class="block text-sm font-semibold text-gray-700 mb-1">Browser / Device</label>
                <select name="browser" id="browser"
                    class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                    <option value="">Select Browser / Platform…</option>
                    @foreach($browsers as $br)
                    <option value="{{ $br }}" {{ strcasecmp($currentBrowser ?? '', $br) === 0 ? 'selected' : '' }}>{{ $br }}</option>
                    @endforeach
                    @if($currentBrowser && !in_array(strtolower($currentBrowser), array_map('strtolower', $browsers)))
                    <option value="{{ $currentBrowser }}" selected>{{ $currentBrowser }}</option>
                    @endif
                </select>
            </div>
        </div>

        {{-- Screenshots --}}
        <div>
            <label for="screenshots" class="block text-sm font-semibold text-gray-700 mb-1">Add More Screenshots</label>
            <input type="file" name="screenshots[]" id="screenshots" multiple accept="image/*"
                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.bugs.show', $bug) }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Cancel</a>
            <button type="submit" class="bg-brand-primary hover:bg-brand-primary/90 text-white font-semibold text-sm px-6 py-2.5 rounded-xl transition shadow-sm">
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
