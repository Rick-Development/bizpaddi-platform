@extends('teamportal::layouts.app')

@section('title', 'Daily Reports')

@section('content')
<div class="space-y-6">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Daily Reports</h1>
            <p class="mt-1 text-sm text-gray-500">Track your daily progress and hours.</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="{{ route('team.reports.create') }}" class="block rounded-md bg-brand-primary px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-brand-primary/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">
                Submit Report
            </a>
        </div>
    </div>

    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
        <table class="min-w-full divide-y divide-gray-300">
            <thead>
                <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Date</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Content</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Hours</th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                        <span class="sr-only">Edit</span>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($reports as $report)
                <tr>
                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                        {{ $report->created_at->format('M j, Y') }}
                    </td>
                    <td class="px-3 py-4 text-sm text-gray-500 max-w-xs truncate">
                        {{ Str::limit(strip_tags($report->tasks_completed ?? $report->content), 80) }}
                    </td>
                     <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                        {{ $report->hours_worked }} hrs
                    </td>
                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                        <a href="{{ route('team.reports.show', $report) }}" class="text-brand-primary hover:text-brand-primary/80">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-10 text-center text-gray-500">
                        No reports submitted yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $reports->links() }}
        </div>
    </div>
</div>
@endsection
