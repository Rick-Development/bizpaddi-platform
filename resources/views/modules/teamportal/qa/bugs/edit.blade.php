@extends('teamportal::layouts.app')
@section('title', 'Edit Bug Report')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div>
        <a href="{{ route('team.qa.bugs.show', $bug) }}" class="text-sm text-gray-400 hover:text-white flex items-center gap-1.5 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Back to Bug Report
        </a>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight mt-2">Edit Bug Report</h1>
    </div>

    <form action="{{ route('team.qa.bugs.update', $bug) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')

        {{-- Basic Info --}}
        <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-2xl border border-white/10 shadow-xl p-6 space-y-5">
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider pb-2 border-b border-white/10">Basic Information</h2>

            <div>
                <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Bug Title <span class="text-red-400">*</span></label>
                <input type="text" name="title" value="{{ old('title', $bug->title) }}" required
                    class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500">
                @error('title') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Severity</label>
                    <select name="severity" class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500">
                        @foreach(['low' => '🟢 Low','medium' => '🟡 Medium','high' => '🟠 High','critical' => '🔴 Critical'] as $val => $label)
                        <option value="{{ $val }}" {{ old('severity', $bug->severity) === $val ? 'selected' : '' }} class="bg-[#10231C] text-white">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Priority</label>
                    <select name="priority" class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500">
                        @foreach(['low','medium','high','urgent'] as $val)
                        <option value="{{ $val }}" {{ old('priority', $bug->priority) === $val ? 'selected' : '' }} class="bg-[#10231C] text-white">{{ ucfirst($val) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Status</label>
                    <select name="status" class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500">
                        @foreach(['open' => 'Open','in_progress' => 'In Progress','resolved' => 'Resolved','closed' => 'Closed','wont_fix' => "Won't Fix"] as $val => $label)
                        <option value="{{ $val }}" {{ old('status', $bug->status) === $val ? 'selected' : '' }} class="bg-[#10231C] text-white">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Project</label>
                    <select name="project_id" class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500">
                        <option value="" class="bg-[#10231C] text-white">Select project…</option>
                        @foreach($projects as $proj)
                        <option value="{{ $proj->id }}" {{ old('project_id', $bug->project_id) == $proj->id ? 'selected' : '' }} class="bg-[#10231C] text-white">{{ $proj->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Related Task</label>
                    <select name="task_id" class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500">
                        <option value="" class="bg-[#10231C] text-white">Select task…</option>
                        @foreach($tasks as $task)
                        <option value="{{ $task->id }}" {{ old('task_id', $bug->task_id) == $task->id ? 'selected' : '' }} class="bg-[#10231C] text-white">{{ $task->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @if(auth()->user()->isAdmin() || auth()->user()->isProjectManager())
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Assign Developer / Team Member</label>
                    <select name="assigned_to" class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500">
                        <option value="" class="bg-[#10231C] text-white">Unassigned</option>
                        @foreach($assignableUsers as $u)
                        <option value="{{ $u->id }}" {{ old('assigned_to', $bug->assigned_to) == $u->id ? 'selected' : '' }} class="bg-[#10231C] text-white">
                            {{ $u->name }} ({{ ucfirst(str_replace('_', ' ', $u->role ?? 'Team')) }})
                        </option>
                        @endforeach
                    </select>
                    @error('assigned_to') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="due_date" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">
                        Deadline / Timeframe <span class="text-xs font-normal text-gray-400">(Date & Time)</span>
                    </label>
                    <input type="datetime-local" name="due_date" id="due_date"
                        value="{{ old('due_date', $bug->due_date ? $bug->due_date->format('Y-m-d\TH:i') : '') }}"
                        class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500">
                    <p class="text-xs text-gray-400 mt-1">Specify target date and time for fixing this bug</p>
                    @error('due_date') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            @endif

            @php
                $environments = ['Production', 'Staging', 'Testing / QA', 'Sandbox', 'Development / Local', 'UAT', 'Other'];
                $currentEnv = old('environment', $bug->environment);
                $browsers = ['Google Chrome', 'Safari', 'Mozilla Firefox', 'Microsoft Edge', 'Opera', 'Brave', 'Mobile Safari (iOS)', 'Mobile Chrome (Android)', 'Mobile App (iOS)', 'Mobile App (Android)', 'Other'];
                $currentBrowser = old('browser', $bug->browser);
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="environment" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Environment</label>
                    <select name="environment" id="environment"
                        class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500">
                        <option value="" class="bg-[#10231C] text-white">Select Environment…</option>
                        @foreach($environments as $env)
                        <option value="{{ $env }}" {{ strcasecmp($currentEnv ?? '', $env) === 0 ? 'selected' : '' }} class="bg-[#10231C] text-white">{{ $env }}</option>
                        @endforeach
                        @if($currentEnv && !in_array(strtolower($currentEnv), array_map('strtolower', $environments)))
                        <option value="{{ $currentEnv }}" selected class="bg-[#10231C] text-white">{{ $currentEnv }}</option>
                        @endif
                    </select>
                </div>
                <div>
                    <label for="browser" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Browser / Device</label>
                    <select name="browser" id="browser"
                        class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500">
                        <option value="" class="bg-[#10231C] text-white">Select Browser / Platform…</option>
                        @foreach($browsers as $br)
                        <option value="{{ $br }}" {{ strcasecmp($currentBrowser ?? '', $br) === 0 ? 'selected' : '' }} class="bg-[#10231C] text-white">{{ $br }}</option>
                        @endforeach
                        @if($currentBrowser && !in_array(strtolower($currentBrowser), array_map('strtolower', $browsers)))
                        <option value="{{ $currentBrowser }}" selected class="bg-[#10231C] text-white">{{ $currentBrowser }}</option>
                        @endif
                    </select>
                </div>
            </div>
        </div>

        {{-- Details --}}
        <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-2xl border border-white/10 shadow-xl p-6 space-y-5">
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider pb-2 border-b border-white/10">Bug Details</h2>

            <div>
                <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Description <span class="text-red-400">*</span></label>
                <textarea name="description" rows="4" required
                    class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 resize-none">{{ old('description', $bug->description) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Steps to Reproduce</label>
                <textarea name="steps_to_reproduce" rows="4"
                    class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 resize-none">{{ old('steps_to_reproduce', $bug->steps_to_reproduce) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Expected Result</label>
                    <textarea name="expected_result" rows="3"
                        class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 resize-none">{{ old('expected_result', $bug->expected_result) }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Actual Result</label>
                    <textarea name="actual_result" rows="3"
                        class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 resize-none">{{ old('actual_result', $bug->actual_result) }}</textarea>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Resolution Notes</label>
                <textarea name="resolution_notes" rows="3"
                    class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 resize-none"
                    placeholder="How was this resolved? (fill when marking as resolved)">{{ old('resolution_notes', $bug->resolution_notes) }}</textarea>
            </div>
        </div>

        {{-- Add more screenshots --}}
        <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-2xl border border-white/10 shadow-xl p-6 space-y-4">
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider pb-2 border-b border-white/10">Add More Screenshots</h2>
            @if($bug->getMedia('screenshots')->count())
            <div class="grid grid-cols-4 gap-2 mb-3">
                @foreach($bug->getMedia('screenshots') as $media)
                <a href="{{ $media->getUrl() }}" target="_blank" class="block rounded-xl overflow-hidden border border-white/10 hover:border-emerald-500/40">
                    <img src="{{ $media->getUrl() }}" class="w-full h-16 object-cover">
                </a>
                @endforeach
            </div>
            @endif
            <div class="border-2 border-dashed border-white/15 rounded-xl p-5 text-center cursor-pointer hover:border-emerald-500/50 bg-white/5 transition" onclick="document.getElementById('screenshots').click()">
                <p class="text-sm text-gray-300">Click to add more screenshots</p>
                <input type="file" id="screenshots" name="screenshots[]" multiple accept="image/*" class="hidden">
            </div>
        </div>

        <div class="flex gap-3 justify-end">
            <a href="{{ route('team.qa.bugs.show', $bug) }}" class="px-5 py-2.5 rounded-xl border border-white/10 text-sm font-semibold text-gray-400 hover:text-white hover:bg-white/5 transition">Cancel</a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold shadow-lg shadow-emerald-950/50 transition active:scale-95">Save Changes</button>
        </div>
    </form>
</div>
@endsection
