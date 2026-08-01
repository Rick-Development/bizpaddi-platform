@extends('admin::layouts.app')

@section('title', 'Financial Reports')

@section('content')
<div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
        <h1 class="text-3xl font-bold text-gray-900">Financial Reports</h1>
        <p class="mt-2 text-sm text-gray-700">Manage quarterly and annual financial reports</p>
    </div>
    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
        <a href="{{ route('admin.investor.financial-reports.create') }}" 
           class="block rounded-md bg-brand-primary px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-red-700">
            Create Report
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
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Period</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Report Date</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">File</th>
                            <th class="relative py-3.5 pl-3 pr-4 sm:pr-6"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($reports as $report)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                {{ $report->title }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                    {{ ucfirst($report->report_type) }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                <span class="font-medium text-gray-900">{{ $report->report_period }}</span>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ $report->report_date->format('M d, Y') }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                @if($report->is_published)
                                    <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                        <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        Published
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                                        <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                @if($report->getFirstMedia('report_file'))
                                    <a href="{{ $report->getFirstMediaUrl('report_file') }}" target="_blank" 
                                       class="inline-flex items-center text-brand-primary hover:text-red-900">
                                        <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Download
                                    </a>
                                @else
                                    <span class="text-gray-400">No file</span>
                                @endif
                            </td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                <a href="{{ route('admin.investor.financial-reports.edit', $report) }}" class="text-brand-primary hover:text-red-900 mr-4">Edit</a>
                                <form action="{{ route('admin.investor.financial-reports.destroy', $report) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this report?');">
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="mt-2">No financial reports found.</p>
                                <a href="{{ route('admin.investor.financial-reports.create') }}" class="mt-1 text-brand-primary hover:text-red-900">Create one</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($reports->hasPages())
            <div class="mt-4">
                {{ $reports->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
