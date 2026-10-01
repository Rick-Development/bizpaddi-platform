@extends('admin::layouts.app')
@section('title', 'Log New Bug Report')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Log New Bug Report</h1>
            <p class="text-sm text-gray-500 mt-1">Create a QA bug report for team investigation and tracking</p>
        </div>
        <a href="{{ route('admin.bugs.index') }}" class="text-sm text-gray-500 hover:text-gray-900">
            Cancel
        </a>
    </div>

    <form action="{{ route('admin.bugs.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-6">
        @csrf

        {{-- Title --}}
        <div>
            <label for="title" class="block text-sm font-semibold text-gray-700 mb-1">Bug Title <span class="text-red-500">*</span></label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" required placeholder="e.g. Blank home screen on iOS mobile app"
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
                    <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
                @error('project_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="task_id" class="block text-sm font-semibold text-gray-700 mb-1">Linked Task</label>
                <select name="task_id" id="task_id" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                    <option value="">None / Standalone</option>
                    @foreach($tasks as $task)
                    <option value="{{ $task->id }}" {{ old('task_id') == $task->id ? 'selected' : '' }}>{{ $task->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Severity, Priority, Status, Assignee --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label for="severity" class="block text-sm font-semibold text-gray-700 mb-1">Severity <span class="text-red-500">*</span></label>
                <select name="severity" id="severity" required class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                    <option value="low" {{ old('severity') === 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ old('severity', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ old('severity') === 'high' ? 'selected' : '' }}>High</option>
                    <option value="critical" {{ old('severity') === 'critical' ? 'selected' : '' }}>Critical</option>
                </select>
            </div>

            <div>
                <label for="priority" class="block text-sm font-semibold text-gray-700 mb-1">Priority <span class="text-red-500">*</span></label>
                <select name="priority" id="priority" required class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                    <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>

            <div>
                <label for="status" class="block text-sm font-semibold text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                <select name="status" id="status" required class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                    <option value="open" {{ old('status', 'open') === 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="resolved" {{ old('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ old('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>

            <div>
                <label for="assigned_to" class="block text-sm font-semibold text-gray-700 mb-1">Assign Developer</label>
                <select name="assigned_to" id="assigned_to" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                    <option value="">Unassigned</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Description --}}
        <div>
            <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
            <textarea name="description" id="description" rows="4" required placeholder="Detailed description of the issue..."
                class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:ring-brand-primary focus:border-brand-primary">{{ old('description') }}</textarea>
            @error('description')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Steps to reproduce --}}
        <div>
            <label for="steps_to_reproduce" class="block text-sm font-semibold text-gray-700 mb-1">Steps to Reproduce</label>
            <textarea name="steps_to_reproduce" id="steps_to_reproduce" rows="3" placeholder="1. Go to login page&#10;2. Click on submit with empty form&#10;3. Observe crash"
                class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:ring-brand-primary focus:border-brand-primary font-mono text-xs">{{ old('steps_to_reproduce') }}</textarea>
        </div>

        {{-- Expected vs Actual --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="expected_result" class="block text-sm font-semibold text-gray-700 mb-1">Expected Result</label>
                <textarea name="expected_result" id="expected_result" rows="2" placeholder="What should happen..."
                    class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:ring-brand-primary focus:border-brand-primary">{{ old('expected_result') }}</textarea>
            </div>
            <div>
                <label for="actual_result" class="block text-sm font-semibold text-gray-700 mb-1">Actual Result</label>
                <textarea name="actual_result" id="actual_result" rows="2" placeholder="What actually happened..."
                    class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:ring-brand-primary focus:border-brand-primary">{{ old('actual_result') }}</textarea>
            </div>
        </div>

        {{-- Environment & Browser --}}
        @php
            $environments = ['Production', 'Staging', 'Testing / QA', 'Sandbox', 'Development / Local', 'UAT', 'Other'];
            $browsers = ['Google Chrome', 'Safari', 'Mozilla Firefox', 'Microsoft Edge', 'Opera', 'Brave', 'Mobile Safari (iOS)', 'Mobile Chrome (Android)', 'Mobile App (iOS)', 'Mobile App (Android)', 'Other'];
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="environment" class="block text-sm font-semibold text-gray-700 mb-1">Environment</label>
                <select name="environment" id="environment"
                    class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                    <option value="">Select Environment…</option>
                    @foreach($environments as $env)
                    <option value="{{ $env }}" {{ strcasecmp(old('environment') ?? 'Staging', $env) === 0 ? 'selected' : '' }}>{{ $env }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="browser" class="block text-sm font-semibold text-gray-700 mb-1">Browser / Device</label>
                <select name="browser" id="browser"
                    class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm bg-white focus:ring-brand-primary focus:border-brand-primary">
                    <option value="">Select Browser / Platform…</option>
                    @foreach($browsers as $br)
                    <option value="{{ $br }}" {{ strcasecmp(old('browser') ?? '', $br) === 0 ? 'selected' : '' }}>{{ $br }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Screenshots --}}
        <div>
            <label for="screenshots" class="block text-sm font-semibold text-gray-700 mb-1">Screenshots / Media</label>
            <input type="file" name="screenshots[]" id="screenshots" multiple accept="image/*"
                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.bugs.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Cancel</a>
            <button type="submit" class="bg-brand-primary hover:bg-brand-primary/90 text-white font-semibold text-sm px-6 py-2.5 rounded-xl transition shadow-sm">
                Create Bug Report
            </button>
        </div>
    </form>
</div>
@endsection
