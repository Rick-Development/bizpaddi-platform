@extends('teamportal::layouts.app')
@section('title', 'Report a Bug')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div>
        <a href="{{ route('team.qa.bugs.index') }}" class="text-sm text-gray-400 hover:text-white flex items-center gap-1.5 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Back to Bug Reports
        </a>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight mt-2">Report a Bug</h1>
        <p class="text-sm text-gray-400 mt-1">Document a bug you found during testing</p>
    </div>

    <form action="{{ route('team.qa.bugs.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Basic Info --}}
        <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-2xl border border-white/10 shadow-xl p-6 space-y-5">
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider pb-2 border-b border-white/10">Basic Information</h2>

            <div>
                <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Bug Title <span class="text-red-400">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required
                    class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white placeholder-gray-500 px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 @error('title') border-red-500 @enderror"
                    placeholder="Short, descriptive summary of the bug">
                @error('title') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Severity <span class="text-red-400">*</span></label>
                    <select name="severity" required class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500">
                        <option value="low" {{ old('severity') === 'low' ? 'selected' : '' }} class="bg-[#10231C] text-white">🟢 Low</option>
                        <option value="medium" {{ old('severity', 'medium') === 'medium' ? 'selected' : '' }} class="bg-[#10231C] text-white">🟡 Medium</option>
                        <option value="high" {{ old('severity') === 'high' ? 'selected' : '' }} class="bg-[#10231C] text-white">🟠 High</option>
                        <option value="critical" {{ old('severity') === 'critical' ? 'selected' : '' }} class="bg-[#10231C] text-white">🔴 Critical</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Priority <span class="text-red-400">*</span></label>
                    <select name="priority" required class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500">
                        <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }} class="bg-[#10231C] text-white">Low</option>
                        <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }} class="bg-[#10231C] text-white">Medium</option>
                        <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }} class="bg-[#10231C] text-white">High</option>
                        <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }} class="bg-[#10231C] text-white">Urgent</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Project</label>
                    <select name="project_id" class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500">
                        <option value="" class="bg-[#10231C] text-white">Select project…</option>
                        @foreach($projects as $proj)
                        <option value="{{ $proj->id }}" {{ old('project_id') == $proj->id ? 'selected' : '' }} class="bg-[#10231C] text-white">{{ $proj->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Related Task</label>
                    <select name="task_id" class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500">
                        <option value="" class="bg-[#10231C] text-white">Select task…</option>
                        @foreach($tasks as $task)
                        <option value="{{ $task->id }}" {{ old('task_id') == $task->id ? 'selected' : '' }} class="bg-[#10231C] text-white">{{ $task->title }}</option>
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
                        <option value="{{ $u->id }}" {{ old('assigned_to') == $u->id ? 'selected' : '' }} class="bg-[#10231C] text-white">
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
                        value="{{ old('due_date') }}"
                        class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500">
                    <p class="text-xs text-gray-400 mt-1">Specify target date and time for fixing this bug</p>
                    @error('due_date') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            @endif

            @php
                $environments = ['Production', 'Staging', 'Testing / QA', 'Sandbox', 'Development / Local', 'UAT', 'Other'];
                $browsers = ['Google Chrome', 'Safari', 'Mozilla Firefox', 'Microsoft Edge', 'Opera', 'Brave', 'Mobile Safari (iOS)', 'Mobile Chrome (Android)', 'Mobile App (iOS)', 'Mobile App (Android)', 'Other'];

                $ua = request()->header('User-Agent') ?? '';
                $detectedBrowser = 'Google Chrome';
                if (str_contains($ua, 'iPhone') || str_contains($ua, 'iPad') || str_contains($ua, 'iPod')) {
                    $detectedBrowser = 'Mobile Safari (iOS)';
                } elseif (str_contains($ua, 'Android')) {
                    $detectedBrowser = 'Mobile Chrome (Android)';
                } elseif (str_contains($ua, 'Edg')) {
                    $detectedBrowser = 'Microsoft Edge';
                } elseif (str_contains($ua, 'OPR') || str_contains($ua, 'Opera')) {
                    $detectedBrowser = 'Opera';
                } elseif (str_contains($ua, 'Firefox')) {
                    $detectedBrowser = 'Mozilla Firefox';
                } elseif (str_contains($ua, 'Chrome')) {
                    $detectedBrowser = 'Google Chrome';
                } elseif (str_contains($ua, 'Safari')) {
                    $detectedBrowser = 'Safari';
                }
                $selectedBrowser = old('browser', $detectedBrowser);
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="environment" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Environment</label>
                    <select name="environment" id="environment"
                        class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500">
                        <option value="" class="bg-[#10231C] text-white">Select Environment…</option>
                        @foreach($environments as $env)
                        <option value="{{ $env }}" {{ strcasecmp(old('environment') ?? 'Staging', $env) === 0 ? 'selected' : '' }} class="bg-[#10231C] text-white">{{ $env }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="browser" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Browser / Device</label>
                    <select name="browser" id="browser"
                        class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500">
                        <option value="" class="bg-[#10231C] text-white">Select Browser / Platform…</option>
                        @foreach($browsers as $br)
                        <option value="{{ $br }}" {{ strcasecmp($selectedBrowser, $br) === 0 ? 'selected' : '' }} class="bg-[#10231C] text-white">{{ $br }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Description --}}
        <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-2xl border border-white/10 shadow-xl p-6 space-y-5">
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider pb-2 border-b border-white/10">Bug Details</h2>

            <div>
                <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Description <span class="text-red-400">*</span></label>
                <textarea name="description" rows="4" required
                    class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white placeholder-gray-500 px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 resize-none"
                    placeholder="Describe the bug in detail…">{{ old('description') }}</textarea>
                @error('description') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Steps to Reproduce</label>
                <textarea name="steps_to_reproduce" rows="4"
                    class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white placeholder-gray-500 px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 resize-none"
                    placeholder="1. Go to...&#10;2. Click on...&#10;3. Observe...">{{ old('steps_to_reproduce') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Expected Result</label>
                    <textarea name="expected_result" rows="3"
                        class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white placeholder-gray-500 px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 resize-none"
                        placeholder="What should have happened?">{{ old('expected_result') }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Actual Result</label>
                    <textarea name="actual_result" rows="3"
                        class="w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white placeholder-gray-500 px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 resize-none"
                        placeholder="What actually happened?">{{ old('actual_result') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Screenshots --}}
        <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-2xl border border-white/10 shadow-xl p-6 space-y-4">
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider pb-2 border-b border-white/10">Screenshots</h2>
            <div class="border-2 border-dashed border-white/15 rounded-xl p-6 text-center hover:border-emerald-500/50 transition cursor-pointer bg-white/5" onclick="document.getElementById('screenshots').click()">
                <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                </svg>
                <p class="text-sm text-gray-300">Click to upload screenshots</p>
                <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF, WebP up to 5MB each</p>
                <input type="file" id="screenshots" name="screenshots[]" multiple accept="image/*" class="hidden" onchange="previewImages(this)">
            </div>
            <div id="screenshot-preview" class="grid grid-cols-4 gap-2 hidden"></div>
        </div>

        {{-- Submit --}}
        <div class="flex gap-3 justify-end">
            <a href="{{ route('team.qa.bugs.index') }}" class="px-5 py-2.5 rounded-xl border border-white/10 text-sm font-semibold text-gray-400 hover:text-white hover:bg-white/5 transition">Cancel</a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white text-sm font-bold shadow-lg shadow-red-950/50 transition active:scale-95">Submit Bug Report</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const browserSelect = document.getElementById('browser');
    if (browserSelect) {
        const ua = navigator.userAgent;
        let detected = 'Google Chrome';
        if (/iPad|iPhone|iPod/.test(ua)) {
            detected = 'Mobile Safari (iOS)';
        } else if (/Android/.test(ua)) {
            detected = 'Mobile Chrome (Android)';
        } else if (/Edg\//.test(ua)) {
            detected = 'Microsoft Edge';
        } else if (/OPR\/|Opera/.test(ua)) {
            detected = 'Opera';
        } else if (/Firefox\//.test(ua)) {
            detected = 'Mozilla Firefox';
        } else if (/Chrome\//.test(ua)) {
            detected = 'Google Chrome';
        } else if (/Safari\//.test(ua)) {
            detected = 'Safari';
        }

        @if(!old('browser'))
        for (let i = 0; i < browserSelect.options.length; i++) {
            if (browserSelect.options[i].value === detected) {
                browserSelect.selectedIndex = i;
                break;
            }
        }
        @endif
    }
});

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
                div.innerHTML = `<img src="${e.target.result}" class="w-full h-20 object-cover rounded-lg border border-white/10">`;
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
