@extends('admin::layouts.app')

@section('title', 'Create Project')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">Create Project</h2>
        </div>
    </div>

    <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
        @csrf
        
        <div class="px-4 py-6 sm:p-8">
            <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                
                <!-- Title -->
                <div class="sm:col-span-4">
                    <label for="title" class="block text-sm font-medium leading-6 text-gray-900">Project Title</label>
                    <div class="mt-2">
                        <input type="text" name="title" id="title" value="{{ old('title') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6" required>
                    </div>
                </div>

                <!-- Client -->
                <div class="sm:col-span-4">
                    <label for="client" class="block text-sm font-medium leading-6 text-gray-900">Client Name</label>
                    <div class="mt-2">
                        <input type="text" name="client" id="client" value="{{ old('client') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6" required>
                    </div>
                </div>

                <!-- Industry -->
                <div class="sm:col-span-3">
                    <label for="industry_id" class="block text-sm font-medium leading-6 text-gray-900">Industry</label>
                    <div class="mt-2">
                        <select id="industry_id" name="industry_id" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6" required>
                            <option value="">Select Industry</option>
                            @foreach($industries as $industry)
                                <option value="{{ $industry->id }}" {{ old('industry_id') == $industry->id ? 'selected' : '' }}>{{ $industry->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Year -->
                <div class="sm:col-span-3">
                    <label for="year" class="block text-sm font-medium leading-6 text-gray-900">Year</label>
                    <div class="mt-2">
                        <input type="number" name="year" id="year" value="{{ old('year', date('Y')) }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6" required>
                    </div>
                </div>

                <!-- Description -->
                <div class="col-span-full">
                    <label for="description" class="block text-sm font-medium leading-6 text-gray-900">Description</label>
                    <div class="mt-2">
                        <textarea id="description" name="description" rows="5" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6" required>{{ old('description') }}</textarea>
                    </div>
                    <p class="mt-3 text-sm leading-6 text-gray-600">Detailed overview of the project.</p>
                </div>

                <!-- Problem Solved -->
                <div class="col-span-full">
                    <label for="problem_solved" class="block text-sm font-medium leading-6 text-gray-900">Problem Solved</label>
                    <div class="mt-2">
                        <textarea id="problem_solved" name="problem_solved" rows="3" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6">{{ old('problem_solved') }}</textarea>
                    </div>
                    <p class="mt-3 text-sm leading-6 text-gray-600">The core problem the client faced before this project.</p>
                </div>

                <!-- Solution -->
                <div class="col-span-full">
                    <label for="solution" class="block text-sm font-medium leading-6 text-gray-900">Solution</label>
                    <div class="mt-2">
                        <textarea id="solution" name="solution" rows="4" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6">{{ old('solution') }}</textarea>
                    </div>
                    <p class="mt-3 text-sm leading-6 text-gray-600">How you solved the problem (technologies, strategy, etc).</p>
                </div>

                <!-- Impact Metrics -->
                <div class="col-span-full">
                    <label for="impact_metrics" class="block text-sm font-medium leading-6 text-gray-900">Impact Metrics</label>
                    <div class="mt-2">
                        <textarea id="impact_metrics" name="impact_metrics" rows="3" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6">{{ old('impact_metrics') }}</textarea>
                    </div>
                    <p class="mt-3 text-sm leading-6 text-gray-600">Key results or statistics (e.g., "50% increase in sales").</p>
                </div>

                <!-- Is Featured -->
                <div class="col-span-full">
                    <div class="relative flex gap-x-3">
                        <div class="flex h-6 items-center">
                            <input id="is_featured" name="is_featured" type="checkbox" value="1" {{ old('is_featured') ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-brand-primary focus:ring-brand-primary">
                        </div>
                        <div class="text-sm leading-6">
                            <label for="is_featured" class="font-medium text-gray-900">Featured Project</label>
                            <p class="text-gray-500">Show this project on the homepage "Recent Work" section.</p>
                        </div>
                    </div>
                </div>

                <!-- Featured Image -->
                <div class="col-span-full">
                    <label for="featured_image" class="block text-sm font-medium leading-6 text-gray-900">Featured Image</label>
                    <div class="mt-2">
                        <input type="file" name="featured_image" id="featured_image" accept="image/*" class="block w-full text-sm text-gray-900 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-brand-primary file:text-white hover:file:bg-red-700">
                        @error('featured_image')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Main image representing the project. Max 10MB.</p>
                </div>

                <!-- Gallery -->
                <div class="col-span-full">
                    <label for="gallery" class="block text-sm font-medium leading-6 text-gray-900">Gallery Images</label>
                    <div class="mt-2">
                        <input type="file" name="gallery[]" id="gallery" accept="image/*" multiple class="block w-full text-sm text-gray-900 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-brand-primary file:text-white hover:file:bg-red-700">
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Additional images for the project page.</p>
                </div>
            </div>
        </div>
        
        <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
            <a href="{{ route('admin.projects.index') }}" class="text-sm font-semibold leading-6 text-gray-900">Cancel</a>
            <button type="submit" class="rounded-md bg-brand-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">Create Project</button>
        </div>
    </form>
</div>
@endsection

@push('styles')
<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .note-editor.note-frame { border: 1px solid #d1d5db !important; border-radius: 0.5rem !important; overflow: hidden; }
    .note-toolbar { background: #f9fafb !important; border-bottom: 1px solid #d1d5db !important; }
</style>
@endpush

@push('scripts')
<!-- jQuery (required for Summernote) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
$(document).ready(function() {
    $('#description, #impact_metrics').summernote({
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
        placeholder: 'Enter details here...',
        tabsize: 2
    });
});
</script>
@endpush
