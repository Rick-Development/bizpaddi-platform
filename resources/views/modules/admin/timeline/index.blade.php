@extends('admin::layouts.app')

@section('title', 'Timeline')

@section('content')
<div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
        <h1 class="text-3xl font-bold text-gray-900">Company Timeline</h1>
        <p class="mt-2 text-sm text-gray-700">Manage company history and milestones</p>
    </div>
    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
        <a href="{{ route('admin.timeline.create') }}" 
           class="block rounded-md bg-brand-primary px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-red-700">
            Add Event
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
                            <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Year</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Title</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Description</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Order</th>
                            <th class="relative py-3.5 pl-3 pr-4 sm:pr-6"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($timelines as $timeline)
                        <tr>
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                {{ $timeline->year }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $timeline->title }}</td>
                            <td class="px-3 py-4 text-sm text-gray-500">{{ Str::limit($timeline->description, 50) }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $timeline->order }}</td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                <a href="{{ route('admin.timeline.edit', $timeline) }}" class="text-brand-primary hover:text-red-900 mr-4">Edit</a>
                                <form action="{{ route('admin.timeline.destroy', $timeline) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-3 py-8 text-center text-sm text-gray-500">
                                No timeline events found. <a href="{{ route('admin.timeline.create') }}" class="text-brand-primary hover:text-red-900">Add one</a>
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
