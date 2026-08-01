@extends('teamportal::layouts.app')

@section('title', 'Submit Daily Report')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">Submit New Report</h2>
            <p class="mt-2 text-sm text-gray-500">Log your daily progress, plans, and challenges.</p>
        </div>
    </div>

    <form action="{{ route('team.reports.store') }}" method="POST" class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl p-8">
        @csrf
        <div class="space-y-8">
            <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <div class="sm:col-span-2">
                    <label for="hours_worked" class="block text-sm font-medium leading-6 text-gray-900">Hours Worked</label>
                    <div class="mt-2">
                        <input type="number" step="0.5" name="hours_worked" id="hours_worked" class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6" placeholder="e.g. 8.0" required>
                    </div>
                </div>
            </div>

            <div class="col-span-full">
                <label for="tasks_completed" class="block text-sm font-medium leading-6 text-gray-900">What did you accomplish today? <span class="text-red-500">*</span></label>
                <div class="mt-2">
                    <textarea name="tasks_completed" id="tasks_completed" class="wysiwyg-editor"></textarea>
                </div>
            </div>

            <div class="col-span-full">
                <label for="tasks_planned" class="block text-sm font-medium leading-6 text-gray-900">What is the plan for tomorrow? <span class="text-red-500">*</span></label>
                <div class="mt-2">
                    <textarea name="tasks_planned" id="tasks_planned" class="wysiwyg-editor"></textarea>
                </div>
            </div>

            <div class="col-span-full">
                <label for="challenges" class="block text-sm font-medium leading-6 text-gray-900">Any blockers or challenges?</label>
                <div class="mt-2">
                    <textarea name="challenges" id="challenges" class="wysiwyg-editor"></textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-x-6 pt-4 border-t border-gray-100">
                <a href="{{ route('team.reports.index') }}" class="text-sm font-semibold leading-6 text-gray-900">Cancel</a>
                <button type="submit" class="rounded-md bg-brand-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-primary/80 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">Submit Report</button>
            </div>
        </div>
    </form>
</div>

<!-- Load CKEditor -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.2.0/classic/ckeditor.js"></script>
<script>
    document.querySelectorAll('.wysiwyg-editor').forEach(element => {
        ClassicEditor
            .create(element, {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote'],
                placeholder: 'Type your details here...'
            })
            .catch(error => {
                console.error(error);
            });
    });
</script>
<style>
    .ck-editor__editable {
        min-height: 150px;
    }
</style>
@endsection
