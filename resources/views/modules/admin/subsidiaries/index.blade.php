@extends('admin::layouts.app')

@section('title', 'Subsidiaries')

@section('content')
<div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
        <h1 class="text-3xl font-bold text-gray-900">Subsidiaries</h1>
        <p class="mt-2 text-sm text-gray-700">Manage subsidiary companies and partners</p>
    </div>
    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
        <a href="{{ route('admin.subsidiaries.create') }}" 
           class="block rounded-md bg-brand-primary px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-red-700">
            Add Subsidiary
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
                            <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Company</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Location</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Website</th>
                            <th class="relative py-3.5 pl-3 pr-4 sm:pr-6"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($subsidiaries as $subsidiary)
                        <tr>
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                <div class="flex items-center">
                                    @if($subsidiary->hasMedia('logo'))
                                        <div class="h-10 w-10 flex-shrink-0">
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ $subsidiary->getFirstMediaUrl('logo') }}" alt="">
                                        </div>
                                    @else
                                        <div class="h-10 w-10 flex-shrink-0 rounded-full bg-gray-100 flex items-center justify-center">
                                            <span class="text-gray-500 font-medium text-xs">{{ substr($subsidiary->name, 0, 2) }}</span>
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <div class="font-medium text-gray-900">{{ $subsidiary->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $subsidiary->location ?? '-' }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                @if($subsidiary->website)
                                    <a href="{{ $subsidiary->website }}" target="_blank" class="text-brand-primary hover:underline">Visit Site</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                <a href="{{ route('admin.subsidiaries.edit', $subsidiary) }}" class="text-brand-primary hover:text-red-900 mr-4">Edit</a>
                                <form action="{{ route('admin.subsidiaries.destroy', $subsidiary) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-3 py-8 text-center text-sm text-gray-500">
                                No subsidiaries found. <a href="{{ route('admin.subsidiaries.create') }}" class="text-brand-primary hover:text-red-900">Add one</a>
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
