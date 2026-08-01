@extends('admin::layouts.app')

@section('title', 'Industries')

@section('content')
<div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
        <h1 class="text-3xl font-bold text-gray-900">Industries</h1>
        <p class="mt-2 text-sm text-gray-700">Manage project categories and industries</p>
    </div>
    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
        <a href="{{ route('admin.industries.create') }}" 
           class="block rounded-md bg-brand-primary px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-red-700">
            Add Industry
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
                            <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Name</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Slug</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Projects</th>
                            <th class="relative py-3.5 pl-3 pr-4 sm:pr-6"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($industries as $industry)
                        <tr>
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                {{ $industry->name }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $industry->slug }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                    {{ $industry->projects_count }} {{ Str::plural('project', $industry->projects_count) }}
                                </span>
                            </td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                <a href="{{ route('admin.industries.edit', $industry) }}" class="text-brand-primary hover:text-red-900 mr-4">Edit</a>
                                <form action="{{ route('admin.industries.destroy', $industry) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure? This will fail if there are projects in this industry.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-3 py-8 text-center text-sm text-gray-500">
                                No industries found. <a href="{{ route('admin.industries.create') }}" class="text-brand-primary hover:text-red-900">Add one</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
