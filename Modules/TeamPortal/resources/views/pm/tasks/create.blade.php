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
    <form action="{{ route('team.pm.tasks.store', $project) }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-6">
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
                    <option value="{{ $milestone->id }}">{{ $milestone->name }}</option>
                    @endforeach
                </select>
            </div>
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
