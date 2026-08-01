@extends('clientportal::layouts.app')

@section('title', 'Client Dashboard')

@section('content')
<div class="dashboard-wrapper space-y-8 animate-fade-in">
    <!-- Premium Welcome Header -->
    <div class="relative overflow-hidden rounded-[2.5rem] bg-brand-secondary p-10 text-white shadow-2xl">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <h1 class="text-4xl font-black tracking-tight sm:text-5xl">Welcome, {{ $client->name }}</h1>
                <p class="mt-3 text-lg font-medium text-gray-300 max-w-xl">
                    Your executive portal for project tracking, fiscal management, and dedicated support.
                </p>
                <div class="mt-6 flex items-center gap-4">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-green-500/20 px-4 py-1 text-sm font-bold text-green-400">
                        <span class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></span>
                        {{ $client->is_active ? 'Active Account' : 'Action Required' }}
                    </span>
                    <span class="text-sm font-medium text-gray-400">ID: #{{ str_pad($client->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
            </div>
            <div class="hidden lg:block">
                <div class="rounded-3xl bg-white/5 p-6 backdrop-blur-md border border-white/10">
                    <p class="text-xs font-bold uppercase tracking-widest text-brand-primary">Total Investment</p>
                    <h3 class="mt-1 text-3xl font-black">₦{{ number_format($stats['total_invested'], 2) }}</h3>
                </div>
            </div>
        </div>
        <!-- Decorative elements -->
        <div class="absolute -right-20 -top-20 h-80 w-80 rounded-full bg-brand-primary/10 blur-3xl"></div>
        <div class="absolute -left-20 -bottom-20 h-64 w-64 rounded-full bg-blue-500/5 blur-3xl"></div>
    </div>

    <!-- Main Stats Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <div class="group relative overflow-hidden rounded-3xl bg-white p-6 shadow-sm shadow-brand-secondary/5 transition-all hover:-translate-y-1 hover:shadow-xl hover:shadow-brand-secondary/10">
            <div class="flex items-center justify-between">
                <div class="h-14 w-14 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 transition-colors group-hover:bg-blue-600 group-hover:text-white">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                </div>
                <span class="text-xs font-black text-gray-300 uppercase">Live</span>
            </div>
            <div class="mt-6">
                <h3 class="text-3xl font-black text-brand-secondary">{{ $stats['active_projects'] }}</h3>
                <p class="text-sm font-bold text-gray-400 uppercase tracking-wide">Active Projects</p>
            </div>
        </div>

        <div class="group relative overflow-hidden rounded-3xl bg-white p-6 shadow-sm shadow-brand-secondary/5 transition-all hover:-translate-y-1 hover:shadow-xl hover:shadow-brand-secondary/10">
            <div class="flex items-center justify-between">
                <div class="h-14 w-14 rounded-2xl bg-brand-primary/10 flex items-center justify-center text-brand-primary transition-colors group-hover:bg-brand-primary group-hover:text-white">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <span class="text-xs font-black text-brand-primary uppercase">Pending</span>
            </div>
            <div class="mt-6">
                <h3 class="text-3xl font-black text-brand-secondary">{{ $stats['pending_invoices'] }}</h3>
                <p class="text-sm font-bold text-gray-400 uppercase tracking-wide">Open Invoices</p>
            </div>
        </div>

        <div class="group relative overflow-hidden rounded-3xl bg-white p-6 shadow-sm shadow-brand-secondary/5 transition-all hover:-translate-y-1 hover:shadow-xl hover:shadow-brand-secondary/10">
            <div class="flex items-center justify-between">
                <div class="h-14 w-14 rounded-2xl bg-green-50 flex items-center justify-center text-green-600 transition-colors group-hover:bg-green-600 group-hover:text-white">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                </div>
                <span class="text-xs font-black text-gray-300 uppercase">Support</span>
            </div>
            <div class="mt-6">
                <h3 class="text-3xl font-black text-brand-secondary">{{ $stats['support_tickets'] }}</h3>
                <p class="text-sm font-bold text-gray-400 uppercase tracking-wide">Tickets</p>
            </div>
        </div>

        <div class="group relative overflow-hidden rounded-3xl bg-white p-6 shadow-sm shadow-brand-secondary/5 transition-all hover:-translate-y-1 hover:shadow-xl hover:shadow-brand-secondary/10">
            <div class="flex items-center justify-between">
                <div class="h-14 w-14 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-600 transition-colors group-hover:bg-purple-600 group-hover:text-white">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <span class="text-xs font-black text-gray-300 uppercase">Profile</span>
            </div>
            <div class="mt-6">
                <h3 class="text-xl font-black text-brand-secondary truncate">{{ $client->industry ?? 'Technology' }}</h3>
                <p class="text-sm font-bold text-gray-400 uppercase tracking-wide">Business Sector</p>
            </div>
        </div>
    </div>

    <!-- Middle Section: Charts -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        <div class="lg:col-span-4 rounded-3xl bg-white p-8 shadow-sm border border-gray-100 flex flex-col">
            <div class="mb-8">
                <h3 class="text-xl font-black text-brand-secondary">Project Portfolio</h3>
                <p class="text-sm font-bold text-gray-400">Status Breakdown</p>
            </div>
            <div class="relative flex-1 min-h-[250px]">
                <canvas id="projectDistributionChart"></canvas>
            </div>
        </div>

        <div class="lg:col-span-8 rounded-3xl bg-white p-8 shadow-sm border border-gray-100">
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-black text-brand-secondary">Investment Trajectory</h3>
                    <p class="text-sm font-bold text-gray-400">Monthly Fiscal Engagement</p>
                </div>
                <div class="flex gap-2">
                    <span class="inline-flex h-3 w-3 rounded-full bg-brand-primary"></span>
                    <span class="inline-flex h-3 w-3 rounded-full bg-gray-100"></span>
                </div>
            </div>
            <div class="relative h-[300px]">
                <canvas id="paymentHistoryChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Bottom Section: Quick Actions & Details -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Quick Actions -->
        <div class="rounded-3xl bg-white p-8 shadow-sm border border-gray-100">
            <h3 class="text-xl font-black text-brand-secondary mb-6">Operations Center</h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('client.services.index') }}" class="group flex flex-col items-center justify-center gap-3 rounded-2xl border border-gray-50 bg-gray-50/50 p-6 transition-all hover:bg-brand-primary hover:border-brand-primary shadow-sm">
                    <div class="rounded-xl bg-white p-3 text-brand-primary group-hover:scale-110 transition-transform">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    </div>
                    <span class="text-sm font-bold text-gray-600 group-hover:text-white transition-colors">Order Service</span>
                </a>
                <a href="{{ route('client.projects.index') }}" class="group flex flex-col items-center justify-center gap-3 rounded-2xl border border-gray-50 bg-gray-50/50 p-6 transition-all hover:bg-brand-secondary hover:border-brand-secondary shadow-sm">
                    <div class="rounded-xl bg-white p-3 text-brand-secondary group-hover:scale-110 transition-transform">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                    <span class="text-sm font-bold text-gray-600 group-hover:text-white transition-colors">View Track</span>
                </a>
                <a href="{{ route('client.invoices.index') }}" class="group flex flex-col items-center justify-center gap-3 rounded-2xl border border-gray-50 bg-gray-50/50 p-6 transition-all hover:bg-brand-primary hover:border-brand-primary shadow-sm">
                    <div class="rounded-xl bg-white p-3 text-brand-primary group-hover:scale-110 transition-transform">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                    <span class="text-sm font-bold text-gray-600 group-hover:text-white transition-colors">Financials</span>
                </a>
                <a href="{{ route('client.tickets.index') }}" class="group flex flex-col items-center justify-center gap-3 rounded-2xl border border-gray-50 bg-gray-50/50 p-6 transition-all hover:bg-brand-secondary hover:border-brand-secondary shadow-sm">
                    <div class="rounded-xl bg-white p-3 text-brand-secondary group-hover:scale-110 transition-transform">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                    </div>
                    <span class="text-sm font-bold text-gray-600 group-hover:text-white transition-colors">Support Line</span>
                </a>
            </div>
        </div>

        <!-- Account Info -->
        <div class="rounded-3xl bg-white p-8 shadow-sm border border-gray-100 flex flex-col">
            <h3 class="text-xl font-black text-brand-secondary mb-6">Corporate Profile</h3>
            <div class="flex-1 space-y-6">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Organization</p>
                        <p class="text-base font-bold text-brand-secondary">{{ $client->company_name ?? 'Joint Venture' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" /></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Direct Contact</p>
                        <p class="text-base font-bold text-brand-secondary">{{ $client->email }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Client Tenure</p>
                        <p class="text-base font-bold text-brand-secondary">{{ $client->created_at->format('F d, Y') }}</p>
                    </div>
                </div>
            </div>
            <div class="mt-8 pt-6 border-t border-gray-50 flex justify-between items-center">
                <span class="text-xs font-bold text-green-500 uppercase tracking-tighter">Encrypted Connection Secure</span>
                <span class="h-2 w-2 rounded-full bg-green-500"></span>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.6s ease-out forwards;
    }
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Project Distribution Chart
    const projectCtx = document.getElementById('projectDistributionChart').getContext('2d');
    new Chart(projectCtx, {
        type: 'pie',
        data: {
            labels: @json($projectDistribution['labels']),
            datasets: [{
                data: @json($projectDistribution['data']),
                backgroundColor: ['#3b82f6', '#22c55e', '#f59e0b', '#ef4444'],
                borderWidth: 0,
                hoverOffset: 15
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 15,
                        font: { weight: 'bold', size: 10 }
                    }
                }
            }
        }
    });

    // Payment History Chart
    const paymentCtx = document.getElementById('paymentHistoryChart').getContext('2d');
    const gradient = paymentCtx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(25, 28, 71, 0.2)');
    gradient.addColorStop(1, 'rgba(25, 28, 71, 0)');

    new Chart(paymentCtx, {
        type: 'line',
        data: {
            labels: @json($paymentHistory['labels']),
            datasets: [{
                label: 'Investment (₦)',
                data: @json($paymentHistory['data']),
                borderColor: '#191C47',
                backgroundColor: gradient,
                borderWidth: 4,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#191C47',
                pointBorderWidth: 3,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#191C47',
                    padding: 12,
                    titleFont: { weight: 'bold' }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f8fafc' },
                    ticks: { 
                        font: { weight: 'bold' },
                        callback: function(value) { return '₦' + value.toLocaleString(); }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { weight: 'bold' } }
                }
            }
        }
    });
});
</script>
@endpush
@endsection
