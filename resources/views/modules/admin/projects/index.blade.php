@extends('admin::layouts.app')

@section('title', 'Projects')

@section('content')
<div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
        <h1 class="text-3xl font-bold leading-tight tracking-tight text-gray-900">Projects</h1>
        <p class="mt-2 text-sm text-gray-700">Manage portfolio projects and recent work.</p>
    </div>
    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
        <a href="{{ route('admin.projects.create') }}" 
           class="block rounded-md bg-brand-primary px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-red-700">
            Add Project
        </a>
    </div>
</div>

<div class="mt-8 flow-root">
    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Title</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Client</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Industry</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Year</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                            <th class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($projects as $project)
                        <tr>
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                {{ $project->title }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $project->client }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $project->industry->name ?? '-' }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $project->year }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                @if($project->is_featured)
                                    <span class="inline-flex items-center rounded-md bg-purple-50 px-2 py-1 text-xs font-medium text-purple-700 ring-1 ring-inset ring-purple-600/20">Featured</span>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">Standard</span>
                                @endif
                            </td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                <a href="{{ route('admin.projects.edit', $project) }}" class="text-brand-primary hover:text-red-900 mr-4">Edit</a>
                                <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-3 py-8 text-center text-sm text-gray-500">
                                No projects found. <a href="{{ route('admin.projects.create') }}" class="text-brand-primary hover:text-red-900">Add your first project</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($projects->hasPages())
                <div class="mt-4">
                    {{ $projects->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
