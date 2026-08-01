@extends('admin::layouts.app')

@section('title', 'Add Department')

@section('content')
<div class="mx-auto px-4 sm:px-6 lg:px-8">
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">Add Department</h2>
            <p class="mt-2 text-sm text-gray-500">Create a new department to organize your team structure.</p>
        </div>
    </div>

    <form action="{{ route('admin.departments.store') }}" method="POST" class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
        @csrf
        <div class="p-8">
            <div class="max-w-2xl space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Name</label>
                    <div class="mt-2">
                        <input type="text" name="name" id="name" placeholder="e.g. Sales & Marketing" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6" required>
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium leading-6 text-gray-900">Description</label>
                    <div class="mt-2">
                        <textarea rows="4" name="description" id="description" placeholder="Briefly describe the department's responsibilities..." class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6"></textarea>
                    </div>
                </div>

                <div class="relative flex items-start pt-2">
                    <div class="flex h-6 items-center">
                      <input id="is_active" aria-describedby="is_active-description" name="is_active" type="checkbox" value="1" checked class="h-4 w-4 rounded border-gray-300 text-brand-primary focus:ring-brand-primary cursor-pointer">
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
            <button type="submit" class="rounded-md bg-brand-primary px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-primary/80 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary transition-all">Create Department</button>
        </div>
    </form>
</div>
@endsection
