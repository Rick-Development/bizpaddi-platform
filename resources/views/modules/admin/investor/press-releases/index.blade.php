@extends('admin::layouts.app')

@section('title', 'Press Releases')

@section('content')
<div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
        <h1 class="text-3xl font-bold text-gray-900">Press Releases</h1>
        <p class="mt-2 text-sm text-gray-700">Manage investor press releases and announcements</p>
    </div>
    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
        <a href="{{ route('admin.investor.press-releases.create') }}" 
           class="block rounded-md bg-brand-primary px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-red-700">
            Create Release
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
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Category</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Published</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                            <th class="relative py-3.5 pl-3 pr-4 sm:pr-6"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($pressReleases as $release)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 pl-4 pr-3 text-sm sm:pl-6">
                                <div class="font-medium text-gray-900">{{ $release->title }}</div>
                                <div class="mt-1 text-gray-500">{{ $release->slug }}</div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                @php
                                    $categoryColors = [
                                        'earnings' => 'bg-purple-50 text-purple-700 ring-purple-700/10',
                                        'corporate' => 'bg-blue-50 text-blue-700 ring-blue-700/10',
                                        'product' => 'bg-green-50 text-green-700 ring-green-600/20',
                                        'other' => 'bg-gray-50 text-gray-600 ring-gray-500/10',
                                    ];
                                    $colorClass = $categoryColors[$release->category] ?? $categoryColors['other'];
                                @endphp
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $colorClass }}">
                                    {{ ucfirst($release->category) }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                @if($release->published_at)
                                    <div class="flex items-center">
                                        <svg class="mr-1.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $release->published_at->format('M d, Y') }}
                                    </div>
                                @else
                                    <span class="text-gray-400">Not published</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                @if($release->is_featured)
                                    <span class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">
                                        <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        Featured
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                                        Regular
                                    </span>
                                @endif
                            </td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                <a href="{{ route('admin.investor.press-releases.edit', $release) }}" class="text-brand-primary hover:text-red-900 mr-4">Edit</a>
                                <form action="{{ route('admin.investor.press-releases.destroy', $release) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this press release?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-3 py-12 text-center text-sm text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                                <p class="mt-2">No press releases found.</p>
                                <a href="{{ route('admin.investor.press-releases.create') }}" class="mt-1 text-brand-primary hover:text-red-900">Create one</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($pressReleases->hasPages())
            <div class="mt-4">
                {{ $pressReleases->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
