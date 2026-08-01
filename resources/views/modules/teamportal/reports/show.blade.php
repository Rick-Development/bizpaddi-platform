@extends('teamportal::layouts.app')

@section('title', 'Daily Report Details')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">Daily Report: {{ $report->created_at->format('F d, Y') }}</h1>
            <p class="mt-2 text-sm text-gray-700">submitted by {{ auth()->user()->name }}</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="{{ route('team.reports.index') }}" class="block rounded-md bg-gray-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-gray-500">Back</a>
        </div>
    </div>

    <div class="mt-6 border-t border-gray-100">
        <dl class="divide-y divide-gray-100">
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Total Hours</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $report->hours_worked }} hours</dd>
            </div>

            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0 bg-gray-50/50 rounded-lg">
                <dt class="text-sm font-bold leading-6 text-brand-primary">Accomplished Today</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 prose prose-sm max-w-none">
                    {!! $report->tasks_completed ?? nl2br(e($report->content)) !!}
                </dd>
            </div>

            @if($report->tasks_planned)
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-bold leading-6 text-blue-600">Plan for Tomorrow</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 prose prose-sm max-w-none">
                     {!! $report->tasks_planned !!}
                </dd>
            </div>
            @endif

            @if($report->challenges)
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0 bg-red-50/30 rounded-lg">
                <dt class="text-sm font-bold leading-6 text-red-600">Blockers & Challenges</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 prose prose-sm max-w-none">
                     {!! $report->challenges !!}
                </dd>
            </div>
            @endif
        </dl>
    </div>
</div>
@endsection
