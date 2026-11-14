@extends('teamportal::layouts.app')

@section('title', 'Daily Report Details')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Daily Report: {{ $report->created_at->format('F d, Y') }}</h1>
            <p class="mt-1 text-xs sm:text-sm text-gray-400">Submitted by {{ auth()->user()->name }}</p>
        </div>
        <div>
            <a href="{{ route('team.reports.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-xs sm:text-sm font-semibold text-gray-300 hover:bg-white/10 hover:text-white transition-all shadow-sm">
                &larr; Back to Reports
            </a>
        </div>
    </div>

    <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/10 p-6 sm:p-8">
        <dl class="divide-y divide-white/5 text-xs sm:text-sm">
            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                <dt class="font-bold text-gray-400 uppercase tracking-wider text-[11px]">Total Hours</dt>
                <dd class="mt-1 text-emerald-400 font-black sm:col-span-2 sm:mt-0 text-base">{{ $report->hours_worked }} hours</dd>
            </div>

            <div class="py-5 sm:grid sm:grid-cols-3 sm:gap-4 p-4 bg-[#08110e]/60 rounded-2xl border border-white/10 my-3">
                <dt class="font-bold text-emerald-400 uppercase tracking-wider text-[11px]">Accomplished Today</dt>
                <dd class="mt-1 text-gray-200 sm:col-span-2 sm:mt-0 prose prose-invert prose-sm max-w-none leading-relaxed">
                    {!! $report->tasks_completed ?? nl2br(e($report->content)) !!}
                </dd>
            </div>

            @if($report->tasks_planned)
            <div class="py-5 sm:grid sm:grid-cols-3 sm:gap-4 p-4 bg-[#08110e]/60 rounded-2xl border border-white/10 my-3">
                <dt class="font-bold text-blue-400 uppercase tracking-wider text-[11px]">Plan for Tomorrow</dt>
                <dd class="mt-1 text-gray-200 sm:col-span-2 sm:mt-0 prose prose-invert prose-sm max-w-none leading-relaxed">
                     {!! $report->tasks_planned !!}
                </dd>
            </div>
            @endif

            @if($report->challenges)
            <div class="py-5 sm:grid sm:grid-cols-3 sm:gap-4 p-4 bg-red-950/20 rounded-2xl border border-red-500/20 my-3">
                <dt class="font-bold text-red-400 uppercase tracking-wider text-[11px]">Blockers & Challenges</dt>
                <dd class="mt-1 text-red-200 sm:col-span-2 sm:mt-0 prose prose-invert prose-sm max-w-none leading-relaxed">
                     {!! $report->challenges !!}
                </dd>
            </div>
            @endif
        </dl>
    </div>
</div>
@endsection
