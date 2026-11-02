@extends('admin::layouts.app')

@section('title', 'Create Project')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">Create New Project</h1>
        <p class="mt-1 text-sm text-gray-400">Start a new project</p>
    </div>

    <form action="{{ route('admin.pm-projects.store') }}" method="POST" class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl p-6 space-y-6">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-300 mb-1">Project Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-[#0a1612] text-white border-white/10">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-300 mb-1">Description</label>
            <textarea name="description" id="description" class="w-full px-3 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-[#0a1612] text-white border-white/10">{{ old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Client</label>
                <select name="client_id" class="w-full px-3 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-[#0a1612] text-white border-white/10">
                    <option value="">Select a client...</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Project Manager</label>
                <select name="manager_id" class="w-full px-3 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-[#0a1612] text-white border-white/10">
                    <option value="">Select a manager...</option>
                    @foreach($projectManagers as $manager)
                        <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>{{ $manager->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-[#0a1612] text-white border-white/10">
                    <option value="pending" selected>Pending</option>
                    <option value="active">Active</option>
                    <option value="on_hold">On Hold</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Currency</label>
                <select name="currency_id" class="w-full px-3 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-[#0a1612] text-white border-white/10">
                    <option value="">Select currency...</option>
                    @foreach($currencies as $currency)
                        <option value="{{ $currency->id }}" {{ old('currency_id') == $currency->id ? 'selected' : '' }}>
                            {{ $currency->code }} ({{ $currency->symbol }}) - {{ $currency->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Budget</label>
                <input type="number" name="budget" value="{{ old('budget') }}" step="0.01" class="w-full px-3 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-[#0a1612] text-white border-white/10">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Start Date</label>
                <input type="date" name="start_date" value="{{ old('start_date') }}" class="w-full px-3 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-[#0a1612] text-white border-white/10">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Due Date</label>
                <input type="date" name="due_date" value="{{ old('due_date') }}" class="w-full px-3 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-[#0a1612] text-white border-white/10">
            </div>
        </div>

        <div class="flex justify-end gap-x-3 pt-4">
            <a href="{{ route('admin.pm-projects.index') }}" class="px-4 py-2.5 border border-white/10 rounded-xl text-sm font-medium text-gray-300 hover:bg-white/[0.03] transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-sm font-bold shadow-lg shadow-emerald-900/30 transition-all hover:-translate-y-0.5">
                Create Project
            </button>
        </div>
    </form>
</div>
@endsection

@push('styles')
<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endpush

@push('scripts')
<!-- jQuery (required for Summernote) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
$(document).ready(function() {
    $('#description').summernote({
        height: 200,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
        placeholder: 'Enter project description here...',
        tabsize: 2
    });
});
</script>
@endpush
