@extends('teamportal::layouts.app')

@section('title', 'Edit Task - ' . $project->name)

@section('content')
<div class="mb-8">
    <a href="{{ route('team.pm.projects.show', $project) }}" class="text-sm font-semibold text-brand-primary flex items-center gap-1 mb-2 hover:underline">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        Back to Project
    </a>
    <h1 class="text-3xl font-bold text-gray-900">Edit Task</h1>
    <p class="mt-2 text-sm text-gray-600">Update task details for {{ $project->name }}</p>
</div>

<div class="max-w-3xl">
    <form action="{{ route('team.pm.tasks.update', [$project, $task]) }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-6">
        @csrf
        @method('PUT')
        
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Task Title <span class="text-red-500">*</span></label>
            <input type="text" name="title" value="{{ old('title', $task->title) }}" required placeholder="e.g. Design Landing Page" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all outline-none text-sm">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Description</label>
            <textarea name="description" id="description" rows="10" placeholder="Detailed instructions for the assignee..." class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all outline-none text-sm">{{ old('description', $task->description) }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Assign To <span class="text-red-500">*</span></label>
                <select name="assigned_to" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all outline-none text-sm">
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('assigned_to', $task->assigned_to) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Priority</label>
                <select name="priority" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all outline-none text-sm">
                    <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>High</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Status</label>
                <select name="status" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all outline-none text-sm">
                    <option value="todo" {{ old('status', $task->status) == 'todo' ? 'selected' : '' }}>To Do</option>
                    <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="review" {{ old('status', $task->status) == 'review' ? 'selected' : '' }}>Review</option>
                    <option value="done" {{ old('status', $task->status) == 'done' ? 'selected' : '' }}>Done</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Due Date</label>
                <input type="date" name="due_date" value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all outline-none text-sm">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Milestone</label>
            <select name="milestone_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all outline-none text-sm">
                <option value="">No milestone</option>
                @foreach($project->milestones as $milestone)
                <option value="{{ $milestone->id }}" {{ old('milestone_id', $task->milestone_id) == $milestone->id ? 'selected' : '' }}>{{ $milestone->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="pt-6 flex justify-end">
            <button type="submit" class="px-8 py-3 bg-brand-primary text-white rounded-xl font-bold shadow-lg shadow-brand-primary/25 hover:shadow-brand-primary/40 hover:-translate-y-0.5 transition-all">
                Update Task
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
