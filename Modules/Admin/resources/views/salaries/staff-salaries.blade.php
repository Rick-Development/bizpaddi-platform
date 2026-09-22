@extends('admin::layouts.app')

@section('title', 'Staff Payroll & Active Salaries')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 space-y-6" x-data="staffPayroll()">
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-gray-200">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <span>Accounting</span>
                <span>/</span>
                <span class="text-brand-primary font-medium">Staff Payroll</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Staff Payroll & Monthly Salaries</h1>
            <p class="mt-1 text-sm text-gray-500">Record and track active team salaries, monthly expenditures, and payment status.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <!-- Month Selector -->
            <form action="{{ route('admin.accounting.payroll.index') }}" method="GET" class="flex items-center gap-2 bg-white px-3 py-2 rounded-xl border border-gray-200 shadow-sm">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Month:</span>
                <input type="month" name="month" value="{{ $selectedMonthStr }}" onchange="this.form.submit()" class="text-sm font-semibold text-gray-900 border-none p-0 focus:ring-0 cursor-pointer">
            </form>

            <button type="button" @click="openCreateModal()" class="inline-flex items-center gap-2 rounded-xl bg-brand-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-primary/90 transition-all hover:scale-[1.02] active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Record New Salary
            </button>
        </div>
    </div>

    <!-- Navigation Tabs (Connecting Payroll & Disbursements) -->
    <div class="flex items-center justify-between border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <a href="{{ route('admin.accounting.payroll.index') }}" class="border-brand-primary text-brand-primary whitespace-nowrap border-b-2 py-3 px-1 text-sm font-bold flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Staff Payroll & Active Salaries
                <span class="bg-brand-primary/10 text-brand-primary py-0.5 px-2 rounded-full text-xs font-bold">{{ $activeStaffCount }}</span>
            </a>
            <a href="{{ route('admin.accounting.salaries.index') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 whitespace-nowrap border-b-2 py-3 px-1 text-sm font-medium flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
                Salary Disbursements (History)
            </a>
        </nav>

        <a href="{{ route('admin.accounting.salaries.create') }}" class="hidden sm:inline-flex items-center gap-1.5 text-xs font-semibold text-gray-600 hover:text-brand-primary transition-colors py-2">
            <span>+ SafeHaven Direct Transfer</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
    </div>

    <!-- KPI Summary Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- 1. Active Monthly Salary Commitment -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500/10 via-white to-white p-6 border border-emerald-100 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-800">Monthly Salary Expense</span>
                <span class="p-2 rounded-xl bg-emerald-100 text-emerald-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </span>
            </div>
            <div class="mt-4">
                <div class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight font-mono">
                    ₦{{ number_format($totalActiveSalaries, 2) }}
                </div>
                <div class="mt-1 flex items-center justify-between text-xs text-gray-500">
                    <span>Base: ₦{{ number_format($totalBaseSalaries, 0) }}{{ $totalIncentives > 0 ? ' + ₦' . number_format($totalIncentives, 0) . ' bonus' : '' }}</span>
                    <span class="font-bold text-emerald-700">Every Month</span>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-emerald-50 text-[11px] text-gray-500 flex items-center justify-between">
                <span>Annual Projection:</span>
                <span class="font-semibold text-gray-700 font-mono">₦{{ number_format($annualProjection, 0) }}/yr</span>
            </div>
        </div>

        <!-- 2. Active Salaried Team Members -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500/10 via-white to-white p-6 border border-blue-100 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-800">Active Staff</span>
                <span class="p-2 rounded-xl bg-blue-100 text-blue-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </span>
            </div>
            <div class="mt-4">
                <div class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">
                    {{ $activeStaffCount }} <span class="text-lg font-medium text-gray-500">Members</span>
                </div>
                <div class="mt-1 flex items-center justify-between text-xs text-gray-500">
                    <span>On active payroll</span>
                    <span class="font-semibold text-blue-700">Staff Count</span>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-blue-50 text-[11px] text-gray-500 flex items-center justify-between">
                <span>Average Monthly Salary:</span>
                <span class="font-semibold text-gray-700 font-mono">₦{{ number_format($averageSalary, 0) }}</span>
            </div>
        </div>

        <!-- 3. Disbursed This Month -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-500/10 via-white to-white p-6 border border-purple-100 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-purple-800">Paid In {{ $selectedMonth->format('M Y') }}</span>
                <span class="p-2 rounded-xl bg-purple-100 text-purple-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </span>
            </div>
            <div class="mt-4">
                <div class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight font-mono">
                    ₦{{ number_format($disbursedThisMonth, 2) }}
                </div>
                <div class="mt-1 flex items-center justify-between text-xs text-gray-500">
                    <span>{{ $payoutPercentage }}% of monthly budget</span>
                    <span class="font-semibold text-purple-700">Disbursed</span>
                </div>
            </div>
            <!-- Progress Bar -->
            <div class="w-full bg-gray-100 rounded-full h-1.5 mt-3 overflow-hidden">
                <div class="bg-purple-600 h-1.5 rounded-full transition-all duration-500" style="width: {{ $payoutPercentage }}%"></div>
            </div>
        </div>

        <!-- 4. Remaining Balance This Month -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500/10 via-white to-white p-6 border border-amber-100 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-amber-800">Pending For {{ $selectedMonth->format('M Y') }}</span>
                <span class="p-2 rounded-xl bg-amber-100 text-amber-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </span>
            </div>
            <div class="mt-4">
                <div class="text-2xl sm:text-3xl font-black {{ $remainingThisMonth > 0 ? 'text-amber-900' : 'text-emerald-700' }} tracking-tight font-mono">
                    ₦{{ number_format($remainingThisMonth, 2) }}
                </div>
                <div class="mt-1 flex items-center justify-between text-xs text-gray-500">
                    <span>{{ $remainingThisMonth > 0 ? 'To be disbursed' : 'All salaries disbursed' }}</span>
                    <span class="font-semibold {{ $remainingThisMonth > 0 ? 'text-amber-700' : 'text-emerald-700' }}">
                        {{ $remainingThisMonth > 0 ? 'Pending' : 'Completed' }}
                    </span>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-amber-50 text-[11px] text-gray-500 flex items-center justify-between">
                <span>Month:</span>
                <span class="font-semibold text-gray-700">{{ $selectedMonth->format('F Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Monthly Spending Strip (Every Month Trend) -->
    <div class="bg-white rounded-2xl border border-gray-200 p-4 shadow-xs">
        <div class="flex items-center justify-between mb-3 px-1">
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-gray-700">6-Month Disbursement History</span>
                <span class="text-[10px] px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 font-medium">Actual SafeHaven Transfers</span>
            </div>
            <span class="text-xs text-gray-400">Baseline Commitment: <strong>₦{{ number_format($totalActiveSalaries, 0) }}/mo</strong></span>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3">
            @foreach($monthlyTrend as $trend)
            <div class="p-3 rounded-xl border {{ $trend['is_current'] ? 'border-brand-primary/40 bg-brand-primary/5 ring-1 ring-brand-primary/20' : 'border-gray-100 bg-gray-50/60' }} flex flex-col justify-between">
                <div class="flex items-center justify-between text-xs mb-1">
                    <span class="font-bold {{ $trend['is_current'] ? 'text-brand-primary' : 'text-gray-700' }}">{{ $trend['month_name'] }}</span>
                    @if($trend['is_current'])
                    <span class="text-[9px] font-bold uppercase tracking-wider px-1.5 py-0.2 rounded bg-brand-primary text-white">Current</span>
                    @endif
                </div>
                <div class="text-sm font-bold text-gray-900 font-mono">
                    ₦{{ number_format($trend['disbursed'], 0) }}
                </div>
                <div class="text-[10px] text-gray-400 mt-1">
                    {{ $trend['disbursed'] > 0 ? 'Disbursed' : 'No transfers' }}
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Filters Bar -->
    <form action="{{ route('admin.accounting.payroll.index') }}" method="GET" class="bg-white rounded-2xl border border-gray-200 p-4 shadow-xs">
        <input type="hidden" name="month" value="{{ $selectedMonthStr }}">
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            <!-- Search -->
            <div class="sm:col-span-5 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search staff by name, email, role..." class="w-full pl-10 pr-4 py-2 rounded-xl border border-gray-200 text-sm focus:ring-brand-primary focus:border-brand-primary shadow-xs">
            </div>

            <!-- Status Filter -->
            <div class="sm:col-span-3">
                <select name="status" onchange="this.form.submit()" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-brand-primary focus:border-brand-primary shadow-xs bg-white">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>🟢 Active Salaries</option>
                    <option value="on_hold" {{ request('status') == 'on_hold' ? 'selected' : '' }}>🟡 On Hold</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>⚪ Inactive / Paused</option>
                </select>
            </div>

            <!-- Department Filter -->
            <div class="sm:col-span-3">
                <select name="department" onchange="this.form.submit()" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-brand-primary focus:border-brand-primary shadow-xs bg-white">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                    <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Reset -->
            <div class="sm:col-span-1 flex justify-end">
                <a href="{{ route('admin.accounting.payroll.index') }}" class="p-2 text-gray-400 hover:text-gray-600 rounded-xl hover:bg-gray-100 transition-colors" title="Reset Filters">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </a>
            </div>
        </div>
    </form>

    <!-- Staff Salaries Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/80">
                    <tr>
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-xs font-bold uppercase tracking-wider text-gray-600 sm:pl-6">Team Member</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-gray-600">Monthly Salary</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-gray-600">Schedule</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-gray-600">Beneficiary Account</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-gray-600">Status</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-gray-600">Effective Since</th>
                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6 text-right text-xs font-bold uppercase tracking-wider text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($salaries as $salary)
                    <tr class="hover:bg-gray-50/80 transition-colors group">
                        <!-- Member Info -->
                        <td class="py-4 pl-4 pr-3 sm:pl-6 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-brand-primary/20 to-purple-500/20 text-brand-primary flex items-center justify-center font-bold text-sm border border-brand-primary/10">
                                    {{ substr($salary->user->name ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-900 group-hover:text-brand-primary transition-colors">
                                        {{ $salary->user->name ?? 'N/A' }}
                                    </div>
                                    <div class="text-xs text-gray-500 flex items-center gap-2 mt-0.5">
                                        <span>{{ $salary->user->email ?? 'No email' }}</span>
                                        @if($salary->user && $salary->user->department)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-gray-100 text-gray-600">
                                            {{ $salary->user->department }}
                                        </span>
                                        @endif
                                    </div>
                                    @if($salary->designation)
                                    <div class="text-[11px] text-gray-400 font-medium mt-0.5">
                                        {{ $salary->designation }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Salary Amount -->
                        <td class="px-3 py-4 whitespace-nowrap">
                            <div class="text-base font-black text-gray-900 font-mono">
                                {{ $salary->formatted_total }}
                            </div>
                            @if($salary->incentive_amount > 0)
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="text-[11px] text-gray-500 font-mono">Base: {{ $salary->formatted_amount }}</span>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200 font-mono">
                                    +{{ $salary->formatted_incentive }} bonus
                                </span>
                            </div>
                            @else
                            <div class="text-[11px] text-gray-400 font-mono mt-0.5">
                                {{ $salary->formatted_annual_amount }} / yr
                            </div>
                            @endif
                        </td>

                        <!-- Schedule -->
                        <td class="px-3 py-4 whitespace-nowrap text-xs text-gray-600">
                            <div class="font-semibold text-gray-800 capitalize">{{ $salary->payment_cycle }}</div>
                            <div class="text-gray-400 mt-0.5">Pay Day: {{ $salary->payment_day ? $salary->payment_day . 'th of month' : 'End of month' }}</div>
                        </td>

                        <!-- Bank Details -->
                        <td class="px-3 py-4 whitespace-nowrap text-xs text-gray-600">
                            @php
                                $primaryBank = $salary->user ? ($salary->user->bankAccounts->where('is_primary', true)->first() ?? $salary->user->bankAccounts->first()) : null;
                            @endphp
                            @if($primaryBank)
                                <div class="font-semibold text-gray-800">{{ $primaryBank->bank_name ?? $primaryBank->bank_code }}</div>
                                <div class="text-gray-500 font-mono text-[11px]">{{ $primaryBank->account_number }} ({{ $primaryBank->account_name }})</div>
                            @else
                                <span class="inline-flex items-center text-amber-600 bg-amber-50 px-2 py-0.5 rounded text-[10px] font-semibold">
                                    ⚠️ No bank linked
                                </span>
                            @endif
                        </td>

                        <!-- Status -->
                        <td class="px-3 py-4 whitespace-nowrap">
                            @if($salary->status === 'active')
                            <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Active
                            </span>
                            @elseif($salary->status === 'on_hold')
                            <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                On Hold
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                Inactive
                            </span>
                            @endif
                        </td>

                        <!-- Effective Date -->
                        <td class="px-3 py-4 whitespace-nowrap text-xs text-gray-500 font-mono">
                            {{ $salary->effective_date ? $salary->effective_date->format('M d, Y') : 'N/A' }}
                        </td>

                        <!-- Actions -->
                        <td class="py-4 pl-3 pr-4 sm:pr-6 whitespace-nowrap text-right text-sm">
                            <div class="flex items-center justify-end gap-2">
                                <!-- Disburse / Pay Now Shortcut -->
                                <a href="{{ route('admin.accounting.salaries.create', ['user_id' => $salary->user_id, 'amount' => $salary->total_amount]) }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-all border border-emerald-200" title="Disburse via SafeHaven">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Pay
                                </a>

                                <!-- Edit Button -->
                                <button type="button" @click="openEditModal({{ json_encode($salary) }})" class="p-1.5 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors" title="Edit Salary">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>

                                <!-- Toggle Status -->
                                <form action="{{ route('admin.accounting.payroll.toggle-status', $salary) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="p-1.5 rounded-lg text-gray-500 hover:text-amber-600 hover:bg-amber-50 transition-colors" title="{{ $salary->status === 'active' ? 'Pause Salary' : 'Activate Salary' }}">
                                        @if($salary->status === 'active')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        @endif
                                    </button>
                                </form>

                                <!-- Delete -->
                                <form action="{{ route('admin.accounting.payroll.destroy', $salary) }}" method="POST" onsubmit="return confirm('Remove this salary record?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete Record">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-base font-bold text-gray-900">No staff salary records found</h3>
                                <p class="text-xs text-gray-500 mt-1 max-w-sm">Click "Record New Salary" above to assign agreed salaries to your team members and start tracking monthly payroll.</p>
                                <button type="button" @click="openCreateModal()" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-brand-primary text-white text-xs font-semibold rounded-xl hover:bg-brand-primary/90 transition-colors">
                                    + Record First Salary
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($salaries->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $salaries->links() }}
        </div>
        @endif
    </div>

    <!-- Record Salary Modal -->
    <div x-show="showCreateModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs" @click="showCreateModal = false"></div>

            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-100">
                <form action="{{ route('admin.accounting.payroll.store') }}" method="POST">
                    @csrf
                    <div class="bg-gray-50/80 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Record Staff Salary</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Assign an active agreed salary to a team member</p>
                        </div>
                        <button type="button" @click="showCreateModal = false" class="text-gray-400 hover:text-gray-600 p-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="p-6 space-y-4">
                        <!-- Select Staff Member -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Select Team Member <span class="text-red-500">*</span></label>
                            <select name="user_id" required class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm focus:ring-brand-primary focus:border-brand-primary bg-white shadow-xs">
                                <option value="">Choose a team member...</option>
                                @foreach($allUsers as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }}) - {{ $u->department ?? 'General' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Amount, Incentive & Currency -->
                        <div class="space-y-3">
                            <div class="grid grid-cols-3 gap-3">
                                <div class="col-span-2">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Base Monthly Salary <span class="text-red-500">*</span></label>
                                    <div class="relative flex items-stretch rounded-xl shadow-xs">
                                        <span class="inline-flex items-center px-3.5 rounded-l-xl border border-r-0 border-gray-300 bg-gray-100 text-gray-700 font-bold text-sm select-none" x-text="getCurrencySymbol(createCurrency)">₦</span>
                                        <input type="number" step="0.01" min="0" name="amount" x-model.number="createAmount" required placeholder="150000.00" class="block w-full min-w-0 flex-1 rounded-none rounded-r-xl border border-gray-300 py-2.5 text-sm focus:ring-brand-primary focus:border-brand-primary font-mono text-gray-900 bg-white" style="padding-left: 0.85rem; padding-right: 0.85rem;">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Currency</label>
                                    <select name="currency" x-model="createCurrency" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm focus:ring-brand-primary focus:border-brand-primary bg-white shadow-xs">
                                        <option value="NGN">NGN (₦)</option>
                                        <option value="USD">USD ($)</option>
                                        <option value="GBP">GBP (£)</option>
                                        <option value="EUR">EUR (€)</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Incentive / Bonus Amount <span class="text-xs text-gray-400 font-normal lowercase">(optional)</span></label>
                                <div class="relative flex items-stretch rounded-xl shadow-xs">
                                    <span class="inline-flex items-center px-3.5 rounded-l-xl border border-r-0 border-gray-300 bg-purple-50 text-purple-700 font-bold text-sm select-none" x-text="getCurrencySymbol(createCurrency)">₦</span>
                                    <input type="number" step="0.01" min="0" name="incentive_amount" x-model.number="createIncentive" placeholder="0.00" class="block w-full min-w-0 flex-1 rounded-none rounded-r-xl border border-gray-300 py-2.5 text-sm focus:ring-brand-primary focus:border-brand-primary font-mono text-gray-900 bg-white" style="padding-left: 0.85rem; padding-right: 0.85rem;">
                                </div>
                            </div>

                            <!-- Live Total Payout Calculation Strip -->
                            <div class="rounded-xl bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200/80 p-3 flex items-center justify-between text-xs" x-show="(createAmount > 0 || createIncentive > 0)">
                                <div>
                                    <span class="font-bold text-emerald-900 block">Total Monthly Compensation</span>
                                    <span class="text-[11px] text-emerald-700" x-text="'Base: ' + getCurrencySymbol(createCurrency) + Number(createAmount || 0).toLocaleString('en-US', {minimumFractionDigits: 2}) + (createIncentive > 0 ? ' + Incentive: ' + getCurrencySymbol(createCurrency) + Number(createIncentive || 0).toLocaleString('en-US', {minimumFractionDigits: 2}) : '')"></span>
                                </div>
                                <span class="font-black text-emerald-800 font-mono text-base" x-text="getCurrencySymbol(createCurrency) + (Number(createAmount || 0) + Number(createIncentive || 0)).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})">₦0.00</span>
                            </div>
                        </div>

                        <!-- Payment Cycle & Pay Day -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Payment Cycle</label>
                                <select name="payment_cycle" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm focus:ring-brand-primary focus:border-brand-primary bg-white shadow-xs">
                                    <option value="monthly">Monthly</option>
                                    <option value="bi-weekly">Bi-Weekly</option>
                                    <option value="weekly">Weekly</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Pay Day (Day of Month)</label>
                                <input type="number" min="1" max="31" name="payment_day" value="28" placeholder="28" class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm focus:ring-brand-primary focus:border-brand-primary shadow-xs">
                            </div>
                        </div>

                        <!-- Designation & Effective Date -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Role / Designation</label>
                                <input type="text" name="designation" placeholder="e.g. Senior Frontend Dev" class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm focus:ring-brand-primary focus:border-brand-primary shadow-xs">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Effective Date</label>
                                <input type="date" name="effective_date" value="{{ date('Y-m-d') }}" class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm focus:ring-brand-primary focus:border-brand-primary shadow-xs">
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm focus:ring-brand-primary focus:border-brand-primary bg-white shadow-xs">
                                <option value="active">🟢 Active (Counts towards monthly budget)</option>
                                <option value="on_hold">🟡 On Hold</option>
                                <option value="inactive">⚪ Inactive</option>
                            </select>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Notes / Terms (Optional)</label>
                            <textarea name="notes" rows="2" placeholder="e.g. Includes remote internet allowance, reviewed quarterly..." class="w-full rounded-xl border border-gray-300 px-3.5 py-2 text-sm focus:ring-brand-primary focus:border-brand-primary shadow-xs"></textarea>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-3.5 border-t border-gray-100 flex items-center justify-end gap-3">
                        <button type="button" @click="showCreateModal = false" class="px-4 py-2 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl text-sm font-semibold text-white bg-brand-primary hover:bg-brand-primary/90 shadow-sm transition-all">
                            Save Salary Record
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Salary Modal -->
    <div x-show="showEditModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs" @click="showEditModal = false"></div>

            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-100">
                <form :action="editActionUrl" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="bg-gray-50/80 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Edit Staff Salary</h3>
                            <p class="text-xs text-gray-500 mt-0.5" x-text="editStaffName"></p>
                        </div>
                        <button type="button" @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 p-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="p-6 space-y-4">
                        <!-- Amount, Incentive & Currency -->
                        <div class="space-y-3">
                            <div class="grid grid-cols-3 gap-3">
                                <div class="col-span-2">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Base Monthly Salary <span class="text-red-500">*</span></label>
                                    <div class="relative flex items-stretch rounded-xl shadow-xs">
                                        <span class="inline-flex items-center px-3.5 rounded-l-xl border border-r-0 border-gray-300 bg-gray-100 text-gray-700 font-bold text-sm select-none" x-text="getCurrencySymbol(editData.currency)">₦</span>
                                        <input type="number" step="0.01" min="0" name="amount" x-model.number="editData.amount" required class="block w-full min-w-0 flex-1 rounded-none rounded-r-xl border border-gray-300 py-2.5 text-sm focus:ring-brand-primary focus:border-brand-primary font-mono text-gray-900 bg-white" style="padding-left: 0.85rem; padding-right: 0.85rem;">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Currency</label>
                                    <select name="currency" x-model="editData.currency" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm focus:ring-brand-primary focus:border-brand-primary bg-white shadow-xs">
                                        <option value="NGN">NGN (₦)</option>
                                        <option value="USD">USD ($)</option>
                                        <option value="GBP">GBP (£)</option>
                                        <option value="EUR">EUR (€)</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Monthly Incentive / Bonus <span class="text-xs text-gray-400 font-normal lowercase">(optional)</span></label>
                                <div class="relative flex items-stretch rounded-xl shadow-xs">
                                    <span class="inline-flex items-center px-3.5 rounded-l-xl border border-r-0 border-gray-300 bg-purple-50 text-purple-700 font-bold text-sm select-none" x-text="getCurrencySymbol(editData.currency)">₦</span>
                                    <input type="number" step="0.01" min="0" name="incentive_amount" x-model.number="editData.incentive_amount" placeholder="0.00" class="block w-full min-w-0 flex-1 rounded-none rounded-r-xl border border-gray-300 py-2.5 text-sm focus:ring-brand-primary focus:border-brand-primary font-mono text-gray-900 bg-white" style="padding-left: 0.85rem; padding-right: 0.85rem;">
                                </div>
                            </div>

                            <!-- Live Total Payout Calculation Strip -->
                            <div class="rounded-xl bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200/80 p-3 flex items-center justify-between text-xs" x-show="(editData.amount > 0 || editData.incentive_amount > 0)">
                                <div>
                                    <span class="font-bold text-emerald-900 block">Total Monthly Compensation</span>
                                    <span class="text-[11px] text-emerald-700" x-text="'Base: ' + getCurrencySymbol(editData.currency) + Number(editData.amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2}) + (editData.incentive_amount > 0 ? ' + Incentive: ' + getCurrencySymbol(editData.currency) + Number(editData.incentive_amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2}) : '')"></span>
                                </div>
                                <span class="font-black text-emerald-800 font-mono text-base" x-text="getCurrencySymbol(editData.currency) + (Number(editData.amount || 0) + Number(editData.incentive_amount || 0)).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})">₦0.00</span>
                            </div>
                        </div>

                        <!-- Payment Cycle & Pay Day -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Payment Cycle</label>
                                <select name="payment_cycle" x-model="editData.payment_cycle" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm focus:ring-brand-primary focus:border-brand-primary bg-white shadow-xs">
                                    <option value="monthly">Monthly</option>
                                    <option value="bi-weekly">Bi-Weekly</option>
                                    <option value="weekly">Weekly</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Pay Day (Day of Month)</label>
                                <input type="number" min="1" max="31" name="payment_day" x-model="editData.payment_day" class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm focus:ring-brand-primary focus:border-brand-primary shadow-xs">
                            </div>
                        </div>

                        <!-- Designation & Effective Date -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Role / Designation</label>
                                <input type="text" name="designation" x-model="editData.designation" class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm focus:ring-brand-primary focus:border-brand-primary shadow-xs">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Effective Date</label>
                                <input type="date" name="effective_date" x-model="editData.effective_date" class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm focus:ring-brand-primary focus:border-brand-primary shadow-xs">
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Status</label>
                            <select name="status" x-model="editData.status" class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm focus:ring-brand-primary focus:border-brand-primary bg-white shadow-xs">
                                <option value="active">🟢 Active (Counts towards monthly budget)</option>
                                <option value="on_hold">🟡 On Hold</option>
                                <option value="inactive">⚪ Inactive</option>
                            </select>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Notes / Terms</label>
                            <textarea name="notes" x-model="editData.notes" rows="2" class="w-full rounded-xl border border-gray-300 px-3.5 py-2 text-sm focus:ring-brand-primary focus:border-brand-primary shadow-xs"></textarea>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-3.5 border-t border-gray-100 flex items-center justify-end gap-3">
                        <button type="button" @click="showEditModal = false" class="px-4 py-2 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl text-sm font-semibold text-white bg-brand-primary hover:bg-brand-primary/90 shadow-sm transition-all">
                            Update Salary
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function staffPayroll() {
        return {
            showCreateModal: false,
            showEditModal: false,
            editStaffName: '',
            editActionUrl: '',
            createCurrency: 'NGN',
            createAmount: '',
            createIncentive: '',
            currencySymbols: {
                'NGN': '₦',
                'USD': '$',
                'GBP': '£',
                'EUR': '€'
            },
            getCurrencySymbol(code) {
                return this.currencySymbols[code] || '₦';
            },
            editData: {
                amount: '',
                incentive_amount: '',
                currency: 'NGN',
                payment_cycle: 'monthly',
                payment_day: 28,
                effective_date: '',
                status: 'active',
                designation: '',
                notes: ''
            },
            openCreateModal() {
                this.createCurrency = 'NGN';
                this.createAmount = '';
                this.createIncentive = '';
                this.showCreateModal = true;
            },
            openEditModal(salary) {
                this.editStaffName = (salary.user ? salary.user.name : 'Staff Member');
                this.editActionUrl = `{{ url('admin/accounting/payroll') }}/${salary.id}`;
                this.editData = {
                    amount: salary.amount,
                    incentive_amount: salary.incentive_amount || '',
                    currency: salary.currency || 'NGN',
                    payment_cycle: salary.payment_cycle || 'monthly',
                    payment_day: salary.payment_day || 28,
                    effective_date: salary.effective_date ? salary.effective_date.substring(0, 10) : '',
                    status: salary.status || 'active',
                    designation: salary.designation || '',
                    notes: salary.notes || ''
                };
                this.showEditModal = true;
            }
        };
    }
</script>
@endsection
