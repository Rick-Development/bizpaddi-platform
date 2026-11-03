<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Modules\Home\Models\HeroSlider;
use Modules\Home\Models\FeaturedContent;
use Modules\Services\Models\Service;
use Modules\Clients\Models\Testimonial;
use Modules\About\Models\Leader;
use Modules\About\Models\Timeline;
use Modules\About\Models\Award;
use Modules\Portfolio\Models\Project;
use Modules\Portfolio\Models\Industry;
use Modules\Blog\Models\Post;
use Modules\Blog\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        // Resolve active tenant ID (impersonated or authenticated admin's tenant)
        $tenantId = session('impersonated_tenant_id') 
            ?? (auth('admin')->check() ? auth('admin')->user()->tenant_id : null);

        $tenant = $tenantId ? Tenant::find($tenantId) : null;

        // 1. Core Financial Metrics (Accounting) scoped to tenant
        $invoiceQuery = \Modules\Accounting\Models\Invoice::when($tenantId, fn($q) => $q->where('tenant_id', $tenantId));

        $totalRevenue = (clone $invoiceQuery)->where('status', 'paid')->sum('total_amount');
        $revenueThisMonth = (clone $invoiceQuery)->where('status', 'paid')
                                ->whereMonth('paid_at', now()->month)
                                ->whereYear('paid_at', now()->year)
                                ->sum('total_amount');
        $revenueLastMonth = (clone $invoiceQuery)->where('status', 'paid')
                                ->whereMonth('paid_at', now()->subMonth()->month)
                                ->whereYear('paid_at', now()->subMonth()->year)
                                ->sum('total_amount');
        
        $revenueGrowth = $revenueLastMonth > 0 ? (($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100 : 0;

        // 1.1 Salary Metrics scoped to tenant
        $hasSalaryTenantCol = \Illuminate\Support\Facades\Schema::hasTable('salary_disbursements')
            && \Illuminate\Support\Facades\Schema::hasColumn('salary_disbursements', 'tenant_id');

        $totalSalaries = 0;
        $salariesThisMonth = 0;
        $salaryChart = ['labels' => [], 'data' => []];
        $recentSalaries = collect([]);

        if ($hasSalaryTenantCol) {
            $salaryQuery = \Modules\Admin\Models\SalaryDisbursement::when($tenantId, fn($q) => $q->where('tenant_id', $tenantId));

            $totalSalaries = (clone $salaryQuery)->whereIn('status', ['successful', 'completed'])->sum('amount');
            $salariesThisMonth = (clone $salaryQuery)->whereIn('status', ['successful', 'completed'])
                                    ->whereMonth('payment_date', now()->month)
                                    ->whereYear('payment_date', now()->year)
                                    ->sum('amount');

            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $monthName = $date->format('M Y');
                $amount = (clone $salaryQuery)->whereIn('status', ['successful', 'completed'])
                            ->whereYear('payment_date', $date->year)
                            ->whereMonth('payment_date', $date->month)
                            ->sum('amount');
                $salaryChart['labels'][] = $monthName;
                $salaryChart['data'][] = (float)$amount;
            }

            $recentSalaries = \Modules\Admin\Models\SalaryDisbursement::when($tenantId, fn($q) => $q->where('tenant_id', $tenantId))
                                    ->with('user')
                                    ->latest()
                                    ->take(5)
                                    ->get();
        } else {
            for ($i = 5; $i >= 0; $i--) {
                $salaryChart['labels'][] = now()->subMonths($i)->format('M Y');
                $salaryChart['data'][] = 0.0;
            }
        }
                                
        // 2. User & Traffic Metrics (Clients & Analytics) scoped to tenant
        $activeClients = \Modules\Clients\Models\Client::when($tenantId, fn($q) => $q->where('tenant_id', $tenantId))
                                ->where('is_active', true)
                                ->count();
        
        $totalVisitors = 0;
        $deviceDistribution = [];
        $topCountries = [];
        
        // Scope traffic & regional metrics strictly to the tenant's own workspace
        if ($tenantId && \Illuminate\Support\Facades\Schema::hasColumn('visitors', 'tenant_id')) {
            if (class_exists(\Modules\Analytics\Models\Visitor::class)) {
                $totalVisitors = \Modules\Analytics\Models\Visitor::where('tenant_id', $tenantId)->count();
                $deviceDistribution = \Modules\Analytics\Models\Visitor::where('tenant_id', $tenantId)
                                        ->whereNotNull('device')
                                        ->groupBy('device')
                                        ->pluck('count', 'device')
                                        ->toArray();
                
                $topCountries = \Modules\Analytics\Models\Visitor::where('tenant_id', $tenantId)
                                    ->whereNotNull('country')
                                    ->groupBy('country')
                                    ->orderByDesc('count')
                                    ->take(5)
                                    ->pluck('count', 'country')
                                    ->toArray();
            }
        }

        // If no storefront visitors yet, display regional client distribution if clients exist
        if (empty($topCountries) && $tenantId) {
            $clientCountries = \Modules\Clients\Models\Client::where('tenant_id', $tenantId)
                                ->whereNotNull('country')
                                ->where('country', '!=', '')
                                ->selectRaw('country, count(*) as count')
                                ->groupBy('country')
                                ->orderByDesc('count')
                                ->take(5)
                                ->pluck('count', 'country')
                                ->toArray();

            if (!empty($clientCountries)) {
                $topCountries = $clientCountries;
                $totalVisitors = array_sum($clientCountries);
            }
        }
        
        $newLeads = \Modules\Contact\Models\ContactSubmission::when($tenantId, fn($q) => $q->where('tenant_id', $tenantId))
                                ->where('status', 'new')
                                ->count();

        $leadStatusBreakdown = \Modules\Contact\Models\ContactSubmission::when($tenantId, fn($q) => $q->where('tenant_id', $tenantId))
                                ->selectRaw('status, count(*) as count')
                                ->groupBy('status')
                                ->pluck('count', 'status')
                                ->toArray();

        // 3. AI Engagement History (Last 7 Days)
        $aiHistory = ['labels' => [], 'data' => []];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $aiHistory['labels'][] = $date->format('D');
            $aiHistory['data'][] = 0;
        }

        // 4. Operational Metrics scoped to tenant
        $activeProjects = \Modules\ProjectManagement\Models\Project::when($tenantId, fn($q) => $q->where('tenant_id', $tenantId))
                                ->where('status', 'in_progress')
                                ->count();
        
        $staffOnline = 0;
        if (class_exists(\Modules\TeamPortal\Models\WorkLog::class)) {
            $staffOnline = \Modules\TeamPortal\Models\WorkLog::when($tenantId, fn($q) => $q->where('tenant_id', $tenantId))
                                ->where('status', 'in_progress')
                                ->count();
        }

        $aiUsage = $tenant ? (int) ($tenant->ai_tokens_used ?? 0) : 0;

        // 5. Charts Data (Last 6 Months)
        $revenueChart = ['labels' => [], 'data' => []];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M Y');
            $amount = (clone $invoiceQuery)->where('status', 'paid')
                        ->whereYear('paid_at', $date->year)
                        ->whereMonth('paid_at', $date->month)
                        ->sum('total_amount');
            $revenueChart['labels'][] = $monthName;
            $revenueChart['data'][] = (float)$amount;
        }

        // Visitor Trend (Last 7 Days)
        $visitorChart = ['labels' => [], 'data' => []];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dayName = $date->format('D');
            $count = ($tenantId === 1 || is_null($tenantId)) && class_exists(\Modules\Analytics\Models\Visitor::class)
                ? \Modules\Analytics\Models\Visitor::whereDate('created_at', $date->toDateString())->count()
                : 0;
            $visitorChart['labels'][] = $dayName;
            $visitorChart['data'][] = $count;
        }

        // Project Status Distribution
        $projectStatusCounts = \Modules\ProjectManagement\Models\Project::when($tenantId, fn($q) => $q->where('tenant_id', $tenantId))
                                ->selectRaw('status, count(*) as count')
                                ->groupBy('status')
                                ->pluck('count', 'status')
                                ->toArray();
        
        // 6. Recent Activity
        $recentInvoices = \Modules\Accounting\Models\Invoice::when($tenantId, fn($q) => $q->where('tenant_id', $tenantId))
                                ->with('client')
                                ->latest()
                                ->take(5)
                                ->get();

        $recentInquiries = \Modules\Contact\Models\ContactSubmission::when($tenantId, fn($q) => $q->where('tenant_id', $tenantId))
                                ->latest()
                                ->take(5)
                                ->get();
        
        $recentTickets = collect([]);
        if (class_exists(\Modules\ClientPortal\Models\SupportTicket::class)) {
            $recentTickets = \Modules\ClientPortal\Models\SupportTicket::when($tenantId, fn($q) => $q->where('tenant_id', $tenantId))
                                ->with('client')
                                ->latest()
                                ->take(5)
                                ->get();
        }

        // 7. Executive Insights
        $investorInsights = [
            'stock_price' => 0,
            'price_change' => 0,
            'next_meeting' => null,
            'latest_report' => null
        ];

        $projectInsights = [
            'on_track' => \Modules\ProjectManagement\Models\Project::when($tenantId, fn($q) => $q->where('tenant_id', $tenantId))->where('status', 'in_progress')->count(),
            'delayed' => \Modules\ProjectManagement\Models\Project::when($tenantId, fn($q) => $q->where('tenant_id', $tenantId))->where('status', 'delayed')->count(),
            'upcoming_milestones' => []
        ];

        // 8. Financial Health
        $financialHealth = [
            'overdue_invoices' => (clone $invoiceQuery)->where('status', 'unpaid')->where('due_date', '<', now())->sum('total_amount'),
            'pending_quotes' => \Modules\Accounting\Models\Quote::when($tenantId, fn($q) => $q->where('tenant_id', $tenantId))->where('status', 'pending')->count(),
            'cash_flow' => []
        ];

        // 9. Bank Balance
        // For a tenant business: bank balance is their dedicated operating wallet balance
        $safeHavenBalance = $tenant ? (float) ($tenant->wallet_balance ?? 0.00) : 0.00;

        return view('admin::dashboard', compact(
            'totalRevenue', 'revenueThisMonth', 'revenueGrowth', 'activeClients', 
            'totalVisitors', 'deviceDistribution', 'topCountries', 'newLeads', 'leadStatusBreakdown', 
            'activeProjects', 'staffOnline', 'aiUsage', 'aiHistory',
            'revenueChart', 'visitorChart', 'salaryChart', 'projectStatusCounts',
            'recentInvoices', 'recentInquiries', 'recentSalaries', 'recentTickets',
            'investorInsights', 'projectInsights', 'financialHealth',
            'totalSalaries', 'salariesThisMonth', 'safeHavenBalance'
        ));
    }
}
