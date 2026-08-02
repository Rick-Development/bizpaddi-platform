@extends('admin::layouts.app')

@section('title', 'Edit Task')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Task</h1>
            <p class="mt-1 text-sm text-gray-600">Update task details</p>
        </div>
        <a href="{{ route('admin.tasks.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
            &larr; Back to Board
        </a>
    </div>

    <form action="{{ route('admin.tasks.update', $task) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Task Title <span class="text-red-500">*</span></label>
            <input type="text" name="title" value="{{ old('title', $task->title) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Project <span class="text-red-500">*</span></label>
                <select name="project_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                    <option value="">Select Project...</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ old('project_id', $task->project_id) == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-sm font-medium text-gray-700">Milestone</label>
                    <a href="{{ route('admin.pm-milestones.index', ['project_id' => $task->project_id]) }}" id="manage-milestones-link" class="text-[10px] font-bold text-brand-primary uppercase hover:underline">Manage Milestones</a>
                </div>
                <select name="milestone_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                    <option value="">No Milestone</option>
                    @foreach($milestones as $milestone)
                        <option value="{{ $milestone->id }}" {{ old('milestone_id', $task->milestone_id) == $milestone->id ? 'selected' : '' }}>{{ $milestone->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" id="description" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">{{ old('description', $task->description) }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Assign To (Team User)</label>
                <select name="assigned_to" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                    <option value="">Unassigned</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('assigned_to', $task->assigned_to) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                <input type="date" name="due_date" value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Attachments (Images, Videos, Files)</label>
            <input type="file" name="attachments[]" id="file_upload" multiple class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent bg-gray-50">
            <p class="mt-1 text-xs text-gray-500">You can select multiple files to add. Max size per file: 20MB.</p>
            <ul id="file_list" class="mt-2 text-sm text-gray-700 space-y-1"></ul>
            
            <script>
                document.getElementById('file_upload').addEventListener('change', function(e) {
                    const list = document.getElementById('file_list');
                    list.innerHTML = '';
                    for (let i = 0; i < this.files.length; i++) {
                        const li = document.createElement('li');
                        li.className = 'flex items-center gap-2 bg-gray-100 px-2 py-1 rounded';
                        li.innerHTML = `<svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg> ${this.files[i].name}`;
                        list.appendChild(li);
                    }
                });
            </script>
            @if($task->getMedia('task_attachments')->count() > 0)
                <div class="mt-4">
                    <p class="text-sm font-medium text-gray-700 mb-2">Existing Attachments:</p>
                    <ul class="space-y-2">
                        @foreach($task->getMedia('task_attachments') as $media)
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                <a href="{{ $media->getUrl() }}" target="_blank" class="text-brand-primary hover:underline truncate">{{ $media->file_name }}</a>
                                <span class="ml-2 text-xs text-gray-400">({{ $media->human_readable_size }})</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                    <option value="todo" {{ old('status', $task->status) == 'todo' ? 'selected' : '' }}>To Do</option>
                    <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="review" {{ old('status', $task->status) == 'review' ? 'selected' : '' }}>Review</option>
                    <option value="done" {{ old('status', $task->status) == 'done' ? 'selected' : '' }}>Done</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                <select name="priority" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                    <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ old('priority', $task->priority) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>
        </div>

        <div class="flex justify-end gap-x-3 pt-4">
            <a href="{{ route('admin.tasks.index') }}" class="px-4 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2.5 bg-brand-primary text-white rounded-xl text-sm font-medium hover:bg-brand-primary/90 shadow-lg shadow-brand-primary/30 transition-all hover:-translate-y-0.5">
                Update Task
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
            const currentMilestoneId = "{{ $task->milestone_id }}";
            
            milestoneSelect.empty().append('<option value="">No Milestone</option>');
            
            if (projectId) {
                // Update manage link
                const urlTemplate = "{{ route('admin.pm-milestones.index', ['project_id' => ':id']) }}";
                manageLink.attr('href', urlTemplate.replace(':id', projectId));

                $.get(`/admin/pm-projects/${projectId}/milestones`, function(milestones) {
                    milestones.forEach(m => {
                        const selected = m.id == currentMilestoneId ? 'selected' : '';
                        milestoneSelect.append(`<option value="${m.id}" ${selected}>${m.title}</option>`);
                    });
                });
            } else {
                manageLink.attr('href', "{{ route('admin.pm-milestones.index') }}");
            }
        });
    });
</script>
@endpush
