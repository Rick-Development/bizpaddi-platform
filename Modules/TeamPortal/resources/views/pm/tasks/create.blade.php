@extends('teamportal::layouts.app')

@section('title', 'Assign New Task - ' . $project->name)

@section('content')
<div class="mb-8">
    <a href="{{ route('team.pm.projects.show', $project) }}" class="text-sm font-semibold text-brand-primary flex items-center gap-1 mb-2 hover:underline">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        Back to Project
    </a>
    <h1 class="text-3xl font-bold text-gray-900">Assign New Task</h1>
    <p class="mt-2 text-sm text-gray-600">Create and assign a task to a team member for {{ $project->name }}</p>
</div>

<div class="max-w-3xl">
    <form action="{{ route('team.pm.tasks.store', $project) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-6">
        @csrf
        
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Task Title <span class="text-red-500">*</span></label>
            <input type="text" name="title" required placeholder="e.g. Design Landing Page" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all outline-none text-sm">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Description</label>
            <textarea name="description" id="description" rows="10" placeholder="Detailed instructions for the assignee..." class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all outline-none text-sm">{{ old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Assign To <span class="text-red-500">*</span></label>
                <select name="assigned_to" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all outline-none text-sm">
                    @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Priority</label>
                <select name="priority" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all outline-none text-sm">
                    <option value="low">Low</option>
                    <option value="medium" selected>Medium</option>
                    <option value="high">High</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Due Date</label>
                <input type="date" name="due_date" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all outline-none text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Milestone</label>
                <select name="milestone_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all outline-none text-sm">
                    <option value="">No milestone</option>
                    @foreach($project->milestones as $milestone)
                    <option value="{{ $milestone->id }}">{{ $milestone->title ?? $milestone->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Attachments (Images, Videos, Files)</label>
            <label for="file_upload" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-gray-50 transition-colors">
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                    <svg class="w-8 h-8 mb-4 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                    </svg>
                    <p class="mb-2 text-sm text-gray-500 font-semibold">Click to upload or drag and drop files here</p>
                    <p class="text-xs text-gray-400">You can select multiple files to add. Max size per file: 20MB.</p>
                </div>
                <input id="file_upload" name="attachments[]" type="file" multiple class="hidden" onchange="updateFileList(this)" />
            </label>
            <ul id="file_list" class="mt-2 text-sm text-gray-700 space-y-1 w-full text-left"></ul>
            
            <script>
                function updateFileList(input) {
                    const list = document.getElementById('file_list');
                    list.innerHTML = '';
                    for (let i = 0; i < input.files.length; i++) {
                        const li = document.createElement('li');
                        li.className = 'flex items-center gap-2 bg-white border border-gray-100 px-3 py-2 rounded-lg text-xs shadow-sm';
                        li.innerHTML = `📎 ${input.files[i].name}`;
                        list.appendChild(li);
                    }
                }
            </script>
        </div>

        <div class="pt-6 flex justify-end">
            <button type="submit" class="px-8 py-3 bg-brand-primary text-white rounded-xl font-bold shadow-lg shadow-brand-primary/25 hover:shadow-brand-primary/40 hover:-translate-y-0.5 transition-all">
                Assign Task
            </button>
        </div>
    </form>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .note-editor.note-frame { border: 1px solid #e5e7eb !important; border-radius: 0.75rem !important; overflow: hidden; }
    .note-toolbar { background-color: #f9fafb !important; border-bottom: 1px solid #e5e7eb !important; }
    .note-editable { background-color: #f9fafb !important; min-height: 300px; }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
$(document).ready(function() {
    $('#description').summernote({
        placeholder: 'Detailed instructions for the assignee...',
        tabsize: 2,
        height: 300,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });
});
</script>
@endpush
