@extends('admin::layouts.app')

@section('title', 'Create Task')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Create New Task</h1>
        <p class="mt-1 text-sm text-gray-600">Add a task to a project</p>
    </div>

    <form action="{{ route('admin.tasks.store') }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-6">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Task Title <span class="text-red-500">*</span></label>
            <input type="text" name="title" value="{{ old('title') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Project <span class="text-red-500">*</span></label>
                <select name="project_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                    <option value="">Select Project...</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ (old('project_id') == $project->id || ($selectedProject && $selectedProject->id == $project->id)) ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-sm font-medium text-gray-700">Milestone</label>
                    <a href="{{ route('admin.pm-milestones.index', ['project_id' => $selectedProject ? $selectedProject->id : null]) }}" id="manage-milestones-link" class="text-[10px] font-bold text-brand-primary uppercase hover:underline">Manage Milestones</a>
                </div>
                <select name="milestone_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                    <option value="">No Milestone</option>
                    @foreach($milestones as $milestone)
                        <option value="{{ $milestone->id }}" {{ old('milestone_id') == $milestone->id ? 'selected' : '' }}>{{ $milestone->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" id="description" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">{{ old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Assign To (Team User)</label>
                <select name="assigned_to" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                    <option value="">Unassigned</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                <input type="date" name="due_date" value="{{ old('due_date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                    <option value="todo" selected>To Do</option>
                    <option value="in_progress">In Progress</option>
                    <option value="review">Review</option>
                    <option value="done">Done</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                <select name="priority" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                    <option value="low">Low</option>
                    <option value="medium" selected>Medium</option>
                    <option value="high">High</option>
                    <option value="urgent">Urgent</option>
                </select>
            </div>
        </div>

        <div class="flex justify-end gap-x-3 pt-4">
            <a href="{{ route('admin.tasks.index', ['project_id' => $selectedProject ? $selectedProject->id : null]) }}" class="px-4 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2.5 bg-brand-primary text-white rounded-xl text-sm font-medium hover:bg-brand-primary/90 shadow-lg shadow-brand-primary/30 transition-all hover:-translate-y-0.5">
                Create Task
            </button>
        </div>
    </form>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .note-editor .note-toolbar { background: #f9fafb; border-bottom: 1px solid #e5e7eb; }
    .note-editor.note-frame { border: 1px solid #d1d5db; border-radius: 0.5rem; }
    .note-editor.note-airframe { border: 1px solid #d1d5db; border-radius: 0.5rem; }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        $('#description').summernote({
            placeholder: 'Description...',
            tabsize: 2,
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        $('select[name="project_id"]').on('change', function() {
            const projectId = $(this).val();
            const milestoneSelect = $('select[name="milestone_id"]');
            const manageLink = $('#manage-milestones-link');
            
            milestoneSelect.empty().append('<option value="">No Milestone</option>');
            
            if (projectId) {
                // Update manage link
                const urlTemplate = "{{ route('admin.pm-milestones.index', ['project_id' => ':id']) }}";
                manageLink.attr('href', urlTemplate.replace(':id', projectId));

                $.get(`/admin/pm-projects/${projectId}/milestones`, function(milestones) {
                    milestones.forEach(m => {
                        milestoneSelect.append(`<option value="${m.id}">${m.title}</option>`);
                    });
                });
            } else {
                manageLink.attr('href', "{{ route('admin.pm-milestones.index') }}");
            }
        });
    });
</script>
@endpush
