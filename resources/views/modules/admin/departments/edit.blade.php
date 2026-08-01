@extends('admin::layouts.app')

@section('title', 'Edit Department')

@section('content')
<div class="mx-auto px-4 sm:px-6 lg:px-8">
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">Edit Department: {{ $department->name }}</h2>
            <p class="mt-2 text-sm text-gray-500">Update department details and status.</p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0">
             <form action="{{ route('admin.departments.destroy', $department) }}" method="POST" onsubmit="return confirm('Are you sure? This department will be permanently deleted.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-red-600 shadow-sm ring-1 ring-inset ring-red-300 hover:bg-red-50 transition-colors">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                    Delete Department
                </button>
            </form>
        </div>
    </div>

    <form action="{{ route('admin.departments.update', $department) }}" method="POST" class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
        @csrf
        @method('PUT')
        
        <div class="p-8">
            <div class="max-w-2xl space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Name</label>
                    <div class="mt-2">
                        <input type="text" name="name" id="name" value="{{ $department->name }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6" required>
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium leading-6 text-gray-900">Description</label>
                    <div class="mt-2">
                        <textarea rows="4" name="description" id="description" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6">{{ $department->description }}</textarea>
                    </div>
                </div>

                <div class="relative flex items-start pt-2">
                    <input type="hidden" name="is_active" value="0">
                    <div class="flex h-6 items-center">
                      <input id="is_active" aria-describedby="is_active-description" name="is_active" type="checkbox" value="1" {{ $department->is_active ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-brand-primary focus:ring-brand-primary cursor-pointer">
                    </div>
                    <div class="ml-3 text-sm leading-6">
                      <label for="is_active" class="font-medium text-gray-900 select-none cursor-pointer">Active</label>
                      <p id="is_active-description" class="text-gray-500">Enabled for selection when adding team members.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-x-4 px-8 py-4 bg-gray-50 rounded-b-xl border-t border-gray-200">
            <a href="{{ route('admin.departments.index') }}" class="text-sm font-semibold leading-6 text-gray-900 hover:text-gray-700">Cancel</a>
            <button type="submit" class="rounded-md bg-brand-primary px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-primary/80 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary transition-all">Update Department</button>
        </div>
    </form>
</div>
@endsection
