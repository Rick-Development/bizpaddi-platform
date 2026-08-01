@extends('admin::layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard-container" x-data="dashboardApp()">
    <!-- Header Section -->
    <div class="dashboard-header">
        <div class="header-content">
            <div class="header-title-section">
                <h1 class="dashboard-title">
                    <span class="title-accent"></span>
                    <span class="title-text">Dashboard Overview</span>
                </h1>
                <p class="dashboard-subtitle">Real-time insights and analytics</p>
            </div>
            <div class="header-actions">
                <button @click="refreshData" class="btn-refresh" title="Refresh">
                    <svg class="icon-refresh" :class="{ 'spinning': isRefreshing }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>
                <a href="{{ route('admin.settings.index') }}" class="btn-settings">
                    <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="btn-text">Settings</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <!-- Revenue Card -->
        <div class="stat-card stat-card-revenue">
            <div class="stat-card-bg"></div>
            <div class="stat-card-content">
                <div class="stat-header">
                    <div class="stat-icon stat-icon-revenue">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="stat-badge {{ $revenueGrowth >= 0 ? 'stat-badge-positive' : 'stat-badge-negative' }}">
                        {{ $revenueGrowth >= 0 ? '+' : '' }}{{ number_format($revenueGrowth, 1) }}%
                    </span>
                </div>
                <div class="stat-body">
                    <p class="stat-label">Total Revenue</p>
                    <h3 class="stat-value">₦{{ number_format($totalRevenue, 0) }}</h3>
                    <p class="stat-description">Across all modules</p>
                </div>
            </div>
        </div>

        <!-- Clients Card -->
        <div class="stat-card stat-card-clients">
            <div class="stat-card-bg"></div>
            <div class="stat-card-content">
                <div class="stat-header">
                    <div class="stat-icon stat-icon-clients">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                <div class="stat-body">
                    <p class="stat-label">Active Clients</p>
                    <h3 class="stat-value">{{ number_format($activeClients) }}</h3>
                    <p class="stat-description">Verified partners</p>
                </div>
            </div>
        </div>

        <!-- Visitors Card -->
        <div class="stat-card stat-card-visitors">
            <div class="stat-card-bg"></div>
            <div class="stat-card-content">
                <div class="stat-header">
                    <div class="stat-icon stat-icon-visitors">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                    <span class="stat-badge stat-badge-live">
                        <span class="pulse-dot"></span>
                        Live
                    </span>
                </div>
                <div class="stat-body">
                    <p class="stat-label">Total Visitors</p>
                    <h3 class="stat-value">{{ number_format($totalVisitors) }}</h3>
                    <p class="stat-description">Global sessions tracked</p>
                </div>
            </div>
        </div>

        <!-- AI Usage Card -->
        <div class="stat-card stat-card-ai">
            <div class="stat-card-bg"></div>
            <div class="stat-card-content">
                <div class="stat-header">
                    <div class="stat-icon stat-icon-ai">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>
                <div class="stat-body">
                    <p class="stat-label">AI Requests</p>
                    <h3 class="stat-value">{{ number_format($aiUsage) }}</h3>
                    <p class="stat-description">Automated intelligence</p>
                </div>
            </div>
        </div>

        <!-- Salary Disbursement Card -->
        <div class="stat-card stat-card-salary">
            <div class="stat-card-bg"></div>
            <div class="stat-card-content">
                <div class="stat-header">
                    <div class="stat-icon stat-icon-salary">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                <div class="stat-body">
                    <p class="stat-label">Total Salaries</p>
                    <h3 class="stat-value">₦{{ number_format($totalSalaries, 0) }}</h3>
                    <p class="stat-description">Disbursements to date</p>
                </div>
            </div>
        </div>

        <!-- SafeHaven Balance Card -->
        <div class="stat-card stat-card-safehaven" style="background: linear-gradient(135deg, #191C47 0%, #2a2e5d 100%);">
            <div class="stat-card-bg" style="background: rgba(255, 255, 255, 0.05);"></div>
            <div class="stat-card-content">
                <div class="stat-header">
                    <div class="stat-icon stat-icon-safehaven" style="background: rgba(255, 255, 255, 0.1); color: #fff;">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="stat-badge stat-badge-live">
                        <span class="pulse-dot"></span>
                        Live
                    </span>
                </div>
                <div class="stat-body">
                    <p class="stat-label" style="color: rgba(255, 255, 255, 0.7);">Bank Balance</p>
                    <h3 class="stat-value" style="color: #fff;">₦{{ number_format($safeHavenBalance ?? 0, 2) }}</h3>
                    <p class="stat-description" style="color: rgba(255, 255, 255, 0.6);">Main Account</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="content-grid">
        <div class="chart-card chart-card-large">
            <div class="card-header">
                <div class="card-title-section">
                    <h3 class="card-title">Financial Flux</h3>
                    <p class="card-subtitle">Revenue vs Salary trends</p>
                </div>
                <div class="chart-legend">
                    <span class="legend-item">
                        <span class="legend-dot legend-dot-primary"></span>
                        Revenue
                    </span>
                    <span class="legend-item">
                        <span class="legend-dot legend-dot-salary"></span>
                        Salaries
                    </span>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="financialTrendChart"></canvas>
            </div>
        </div>

        <!-- Regional Distribution -->
        <div class="chart-card chart-card-sidebar">
            <div class="card-header">
                <h3 class="card-title">Regional Distribution</h3>
            </div>
            <div class="regional-list">
                @forelse($topCountries as $country => $count)
                <div class="regional-item">
                    <div class="regional-info">
                        <span class="regional-name">{{ $country ?: 'Global' }}</span>
                        <span class="regional-percentage">{{ number_format(($count / max(1, $totalVisitors)) * 100, 1) }}%</span>
                    </div>
                    <div class="regional-bar">
                        <div class="regional-bar-fill" style="width: {{ ($count / max(1, $totalVisitors)) * 100 }}%"></div>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <p>No regional data available</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- AI Timeline Chart -->
        <div class="chart-card">
            <div class="card-header">
                <h3 class="card-title">AI Activity Timeline</h3>
            </div>
            <div class="chart-container chart-container-small">
                <canvas id="aiChart"></canvas>
            </div>
        </div>

        <!-- Device Distribution -->
        <div class="chart-card">
            <div class="card-header">
                <h3 class="card-title">Device Distribution</h3>
            </div>
            <div class="device-chart-wrapper">
                <div class="chart-container chart-container-small">
                    <canvas id="deviceChart"></canvas>
                </div>
                <div class="device-stats">
                    @foreach($deviceDistribution as $device => $count)
                    <div class="device-stat-item">
                        <p class="device-label">{{ $device }}</p>
                        <p class="device-value">{{ number_format(($count / max(1, array_sum($deviceDistribution))) * 100, 1) }}%</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recent Inquiries -->
        <div class="chart-card">
            <div class="card-header">
                <div class="card-title-section">
                    <h3 class="card-title">Recent Inquiries</h3>
                </div>
                <a href="{{ route('admin.contact-submissions.index') }}" class="btn-view-all">
                    View
                </a>
            </div>
            <div class="inquiries-list">
                @forelse($recentInquiries as $inquiry)
                <div class="inquiry-item-mini">
                    <div class="inquiry-avatar-mini">{{ substr($inquiry->name, 0, 1) }}</div>
                    <div class="inquiry-info-mini">
                        <p class="inquiry-name-mini">{{ $inquiry->name }}</p>
                        <p class="inquiry-time-mini">{{ $inquiry->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <p class="text-xs text-gray-400">No inquiries</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Salary Payments -->
        <div class="chart-card chart-card-wide">
            <div class="card-header">
                <div class="card-title-section">
                    <h3 class="card-title">Recent Salary Disbursements</h3>
                    <p class="card-subtitle">Latest payroll transactions</p>
                </div>
                <a href="{{ route('admin.accounting.salaries.index') }}" class="btn-view-all">
                    View All
                    <svg class="icon-arrow" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
            <div class="overflow-hidden border border-gray-100 rounded-xl">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Team Member</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Amount</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Date</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 bg-white">
                        @forelse($recentSalaries as $salary)
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-brand-primary/10 flex items-center justify-center text-[10px] font-bold text-brand-primary">
                                        {{ substr($salary->user->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-semibold text-gray-900">{{ $salary->user->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="text-sm font-bold text-gray-900">₦{{ number_format($salary->amount, 0) }}</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-500">
                                {{ $salary->payment_date->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $salary->status === 'successful' ? 'bg-green-50 text-green-700' : 'bg-blue-50 text-blue-700' }}">
                                    {{ $salary->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-400">No salary history.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Conversion Funnel -->
        <div class="chart-card">
            <div class="card-header">
                <h3 class="card-title">Lead Conversion</h3>
            </div>
            <div class="conversion-wrapper">
                <div class="chart-container chart-container-doughnut">
                    <canvas id="conversionChart"></canvas>
                    <div class="doughnut-center">
                        <span class="doughnut-value">{{ array_sum($leadStatusBreakdown) }}</span>
                        <span class="doughnut-label">Total</span>
                    </div>
                </div>
                <div class="conversion-stats">
                    @foreach($leadStatusBreakdown as $status => $count)
                    <div class="conversion-stat-item">
                        <p class="conversion-label">{{ ucfirst($status) }}</p>
                        <p class="conversion-value">{{ $count }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Project Sentinel -->
        <div class="chart-card chart-card-large">
            <div class="card-header">
                <div class="card-title-section">
                    <h3 class="card-title">Project Delta</h3>
                    <p class="card-subtitle">Operational health and milestone tracking</p>
                </div>
                <div class="health-indicators">
                    <div class="health-tag tag-success">
                        <span class="health-value">{{ $projectInsights['on_track'] }}</span>
                        <span class="health-label">On Track</span>
                    </div>
                    <div class="health-tag tag-warning">
                        <span class="health-value">{{ $projectInsights['delayed'] }}</span>
                        <span class="health-label">Delayed</span>
                    </div>
                </div>
            </div>
            <div class="milestone-timeline">
                <h4 class="section-title-mini">Critical Milestones</h4>
                <div class="milestone-grid">
                    @forelse($projectInsights['upcoming_milestones'] as $milestone)
                    <div class="milestone-card-premium">
                        <div class="milestone-indicator {{ $milestone->due_date < now() ? 'overdue' : '' }}"></div>
                        <div class="milestone-content">
                            <p class="milestone-project">{{ $milestone->project->name }}</p>
                            <h5 class="milestone-name">{{ $milestone->name }}</h5>
                            <div class="milestone-meta">
                                <span class="milestone-date">
                                    <svg class="icon-mini" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    {{ $milestone->due_date->format('M d, Y') }}
                                </span>
                                <span class="milestone-status status-{{ $milestone->status }}">
                                    {{ ucfirst($milestone->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state-mini">
                        <p>No upcoming milestones</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Investor Sentinel -->
        <div class="chart-card chart-card-sidebar">
            <div class="card-header">
                <h3 class="card-title">Investor Sentinel</h3>
            </div>
            <div class="investor-hub">
                <div class="stock-ticker-card">
                    <div class="ticker-header">
                        <span class="ticker-symbol">XBC</span>
                        <span class="ticker-badge {{ $investorInsights['price_change'] >= 0 ? 'badge-up' : 'badge-down' }}">
                            {{ number_format($investorInsights['price_change'], 2) }}%
                        </span>
                    </div>
                    <div class="ticker-body">
                        <h4 class="ticker-price">₦{{ number_format($investorInsights['stock_price'], 2) }}</h4>
                        <p class="ticker-label">Market Valuation</p>
                    </div>
                </div>
                
                <div class="upcoming-event-card">
                    <p class="event-label">Next Shareholder Meeting</p>
                    @if($investorInsights['next_meeting'])
                        <h5 class="event-date">{{ $investorInsights['next_meeting']->meeting_date->format('M d, Y') }}</h5>
                        <p class="event-title">{{ $investorInsights['next_meeting']->title }}</p>
                    @else
                        <p class="event-empty">No meetings scheduled</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Financial Integrity -->
        <div class="chart-card">
            <div class="card-header">
                <h3 class="card-title">Financial Integrity</h3>
            </div>
            <div class="financial-stats-grid">
                <div class="fin-stat-card card-danger">
                    <div class="fin-stat-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div class="fin-stat-info">
                        <p class="fin-label">Overdue Invoices</p>
                        <h5 class="fin-value">₦{{ number_format($financialHealth['overdue_invoices'], 0) }}</h5>
                    </div>
                </div>
                <div class="fin-stat-card card-primary">
                    <div class="fin-stat-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div class="fin-stat-info">
                        <p class="fin-label">Pending Quotes</p>
                        <h5 class="fin-value">{{ $financialHealth['pending_quotes'] }}</h5>
                    </div>
                </div>
            </div>
            <div class="fin-footer">
                <p class="fin-status">All systems encrypted and secure</p>
            </div>
        </div>
    </div>
</div>

<style>
/* Modern Dashboard Styles */
.dashboard-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
    padding: 1.5rem;
}

/* Header */
.dashboard-header {
    background: white;
    border-radius: 1.5rem;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.header-title-section {
    flex: 1;
    min-width: 200px;
}

.dashboard-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1.75rem;
    font-weight: 800;
    color: #191C47;
    margin: 0;
}

.title-accent {
    width: 4px;
    height: 2rem;
    background: linear-gradient(180deg, #FB4C4B 0%, #191C47 100%);
    border-radius: 2px;
}

.dashboard-subtitle {
    color: #64748b;
    font-size: 0.875rem;
    margin: 0.25rem 0 0 0;
    font-weight: 500;
}

.header-actions {
    display: flex;
    gap: 0.75rem;
    align-items: center;
}

.btn-refresh {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.75rem;
    border: none;
    background: #f1f5f9;
    color: #64748b;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.btn-refresh:hover {
    background: #FB4C4B;
    color: white;
    transform: translateY(-2px);
}

.icon-refresh {
    width: 1.25rem;
    height: 1.25rem;
}

.icon-refresh.spinning {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.btn-settings {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1.25rem;
    background: #191C47;
    color: white;
    border-radius: 0.75rem;
    text-decoration: none;
    font-weight: 700;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(25, 28, 71, 0.2);
}

.btn-settings:hover {
    background: #FB4C4B;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(251, 76, 75, 0.3);
}

.btn-settings .icon {
    width: 1rem;
    height: 1rem;
}

.btn-text {
    display: none;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 1.5rem;
    padding: 1.75rem;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
}

.stat-card-bg {
    position: absolute;
    top: -50%;
    right: -20%;
    width: 200px;
    height: 200px;
    border-radius: 50%;
    opacity: 0.05;
    transition: all 0.5s ease;
}

.stat-card:hover .stat-card-bg {
    transform: scale(1.2);
    opacity: 0.08;
}

.stat-card-revenue .stat-card-bg {
    background: #FB4C4B;
}

.stat-card-clients .stat-card-bg {
    background: #191C47;
}

.stat-card-visitors .stat-card-bg {
    background: #FB4C4B;
}

.stat-card-ai .stat-card-bg {
    background: #191C47;
}

.stat-card-content {
    position: relative;
    z-index: 1;
}

.stat-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.25rem;
}

.stat-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.stat-icon svg {
    width: 1.5rem;
    height: 1.5rem;
}

.stat-icon-revenue {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #FB4C4B;
}

.stat-icon-clients {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #191C47;
}

.stat-icon-visitors {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #FB4C4B;
}

.stat-icon-ai {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #191C47;
}

.stat-card:hover .stat-icon {
    transform: scale(1.1) rotate(5deg);
}

.stat-badge {
    padding: 0.375rem 0.75rem;
    border-radius: 0.5rem;
    font-size: 0.75rem;
    font-weight: 700;
}

.stat-badge-positive {
    background: #dcfce7;
    color: #16a34a;
}

.stat-badge-negative {
    background: #fee2e2;
    color: #dc2626;
}

.stat-badge-live {
    background: #f1f5f9;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.pulse-dot {
    width: 0.5rem;
    height: 0.5rem;
    background: #22c55e;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.5;
        transform: scale(1.2);
    }
}

.stat-body {
    margin-top: 0.5rem;
}

.stat-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin: 0 0 0.5rem 0;
}

.stat-value {
    font-size: 2rem;
    font-weight: 800;
    color: #191C47;
    margin: 0 0 0.5rem 0;
    line-height: 1;
}

.stat-description {
    font-size: 0.875rem;
    color: #64748b;
    margin: 0;
    font-weight: 500;
}

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: repeat(12, 1fr);
    gap: 1.5rem;
}

.chart-card {
    background: white;
    border-radius: 1.5rem;
    padding: 1.75rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    grid-column: span 12;
}

.chart-card-large {
    grid-column: span 12;
}

.chart-card-sidebar {
    grid-column: span 12;
}

.chart-card-wide {
    grid-column: span 12;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.card-title-section {
    flex: 1;
}

.card-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #191C47;
    margin: 0;
}

.card-subtitle {
    font-size: 0.875rem;
    color: #64748b;
    margin: 0.25rem 0 0 0;
}

.chart-legend {
    display: flex;
    gap: 1.5rem;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #64748b;
    font-weight: 600;
}

.legend-dot {
    width: 0.75rem;
    height: 0.75rem;
    border-radius: 50%;
}

.legend-dot-primary {
    background: #FB4C4B;
}

.legend-dot-secondary {
    background: #191C47;
}

.chart-container {
    position: relative;
    height: 400px;
}

.chart-container-small {
    height: 250px;
}

.chart-container-doughnut {
    height: 200px;
    position: relative;
}

/* Regional Distribution */
.regional-list {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.regional-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.regional-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.regional-name {
    font-size: 0.875rem;
    font-weight: 600;
    color: #191C47;
}

.regional-percentage {
    font-size: 0.875rem;
    font-weight: 700;
    color: #FB4C4B;
}

.regional-bar {
    width: 100%;
    height: 0.5rem;
    background: #f1f5f9;
    border-radius: 0.25rem;
    overflow: hidden;
}

.regional-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, #FB4C4B 0%, #191C47 100%);
    border-radius: 0.25rem;
    transition: width 1s ease;
}

/* Device Chart */
.device-chart-wrapper {
    display: flex;
    gap: 2rem;
    align-items: center;
}

.device-stats {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.device-stat-item {
    padding: 1rem;
    background: #f8fafc;
    border-radius: 0.75rem;
    transition: all 0.3s ease;
}

.device-stat-item:hover {
    background: #191C47;
}

.device-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #64748b;
    margin: 0 0 0.25rem 0;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.device-stat-item:hover .device-label {
    color: rgba(255, 255, 255, 0.7);
}

.device-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: #191C47;
    margin: 0;
}

.device-stat-item:hover .device-value {
    color: white;
}

/* Inquiries Grid */
.inquiries-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1rem;
}

.inquiry-card {
    display: flex;
    gap: 1rem;
    padding: 1.25rem;
    background: #f8fafc;
    border-radius: 1rem;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.inquiry-card:hover {
    background: white;
    border-color: #FB4C4B;
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(251, 76, 75, 0.1);
}

.inquiry-avatar {
    width: 3rem;
    height: 3rem;
    min-width: 3rem;
    border-radius: 0.75rem;
    background: linear-gradient(135deg, #FB4C4B 0%, #191C47 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    font-weight: 700;
    transition: all 0.3s ease;
}

.inquiry-card:hover .inquiry-avatar {
    transform: rotate(5deg) scale(1.1);
}

.inquiry-content {
    flex: 1;
    min-width: 0;
}

.inquiry-name {
    font-size: 0.9375rem;
    font-weight: 700;
    color: #191C47;
    margin: 0 0 0.25rem 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.inquiry-subject {
    font-size: 0.8125rem;
    color: #64748b;
    margin: 0 0 0.75rem 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.inquiry-meta {
    display: flex;
    gap: 0.75rem;
    align-items: center;
}

.inquiry-status {
    font-size: 0.6875rem;
    font-weight: 700;
    padding: 0.25rem 0.625rem;
    border-radius: 0.375rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-new {
    background: #fee2e2;
    color: #dc2626;
}

.status-resolved {
    background: #dcfce7;
    color: #16a34a;
}

.inquiry-time {
    font-size: 0.75rem;
    color: #94a3b8;
    font-weight: 500;
}

/* Conversion */
.conversion-wrapper {
    display: flex;
    gap: 2rem;
    align-items: center;
}

.doughnut-center {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    pointer-events: none;
}

.doughnut-value {
    display: block;
    font-size: 2rem;
    font-weight: 800;
    color: #191C47;
}

.doughnut-label {
    display: block;
    font-size: 0.75rem;
    color: #64748b;
    font-weight: 600;
    text-transform: uppercase;
}

.conversion-stats {
    flex: 1;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
}

.conversion-stat-item {
    padding: 1rem;
    background: #f8fafc;
    border-radius: 0.75rem;
}

.conversion-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #64748b;
    margin: 0 0 0.25rem 0;
    text-transform: uppercase;
}

.conversion-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: #191C47;
    margin: 0;
}

/* Operations Hub Shared Styles */
.section-title-mini {
    font-size: 0.75rem;
    font-weight: 800;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin: 1.5rem 0 1rem 0;
}

/* Health Indicators */
.health-indicators {
    display: flex;
    gap: 0.75rem;
}

.health-tag {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 0.75rem;
    background: #f8fafc;
}

.health-value {
    font-size: 1.25rem;
    font-weight: 800;
    color: #191C47;
}

.health-label {
    font-size: 0.7rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
}

.tag-success { border-left: 4px solid #22c55e; }
.tag-warning { border-left: 4px solid #f59e0b; }

/* Milestone Timeline */
.milestone-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1rem;
}

.milestone-card-premium {
    display: flex;
    gap: 1rem;
    padding: 1.25rem;
    background: #f8fafc;
    border-radius: 1.25rem;
    position: relative;
    transition: all 0.3s ease;
    border: 1px solid #e2e8f0;
}

.milestone-card-premium:hover {
    transform: translateY(-2px);
    border-color: #FB4C4B;
    background: white;
}

.milestone-indicator {
    width: 4px;
    height: 100%;
    position: absolute;
    left: 0;
    top: 0;
    background: #191C47;
    border-radius: 4px 0 0 4px;
}

.milestone-indicator.overdue {
    background: #FB4C4B;
    animation: flash 2s infinite;
}

@keyframes flash {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.milestone-project {
    font-size: 0.65rem;
    font-weight: 800;
    color: #FB4C4B;
    text-transform: uppercase;
    margin: 0;
}

.milestone-name {
    font-size: 1rem;
    font-weight: 700;
    color: #191C47;
    margin: 0.25rem 0 0.5rem 0;
}

.milestone-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.milestone-date {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.75rem;
    color: #64748b;
    font-weight: 600;
}

.icon-mini { width: 0.875rem; height: 0.875rem; }

/* Investor Hub */
.investor-hub {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.stock-ticker-card {
    background: #191C47;
    padding: 1.5rem;
    border-radius: 1.25rem;
    color: white;
}

.ticker-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.ticker-symbol {
    font-weight: 800;
    font-size: 0.875rem;
    opacity: 0.8;
}

.ticker-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 0.5rem;
    font-size: 0.7rem;
    font-weight: 800;
}

.badge-up { background: #22c55e; }
.badge-down { background: #FB4C4B; }

.ticker-price {
    font-size: 2rem;
    font-weight: 800;
    margin: 0;
}

.ticker-label {
    font-size: 0.75rem;
    opacity: 0.6;
    margin: 0.25rem 0 0 0;
}

.upcoming-event-card {
    background: #f8fafc;
    padding: 1.25rem;
    border-radius: 1.25rem;
    border-left: 4px solid #FB4C4B;
}

.event-label {
    font-size: 0.65rem;
    font-weight: 800;
    color: #64748b;
    text-transform: uppercase;
    margin: 0 0 0.5rem 0;
}

.event-date {
    font-size: 1.125rem;
    font-weight: 800;
    color: #191C47;
    margin: 0;
}

.event-title {
    font-size: 0.875rem;
    color: #64748b;
    margin: 0.25rem 0 0 0;
}

/* Financial Stats */
.financial-stats-grid {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.fin-stat-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem;
    background: #f8fafc;
    border-radius: 1rem;
    transition: all 0.3s ease;
}

.fin-stat-card:hover { background: white; transform: scale(1.02); }

.fin-stat-icon {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.card-danger .fin-stat-icon { background: #fee2e2; color: #FB4C4B; }
.card-primary .fin-stat-icon { background: #dbeafe; color: #191C47; }

.fin-stat-icon svg { width: 1.25rem; height: 1.25rem; }

.fin-label {
    font-size: 0.75rem;
    font-weight: 700;
    color: #64748b;
    margin: 0;
}

.fin-value {
    font-size: 1.25rem;
    font-weight: 800;
    color: #191C47;
    margin: 0;
}

.fin-footer {
    margin-top: 1.5rem;
    padding: 1rem;
    background: #f1f5f9;
    border-radius: 0.75rem;
    text-align: center;
}

.fin-status {
    font-size: 0.7rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    margin: 0;
}

/* Buttons */
.btn-view-all {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1rem;
    background: #f1f5f9;
    color: #191C47;
    border-radius: 0.75rem;
    text-decoration: none;
    font-weight: 700;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.btn-view-all:hover {
    background: #191C47;
    color: white;
}

.icon-arrow {
    width: 1rem;
    height: 1rem;
}

/* Empty State */
.empty-state {
    padding: 3rem 1rem;
    text-align: center;
    color: #94a3b8;
}

.empty-state-full {
    grid-column: 1 / -1;
}

.empty-icon {
    width: 4rem;
    height: 4rem;
    color: #cbd5e1;
    margin: 0 auto 1rem;
}

.empty-state p {
    font-size: 0.875rem;
    font-weight: 600;
    margin: 0;
}

.stat-card-salary .stat-card-bg {
    background: #10b981;
}

.stat-icon-salary {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #10b981;
}

.legend-dot-salary {
    background: #191C47;
}

.inquiry-item-mini {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: #f8fafc;
    border-radius: 0.75rem;
    margin-bottom: 0.5rem;
}

.inquiry-avatar-mini {
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    background: #191C47;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 700;
}

.inquiry-name-mini {
    font-size: 0.8125rem;
    font-weight: 700;
    color: #191C47;
    margin: 0;
}

.inquiry-time-mini {
    font-size: 0.65rem;
    color: #94a3b8;
    margin: 0;
}

/* Responsive Design */
@media (min-width: 640px) {
    .btn-text {
        display: inline;
    }
}

@media (min-width: 768px) {
    .dashboard-container {
        padding: 2rem;
    }

    .dashboard-header {
        padding: 2rem;
    }

    .chart-card {
        grid-column: span 6;
    }

    .chart-card-large {
        grid-column: span 8;
    }

    .chart-card-sidebar {
        grid-column: span 4;
    }
}

@media (min-width: 1024px) {
    .dashboard-container {
        padding: 2.5rem;
    }

    .stats-grid {
        grid-template-columns: repeat(4, 1fr);
    }

    .chart-card-wide {
        grid-column: span 8;
    }
}

@media (max-width: 767px) {
    .device-chart-wrapper {
        flex-direction: column;
    }

    .conversion-wrapper {
        flex-direction: column;
    }

    .conversion-stats {
        width: 100%;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function dashboardApp() {
    return {
        isRefreshing: false,

        init() {
            this.initCharts();
        },

        refreshData() {
            this.isRefreshing = true;
            setTimeout(() => {
                window.location.reload();
            }, 500);
        },

        initCharts() {
            this.initFinancialTrendChart();
            this.initAiChart();
            this.initDeviceChart();
            this.initConversionChart();
        },

        initFinancialTrendChart() {
            const ctx = document.getElementById('financialTrendChart');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($revenueChart['labels']),
                    datasets: [
                        {
                            label: 'Revenue',
                            data: @json($revenueChart['data']),
                            borderColor: '#FB4C4B',
                            backgroundColor: 'rgba(251, 76, 75, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                        },
                        {
                            label: 'Salaries',
                            data: @json($salaryChart['data']),
                            borderColor: '#191C47',
                            backgroundColor: 'rgba(25, 28, 71, 0.05)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { intersect: false, mode: 'index' },
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            ticks: {
                                font: { size: 10, weight: '600' },
                                color: '#64748b',
                                callback: function(value) { return '₦' + value.toLocaleString(); }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10, weight: '700' }, color: '#191C47' }
                        }
                    }
                }
            });
        },

        initAiChart() {
            const ctx = document.getElementById('aiChart');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($aiHistory['labels']),
                    datasets: [{
                        data: @json($aiHistory['data']),
                        borderColor: '#191C47',
                        borderWidth: 3,
                        tension: 0.4,
                        pointRadius: 5,
                        pointBackgroundColor: '#FB4C4B',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 3,
                        fill: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { weight: '700', size: 11 },
                                color: '#64748b'
                            }
                        },
                        y: {
                            grid: { color: '#f1f5f9' },
                            ticks: {
                                font: { weight: '600', size: 11 },
                                color: '#64748b'
                            }
                        }
                    }
                }
            });
        },

        initDeviceChart() {
            const ctx = document.getElementById('deviceChart');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: @json(array_keys($deviceDistribution)),
                    datasets: [{
                        data: @json(array_values($deviceDistribution)),
                        backgroundColor: ['#191C47', '#FB4C4B', '#64748b', '#cbd5e1'],
                        borderWidth: 0,
                        hoverOffset: 15,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        },

        initConversionChart() {
            const ctx = document.getElementById('conversionChart');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: @json(array_keys($leadStatusBreakdown)),
                    datasets: [{
                        data: @json(array_values($leadStatusBreakdown)),
                        backgroundColor: ['#FB4C4B', '#191C47', '#64748b', '#cbd5e1'],
                        borderWidth: 0,
                        cutout: '75%',
                        hoverOffset: 10,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }
    }
}
</script>
@endsection
