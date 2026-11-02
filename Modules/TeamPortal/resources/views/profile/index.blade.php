@extends('teamportal::layouts.app')

@section('title', 'My Profile')

@push('styles')
<style>
    .profile-hero {
        background: linear-gradient(135deg, #10231C 0%, #2d3180 60%, #0E5E4A 100%);
        position: relative;
        overflow: hidden;
    }
    .profile-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    .profile-avatar-ring {
        background: conic-gradient(from 180deg at 50% 50%, #0E5E4A 0deg, #ff9a4b 120deg, #10231C 240deg, #0E5E4A 360deg);
        padding: 3px;
        border-radius: 9999px;
        animation: spin-ring 8s linear infinite;
    }
    @keyframes spin-ring {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .stat-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px -5px rgba(0,0,0,0.12);
    }
    .info-row {
        transition: background 0.15s ease;
    }
    .info-row:hover {
        background: rgba(251, 76, 75, 0.04);
    }
    .task-badge {
        font-size: 0.7rem;
        padding: 2px 8px;
        border-radius: 9999px;
        font-weight: 600;
        letter-spacing: 0.02em;
    }
    .progress-bar {
        height: 6px;
        border-radius: 9999px;
        background: #f3f4f6;
        overflow: hidden;
    }
    .progress-fill {
        height: 100%;
        border-radius: 9999px;
        background: linear-gradient(90deg, #10231C, #0E5E4A);
        transition: width 1s ease-out;
    }
</style>
@endpush

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">

    {{-- ===== HERO BANNER ===== --}}
    <div class="profile-hero rounded-2xl relative">
        <div class="relative z-10 px-8 pt-8 pb-0">
            {{-- Top bar --}}
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-white/60 text-sm font-medium tracking-wide uppercase">Team Portal</p>
                    <h1 class="text-white text-2xl font-bold mt-0.5">My Profile</h1>
                </div>
                <a href="{{ route('team.profile.edit') }}"
                   class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-all backdrop-blur-sm">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                    </svg>
                    Edit Profile
                </a>
            </div>

            {{-- Avatar + Identity --}}
            <div class="flex items-end gap-6 pb-8">
                {{-- Avatar --}}
                <div class="flex-shrink-0 relative">
                    <div class="profile-avatar-ring inline-block">
                        <div class="h-24 w-24 rounded-full overflow-hidden bg-brand-secondary border-2 border-white/10" style="animation: none;">
                            @if($user->getFirstMediaUrl('avatar'))
                                <img src="{{ $user->getFirstMediaUrl('avatar') }}" class="h-full w-full object-cover" alt="{{ $user->name }}">
                            @else
                                <div class="h-full w-full flex items-center justify-center text-white text-3xl font-bold select-none" style="background: linear-gradient(135deg, #0E5E4A, #10231C);">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                            @endif
                        </div>
                    </div>
                    {{-- Status dot --}}
                    @if(($user->status ?? 'active') === 'active')
                    <span class="absolute bottom-1 right-1 h-4 w-4 rounded-full bg-green-400 border-2 border-white ring-2 ring-green-300/50"></span>
                    @endif
                </div>

                {{-- Name + Meta --}}
                <div class="pb-1">
                    <h2 class="text-white text-2xl font-bold leading-tight">{{ $user->name }}</h2>
                    <p class="text-white/60 text-sm mt-0.5">{{ $user->email }}</p>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @if($user->role)
                        <span class="inline-flex items-center gap-1 bg-brand-primary/90 text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                            {{ ucwords(str_replace('_', ' ', $user->role)) }}
                        </span>
                        @endif
                        @if($user->department)
                        <span class="inline-flex items-center gap-1 bg-white/10 text-white text-xs font-semibold px-3 py-1 rounded-full border border-white/20">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" /></svg>
                            {{ $user->department }}
                        </span>
                        @endif
                        <span class="inline-flex items-center gap-1 bg-white/10 text-white/80 text-xs font-semibold px-3 py-1 rounded-full border border-white/20">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5" /></svg>
                            Member since {{ $user->created_at->format('M Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Wave bottom --}}
        <div class="relative h-8">
            <svg viewBox="0 0 1440 32" preserveAspectRatio="none" class="absolute bottom-0 w-full h-8" fill="#f9fafb" xmlns="http://www.w3.org/2000/svg">
                <path d="M0,32 C360,0 1080,0 1440,32 L1440,32 L0,32 Z"/>
            </svg>
        </div>
    </div>

    {{-- ===== STATS ROW ===== --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        @php
            $statItems = [
                ['label' => 'Total Tasks', 'value' => $stats['total_tasks'], 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'color' => 'text-blue-600 bg-blue-50'],
                ['label' => 'Completed', 'value' => $stats['completed_tasks'], 'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'text-green-600 bg-green-50'],
                ['label' => 'Projects', 'value' => $stats['active_projects'], 'icon' => 'M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z', 'color' => 'text-purple-600 bg-purple-50'],
                ['label' => 'Work Logs', 'value' => $stats['total_work_logs'], 'icon' => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'text-orange-600 bg-orange-50'],
            ];
        @endphp

        @foreach($statItems as $stat)
        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3 mb-3">
                <div class="h-9 w-9 rounded-xl flex items-center justify-center {{ $stat['color'] }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ $stat['label'] }}</span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stat['value'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- ===== PROGRESS + DETAILS ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT: Personal Information --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- About / Bio --}}
            @if($user->bio)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <svg class="h-4 w-4 text-brand-primary" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                    About
                </h3>
                <p class="text-sm text-gray-700 leading-relaxed">{{ $user->bio }}</p>
            </div>
            @endif

            {{-- Contact & Role Details --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider flex items-center gap-2">
                        <svg class="h-4 w-4 text-brand-primary" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Personal Details
                    </h3>
                </div>
                <div class="divide-y divide-gray-50">
                    @php
                        $details = [
                            ['label' => 'Full Name',    'value' => $user->name,       'icon' => 'M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z'],
                            ['label' => 'Email',        'value' => $user->email,      'icon' => 'M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75'],
                            ['label' => 'Phone',        'value' => $user->phone ?? '—', 'icon' => 'M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z'],
                            ['label' => 'Role',         'value' => $user->role ? ucwords(str_replace('_', ' ', $user->role)) : '—', 'icon' => 'M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z'],
                            ['label' => 'Department',   'value' => $user->department ?? '—', 'icon' => 'M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z'],
                            ['label' => 'Status',       'value' => null, 'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'status' => true],
                            ['label' => 'Member Since', 'value' => $user->created_at->format('F j, Y'), 'icon' => 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5'],
                        ];
                    @endphp

                    @foreach($details as $detail)
                    <div class="info-row flex items-center gap-4 px-6 py-3.5">
                        <div class="h-8 w-8 rounded-lg bg-gray-50 flex items-center justify-center flex-shrink-0">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $detail['icon'] }}"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">{{ $detail['label'] }}</p>
                            @if(!empty($detail['status']))
                                @php
                                    $s = $user->status ?? 'active';
                                    $statusMap = ['active' => ['bg-green-50 text-green-700 ring-green-600/20', 'Active'], 'inactive' => ['bg-red-50 text-red-700 ring-red-600/20', 'Inactive'], 'pending' => ['bg-yellow-50 text-yellow-700 ring-yellow-600/20', 'Pending']];
                                    [$cls, $label] = $statusMap[$s] ?? ['bg-gray-50 text-gray-700 ring-gray-600/20', ucfirst($s)];
                                @endphp
                                <span class="inline-flex items-center mt-0.5 rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset {{ $cls }}">{{ $label }}</span>
                            @else
                                <p class="text-sm font-medium text-gray-900 truncate mt-0.5">{{ $detail['value'] }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Airtime / Data Phone --}}
            @if($user->airtime_phone || $user->data_phone)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <svg class="h-4 w-4 text-brand-primary" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18h3" /></svg>
                    Mobile Numbers
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @if($user->airtime_phone)
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-1">Airtime Phone</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $user->airtime_phone }}</p>
                    </div>
                    @endif
                    @if($user->data_phone)
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-1">Data Phone</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $user->data_phone }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        {{-- RIGHT: Sidebar cards --}}
        <div class="space-y-6">

            {{-- Task Completion Rate --}}
            @if($stats['total_tasks'] > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <svg class="h-4 w-4 text-brand-primary" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>
                    Task Completion
                </h3>
                @php $rate = $stats['total_tasks'] > 0 ? min(100, round(($stats['completed_tasks'] / $stats['total_tasks']) * 100)) : 0; @endphp
                <div class="flex items-end justify-between mb-2">
                    <span class="text-3xl font-bold text-gray-900">{{ $rate }}%</span>
                    <span class="text-xs text-gray-400">{{ $stats['completed_tasks'] }}/{{ $stats['total_tasks'] }} tasks</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: {{ min(100, $rate) }}%"></div>
                </div>
                <p class="text-xs text-gray-400 mt-2">
                    @if($rate >= 80) 🎉 Excellent work rate!
                    @elseif($rate >= 50) 💪 Keep going, you're doing great!
                    @else 🚀 Room to grow — keep pushing!
                    @endif
                </p>
            </div>
            @endif

            {{-- Recent Tasks --}}
            @if($recentTasks->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider flex items-center gap-2">
                        <svg class="h-4 w-4 text-brand-primary" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                        Recent Tasks
                    </h3>
                    <a href="{{ route('team.tasks.index') }}" class="text-xs text-brand-primary hover:underline font-medium">View all</a>
                </div>
                <div class="divide-y divide-gray-50">
                    @foreach($recentTasks as $task)
                    @php
                        $taskStatus = strtolower($task->status ?? 'pending');
                        $taskBadge = match($taskStatus) {
                            'completed', 'done' => 'bg-green-100 text-green-700',
                            'in_progress', 'in progress' => 'bg-blue-100 text-blue-700',
                            'review' => 'bg-purple-100 text-purple-700',
                            default => 'bg-gray-100 text-gray-600',
                        };
                    @endphp
                    <div class="px-5 py-3 flex items-center gap-3">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-800 truncate">{{ $task->title ?? $task->name ?? 'Unnamed Task' }}</p>
                            @if($task->project)
                            <p class="text-xs text-gray-400 truncate mt-0.5">{{ $task->project->name }}</p>
                            @endif
                        </div>
                        <span class="task-badge flex-shrink-0 {{ $taskBadge }}">
                            {{ ucfirst(str_replace('_', ' ', $taskStatus)) }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Security --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <svg class="h-4 w-4 text-brand-primary" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                    Security
                </h3>
                <p class="text-xs text-gray-500 mb-4">Keep your account secure with a strong password.</p>
                <a href="{{ route('team.profile.password') }}"
                   class="flex items-center justify-between w-full bg-gray-50 hover:bg-gray-100 rounded-xl px-4 py-3 text-sm font-semibold text-gray-700 transition-colors group">
                    <span class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-gray-400 group-hover:text-brand-primary transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" /></svg>
                        Change Password
                    </span>
                    <svg class="h-4 w-4 text-gray-400 group-hover:text-brand-primary transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
