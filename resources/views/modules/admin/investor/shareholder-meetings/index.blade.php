@extends('admin::layouts.app')

@section('title', 'Shareholder Meetings')

@section('content')
<div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
        <h1 class="text-3xl font-bold text-gray-900">Shareholder Meetings</h1>
        <p class="mt-2 text-sm text-gray-700">Manage AGM, EGM, and special shareholder meetings</p>
    </div>
    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
        <a href="{{ route('admin.investor.shareholder-meetings.create') }}" 
           class="block rounded-md bg-brand-primary px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-red-700">
            Create Meeting
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
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Type</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Meeting Date</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Location</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Documents</th>
                            <th class="relative py-3.5 pl-3 pr-4 sm:pr-6"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($meetings as $meeting)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                {{ $meeting->title }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                @php
                                    $typeColors = [
                                        'agm' => 'bg-blue-50 text-blue-700 ring-blue-700/10',
                                        'egm' => 'bg-purple-50 text-purple-700 ring-purple-700/10',
                                        'special' => 'bg-orange-50 text-orange-700 ring-orange-700/10',
                                    ];
                                    $colorClass = $typeColors[$meeting->meeting_type] ?? $typeColors['agm'];
                                @endphp
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $colorClass }}">
                                    {{ strtoupper($meeting->meeting_type) }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                <div class="flex items-center">
                                    <svg class="mr-1.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $meeting->meeting_date->format('M d, Y') }}</div>
                                        <div class="text-gray-500">{{ $meeting->meeting_date->format('h:i A') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-4 text-sm text-gray-500">
                                @if($meeting->location)
                                    <div class="flex items-center">
                                        <svg class="mr-1.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ Str::limit($meeting->location, 30) }}
                                    </div>
                                @else
                                    <span class="text-gray-400">TBD</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                @if($meeting->is_upcoming)
                                    <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                        <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        Upcoming
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                                        <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        Past
                                    </span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                <div class="flex gap-1">
                                    @if($meeting->getFirstMedia('agenda_file'))
                                        <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                            <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                            </svg>
                                            Agenda
                                        </span>
                                    @endif
                                    @if($meeting->getFirstMedia('minutes_file'))
                                        <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                            <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                            </svg>
                                            Minutes
                                        </span>
                                    @endif
                                    @if($meeting->getFirstMedia('presentation_file'))
                                        <span class="inline-flex items-center rounded-md bg-purple-50 px-2 py-1 text-xs font-medium text-purple-700 ring-1 ring-inset ring-purple-700/10">
                                            <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                            </svg>
                                            Slides
                                        </span>
                                    @endif
                                    @if(!$meeting->getFirstMedia('agenda_file') && !$meeting->getFirstMedia('minutes_file') && !$meeting->getFirstMedia('presentation_file'))
                                        <span class="text-gray-400">No files</span>
                                    @endif
                                </div>
                            </td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                <a href="{{ route('admin.investor.shareholder-meetings.edit', $meeting) }}" class="text-brand-primary hover:text-red-900 mr-4">Edit</a>
                                <form action="{{ route('admin.investor.shareholder-meetings.destroy', $meeting) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this meeting?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-3 py-12 text-center text-sm text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <p class="mt-2">No shareholder meetings found.</p>
                                <a href="{{ route('admin.investor.shareholder-meetings.create') }}" class="mt-1 text-brand-primary hover:text-red-900">Create one</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($meetings->hasPages())
            <div class="mt-4">
                {{ $meetings->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
