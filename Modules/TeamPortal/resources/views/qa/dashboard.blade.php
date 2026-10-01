@extends('teamportal::layouts.app')
@section('title', 'QA Dashboard')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="space-y-8">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                </span>
                QA Dashboard
            </h1>
            <p class="text-sm text-gray-500 mt-1">Welcome back, {{ auth()->user()->name }} — Quality Assurance</p>
        </div>
        <a href="{{ route('team.qa.bugs.create') }}" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-4 py-2 rounded-xl shadow transition-all">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Report a Bug
        </a>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        @php
            $statCards = [
                ['label' => 'Total Bugs', 'value' => $stats['total_bugs'], 'color' => 'gray', 'icon' => 'M12 12.75c1.148 0 2.278.08 3.383.237 1.037.146 1.866.966 1.866 2.013 0 3.728-2.35 6.75-5.25 6.75S6.75 18.728 6.75 15c0-1.046.83-1.867 1.866-2.013A24.204 24.204 0 0112 12.75zm0 0c2.883 0 5.647.508 8.207 1.44a23.91 23.91 0 01-1.152 6.06M12 12.75c-2.883 0-5.647.508-8.208 1.44a23.916 23.916 0 001.153 6.06M12 12.75a2.25 2.25 0 002.248-2.354M12 12.75a2.25 2.25 0 01-2.248-2.354M12 8.25c.995 0 1.971-.08 2.922-.236.403-.066.74-.358.795-.762a3.778 3.778 0 00-.399-2.25M12 8.25c-.995 0-1.97-.08-2.922-.236-.402-.066-.74-.358-.795-.762a3.734 3.734 0 01.4-2.253M12 8.25a2.25 2.25 0 00-2.248 2.146M12 8.25a2.25 2.25 0 012.248 2.146'],
                ['label' => 'Open', 'value' => $stats['open_bugs'], 'color' => 'blue', 'icon' => 'M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z'],
                ['label' => 'In Progress', 'value' => $stats['in_progress_bugs'], 'color' => 'amber', 'icon' => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'Resolved', 'value' => $stats['resolved_bugs'], 'color' => 'emerald', 'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'Critical', 'value' => $stats['critical_bugs'], 'color' => 'red', 'icon' => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z'],
                ['label' => 'My Tasks', 'value' => $stats['my_tasks'], 'color' => 'violet', 'icon' => 'M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z'],
            ];
        @endphp
        @foreach($statCards as $card)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col gap-2">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ $card['label'] }}</span>
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-{{ $card['color'] }}-100 text-{{ $card['color'] }}-600">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}"/>
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $card['value'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Charts + Recent Bugs --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Severity Chart --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Bugs by Severity</h3>
            <canvas id="severityChart" height="200"></canvas>
        </div>

        {{-- Status Chart --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Bugs by Status</h3>
            <canvas id="statusChart" height="200"></canvas>
        </div>

        {{-- My Active Tasks --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">My Active Tasks</h3>
            @forelse($myTasks as $task)
            <a href="{{ route('team.qa.test-cases.show', $task) }}" class="flex items-start gap-3 p-3 rounded-xl hover:bg-gray-50 transition group mb-2">
                <span class="mt-0.5 flex-shrink-0 w-2 h-2 rounded-full mt-2
                    {{ $task->priority === 'urgent' ? 'bg-red-500' : ($task->priority === 'high' ? 'bg-orange-400' : ($task->priority === 'medium' ? 'bg-yellow-400' : 'bg-green-400')) }}">
                </span>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate group-hover:text-brand-primary transition">{{ $task->title }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $task->project?->name ?? 'No Project' }}</p>
                </div>
                <span class="ml-auto flex-shrink-0 text-xs font-semibold px-2 py-0.5 rounded-full
                    {{ $task->status === 'review' ? 'bg-purple-100 text-purple-700' : ($task->status === 'in_progress' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600') }}">
                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                </span>
            </a>
            @empty
            <p class="text-sm text-gray-400 text-center py-6">No active tasks assigned.</p>
            @endforelse
            @if($myTasks->count())
            <a href="{{ route('team.qa.test-cases.index') }}" class="block text-center text-xs text-brand-primary font-semibold mt-3 hover:underline">View all test cases →</a>
            @endif
        </div>
    </div>

    {{-- Recent Bug Reports --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700">Recent Bug Reports</h3>
            <a href="{{ route('team.qa.bugs.index') }}" class="text-xs text-brand-primary font-semibold hover:underline">View all →</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentBugs as $bug)
            <a href="{{ route('team.qa.bugs.show', $bug) }}" class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50 transition group">
                {{-- Severity badge --}}
                <span class="flex-shrink-0 w-2 h-2 rounded-full mt-0.5
                    {{ $bug->severity === 'critical' ? 'bg-red-500' : ($bug->severity === 'high' ? 'bg-orange-400' : ($bug->severity === 'medium' ? 'bg-yellow-400' : 'bg-green-400')) }}">
                </span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate group-hover:text-brand-primary">{{ $bug->title }}</p>
                    <p class="text-xs text-gray-400">{{ $bug->project?->name ?? 'No Project' }} • {{ $bug->created_at->diffForHumans() }}</p>
                </div>
                <span class="flex-shrink-0 text-xs font-semibold px-2.5 py-1 rounded-full border
                    @if($bug->status === 'open') border-blue-200 bg-blue-50 text-blue-700
                    @elseif($bug->status === 'in_progress') border-amber-200 bg-amber-50 text-amber-700
                    @elseif($bug->status === 'resolved') border-emerald-200 bg-emerald-50 text-emerald-700
                    @elseif($bug->status === 'closed') border-gray-200 bg-gray-50 text-gray-600
                    @else border-red-200 bg-red-50 text-red-700 @endif">
                    {{ $bug->status_label }}
                </span>
                <span class="flex-shrink-0 text-xs font-semibold px-2.5 py-1 rounded-full
                    @if($bug->severity === 'critical') bg-red-100 text-red-700
                    @elseif($bug->severity === 'high') bg-orange-100 text-orange-700
                    @elseif($bug->severity === 'medium') bg-yellow-100 text-yellow-700
                    @else bg-green-100 text-green-700 @endif">
                    {{ ucfirst($bug->severity) }}
                </span>
            </a>
            @empty
            <div class="px-6 py-12 text-center">
                <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 12.75c1.148 0 2.278.08 3.383.237 1.037.146 1.866.966 1.866 2.013 0 3.728-2.35 6.75-5.25 6.75S6.75 18.728 6.75 15c0-1.046.83-1.867 1.866-2.013A24.204 24.204 0 0112 12.75z"/>
                </svg>
                <p class="text-sm text-gray-400">No bug reports yet.</p>
                <a href="{{ route('team.qa.bugs.create') }}" class="mt-2 inline-block text-sm text-brand-primary font-semibold hover:underline">Report your first bug →</a>
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
// Severity Chart
new Chart(document.getElementById('severityChart'), {
    type: 'doughnut',
    data: {
        labels: @json($severityChart['labels']),
        datasets: [{
            data: @json($severityChart['data']),
            backgroundColor: ['#ef4444','#f97316','#eab308','#22c55e'],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 11 } } } } }
});

// Status Chart
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: @json($statusChart['labels']),
        datasets: [{
            data: @json($statusChart['data']),
            backgroundColor: ['#3b82f6','#f59e0b','#10b981','#6b7280','#ef4444'],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 11 } } } } }
});
</script>
@endpush
