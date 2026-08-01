@extends('admin::layouts.app')

@section('title', 'Departments')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">Departments</h1>
            <p class="mt-2 text-sm text-gray-500">Manage your organization's departments and their structural roles.</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="{{ route('admin.departments.create') }}" class="block rounded-md bg-brand-primary px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-brand-primary/80 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary transition-colors">
                <span class="flex items-center gap-2">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                    </svg>
                    New Department
                </span>
            </a>
        </div>
    </div>

    <!-- Mobile View (Cards) -->
    <div class="mt-8 sm:hidden space-y-4">
        @forelse($departments as $department)
        <div class="bg-white px-4 py-5 shadow sm:rounded-lg sm:px-6 border border-gray-100 rounded-xl">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-semibold leading-6 text-gray-900">{{ $department->name }}</h3>
                <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $department->is_active ? 'bg-green-50 text-green-700 ring-green-600/20' : 'bg-red-50 text-red-700 ring-red-600/20' }}">
                    {{ $department->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <p class="mt-2 text-sm text-gray-500">{{ Str::limit($department->description, 80) }}</p>
            <div class="mt-4 flex items-center justify-end border-t border-gray-100 pt-3">
                 <a href="{{ route('admin.departments.edit', $department) }}" class="text-sm font-medium text-brand-primary hover:text-brand-primary/80">Edit Department &rarr;</a>
            </div>
        </div>
        @empty
        <div class="text-center py-6 bg-white rounded-xl border border-dashed border-gray-300">
            <p class="text-sm text-gray-500">No departments found.</p>
        </div>
        @endforelse
    </div>

    <!-- Desktop View (Table) -->
    <div class="hidden sm:block mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg bg-white">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Name</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Description</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($departments as $department)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0 flex items-center justify-center rounded-lg bg-brand-secondary/10 text-brand-secondary font-bold text-lg">
                                            {{ substr($department->name, 0, 1) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="font-medium text-gray-900">{{ $department->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-500 max-w-md truncate">{{ $department->description }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $department->is_active ? 'bg-green-50 text-green-700 ring-green-600/20' : 'bg-red-50 text-red-700 ring-red-600/20' }}">
                                        {{ $department->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                    <a href="{{ route('admin.departments.edit', $department) }}" class="text-brand-primary hover:text-brand-primary/80 font-semibold">Edit<span class="sr-only">, {{ $department->name }}</span></a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-sm text-gray-500">
                                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100">
                                        <svg class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6" />
                                        </svg>
                                    </div>
                                    <h3 class="mt-2 text-sm font-semibold text-gray-900">No departments</h3>
                                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new department.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-6">
        {{ $departments->links() }}
    </div>
</div>
@endsection
