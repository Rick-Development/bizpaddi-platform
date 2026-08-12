@extends('teamportal::layouts.app')

@section('title', 'BDM Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Business Development Dashboard</h1>
            <p class="mt-1 text-sm text-gray-500">Overview of your sales performance and leads.</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="flex space-x-4 mb-6">
        <a href="{{ route('team.bdm.leads.index') }}" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
            View All Leads
        </a>
        <a href="{{ route('team.bdm.deals.kanban') }}" class="inline-flex items-center rounded-md bg-brand-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-primary/80 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">
            Kanban Board
        </a>
    </div>

    <!-- Today's KPIs Grid -->
    <div>
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Today's Performance</h2>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5">
                <dt><p class="truncate text-sm font-medium text-gray-500">Daily Outreach</p></dt>
                <dd class="mt-2 flex items-baseline pb-1">
                    <p class="text-2xl font-semibold text-gray-900">{{ $dailyOutreach }}</p>
                    <p class="ml-2 flex items-baseline text-sm font-semibold text-gray-500">leads contacted</p>
                </dd>
            </div>
            <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5">
                <dt><p class="truncate text-sm font-medium text-gray-500">Daily Inbound</p></dt>
                <dd class="mt-2 flex items-baseline pb-1">
                    <p class="text-2xl font-semibold text-gray-900">{{ $dailyInbound }}</p>
                    <p class="ml-2 flex items-baseline text-sm font-semibold text-gray-500">total received</p>
                </dd>
            </div>
            <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5">
                <dt><p class="truncate text-sm font-medium text-gray-500">Daily Prospects</p></dt>
                <dd class="mt-2 flex items-baseline pb-1">
                    <p class="text-2xl font-semibold text-gray-900">{{ $dailyProspects }}</p>
                    <p class="ml-2 flex items-baseline text-sm font-semibold text-gray-500">new/untouched</p>
                </dd>
            </div>
        </div>
    </div>

    <!-- Weekly & Monthly KPIs Grid -->
    <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Weekly -->
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                <h3 class="text-base font-semibold leading-6 text-gray-900">This Week</h3>
            </div>
            <div class="p-6 grid grid-cols-3 gap-4 text-center">
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ $weeklyOutreach }}</p>
                    <p class="text-xs text-gray-500 mt-1">Outreach</p>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ $weeklyInbound }}</p>
                    <p class="text-xs text-gray-500 mt-1">Inbound</p>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ $weeklyProspects }}</p>
                    <p class="text-xs text-gray-500 mt-1">Prospects</p>
                </div>
            </div>
        </div>

        <!-- Monthly -->
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                <h3 class="text-base font-semibold leading-6 text-gray-900">This Month</h3>
            </div>
            <div class="p-6 grid grid-cols-3 gap-4 text-center">
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ $monthlyOutreach }}</p>
                    <p class="text-xs text-gray-500 mt-1">Outreach</p>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ $monthlyInbound }}</p>
                    <p class="text-xs text-gray-500 mt-1">Inbound</p>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ $monthlyProspects }}</p>
                    <p class="text-xs text-gray-500 mt-1">Prospects</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Closed Deals -->
    <div class="mt-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Overall Success</h2>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div class="relative overflow-hidden rounded-2xl bg-green-50 p-6 shadow-sm ring-1 ring-green-600/20">
                <dt><p class="truncate text-sm font-medium text-green-700">Closed Deals (All Time)</p></dt>
                <dd class="mt-2 flex items-baseline pb-1">
                    <p class="text-3xl font-semibold text-green-900">{{ $closedDeals }}</p>
                    <p class="ml-2 flex items-baseline text-sm font-semibold text-green-700">deals won</p>
                </dd>
            </div>
            <div class="relative overflow-hidden rounded-2xl bg-green-50 p-6 shadow-sm ring-1 ring-green-600/20">
                <dt><p class="truncate text-sm font-medium text-green-700">Total Deal Value</p></dt>
                <dd class="mt-2 flex items-baseline pb-1">
                    <p class="text-3xl font-semibold text-green-900">${{ number_format($totalDealValue, 2) }}</p>
                </dd>
            </div>
        </div>
    </div>
</div>
@endsection
