@extends('teamportal::layouts.app')

@section('title', 'Daily Reports')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Daily Reports</h1>
            <p class="mt-1 text-xs sm:text-sm text-gray-400">Track your daily progress and logged work hours.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex items-center gap-3">
            <a href="{{ route('team.reports.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-xs sm:text-sm font-bold text-white transition-all shadow-md active:scale-95">
                + Submit Report
            </a>
            <a href="{{ route('team.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-semibold text-gray-300 transition-colors">
                &larr; Dashboard
            </a>
        </div>
    </div>

    <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-3xl border border-white/10 shadow-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/5 text-left text-xs sm:text-sm">
                <thead class="bg-white/5">
                    <tr>
                        <th scope="col" class="py-4 pl-6 pr-3 font-bold text-gray-400 uppercase tracking-wider text-[11px]">Date</th>
                        <th scope="col" class="px-4 py-4 font-bold text-gray-400 uppercase tracking-wider text-[11px]">Content</th>
                        <th scope="col" class="px-4 py-4 font-bold text-gray-400 uppercase tracking-wider text-[11px]">Hours</th>
                        <th scope="col" class="relative py-4 pl-3 pr-6 text-right">
                            <span class="sr-only">View</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($reports as $report)
                    <tr class="hover:bg-white/[0.03] transition-colors">
                        <td class="whitespace-nowrap py-4 pl-6 pr-3 font-semibold text-white">
                            {{ $report->created_at->format('M j, Y') }}
                        </td>
                        <td class="px-4 py-4 text-gray-400 max-w-xs truncate text-xs">
                            {{ Str::limit(strip_tags($report->tasks_completed ?? $report->content), 80) }}
                        </td>
                        <td class="whitespace-nowrap px-4 py-4 text-emerald-400 font-bold">
                            {{ $report->hours_worked }} hrs
                        </td>
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-6 text-right font-medium">
                            <a href="{{ route('team.reports.show', $report) }}" class="text-emerald-400 hover:text-emerald-300 font-bold text-xs">View &rarr;</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center text-xs text-gray-400 italic">
                            No reports submitted yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reports->hasPages())
        <div class="p-4 border-t border-white/10 bg-white/[0.02]">
            {{ $reports->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
