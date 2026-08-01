<?php

namespace Modules\Admin\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Clients\Models\Client;
use App\Models\User;
use Modules\ProjectManagement\Models\Project;
use Modules\Accounting\Models\Invoice;

class DashboardController extends ApiController
{
    /**
     * GET /api/v1/admin/dashboard
     * General admin overview dashboard metrics.
     */
    public function index(Request $request)
    {
        // 1. Core Financial Metrics (Accounting)
        $totalRevenue = \Modules\Accounting\Models\Invoice::where('status', 'paid')->sum('total_amount');
        $revenueThisMonth = \Modules\Accounting\Models\Invoice::where('status', 'paid')
                                ->whereMonth('paid_at', now()->month)
                                ->whereYear('paid_at', now()->year)
                                ->sum('total_amount');
        $revenueLastMonth = \Modules\Accounting\Models\Invoice::where('status', 'paid')
                                ->whereMonth('paid_at', now()->subMonth()->month)
                                ->whereYear('paid_at', now()->subMonth()->year)
                                ->sum('total_amount');
        
        $revenueGrowth = $revenueLastMonth > 0 ? (($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100 : 0;

        // 1.1 Salary Metrics
        $totalSalaries = \Modules\Admin\Models\SalaryDisbursement::whereIn('status', ['successful', 'completed'])->sum('amount');
        $salariesThisMonth = \Modules\Admin\Models\SalaryDisbursement::whereIn('status', ['successful', 'completed'])
                                ->whereMonth('payment_date', now()->month)
                                ->whereYear('payment_date', now()->year)
                                ->sum('amount');
                                
        // 2. User & Traffic Metrics (Clients & Analytics)
        $activeClients = \Modules\Clients\Models\Client::where('is_active', true)->count();
        $totalVisitors = 0;
        $deviceDistribution = [];
        $topCountries = [];
        if (class_exists(\Modules\Analytics\Models\Visitor::class)) {
            $totalVisitors = \Modules\Analytics\Models\Visitor::count();
            $deviceDistribution = \Modules\Analytics\Models\Visitor::selectRaw('device, count(*) as count')
                                    ->whereNotNull('device')
                                    ->groupBy('device')
                                    ->pluck('count', 'device')
                                    ->toArray();
            
            $topCountries = \Modules\Analytics\Models\Visitor::selectRaw('country, count(*) as count')
                                ->whereNotNull('country')
                                ->groupBy('country')
                                ->orderByDesc('count')
                                ->take(5)
                                ->pluck('count', 'country')
                                ->toArray();
        }
        
        $newLeads = \Modules\Contact\Models\ContactSubmission::where('status', 'new')->count();
        $leadStatusBreakdown = \Modules\Contact\Models\ContactSubmission::selectRaw('status, count(*) as count')
                                    ->groupBy('status')
                                    ->pluck('count', 'status')
                                    ->toArray();

        // 3. AI Engagement History (Last 7 Days)
        $aiHistory = ['labels' => [], 'data' => []];
        if (class_exists(\Modules\AI\Models\ChatMessage::class)) {
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $aiHistory['labels'][] = $date->format('D');
                $aiHistory['data'][] = \Modules\AI\Models\ChatMessage::whereDate('created_at', $date->toDateString())->count();
            }
        }

        // 4. Operational Metrics
        $activeProjects = \Modules\ProjectManagement\Models\Project::where('status', 'in_progress')->count();
        
        $staffOnline = 0;
        if (class_exists(\Modules\TeamPortal\Models\WorkLog::class)) {
            $staffOnline = \Modules\TeamPortal\Models\WorkLog::where('status', 'in_progress')->count();
        }

        $aiUsage = 0;
        if (class_exists(\Modules\AI\Models\ChatMessage::class)) {
            $aiUsage = \Modules\AI\Models\ChatMessage::count();
        }

        // 5. Charts Data
        // Monthly Revenue (Last 6 Months)
        $revenueChart = ['labels' => [], 'data' => []];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M Y');
            $amount = \Modules\Accounting\Models\Invoice::where('status', 'paid')
                        ->whereYear('paid_at', $date->year)
                        ->whereMonth('paid_at', $date->month)
                        ->sum('total_amount');
            $revenueChart['labels'][] = $monthName;
            $revenueChart['data'][] = (float)$amount;
        }

        // Visitor Trend (Last 7 Days)
        $visitorChart = ['labels' => [], 'data' => []];
        if (class_exists(\Modules\Analytics\Models\Visitor::class)) {
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $dayName = $date->format('D');
                $count = \Modules\Analytics\Models\Visitor::whereDate('created_at', $date->toDateString())->count();
                $visitorChart['labels'][] = $dayName;
                $visitorChart['data'][] = $count;
            }
        }

        // Salary Trend (Last 6 Months)
        $salaryChart = ['labels' => [], 'data' => []];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M Y');
            $amount = \Modules\Admin\Models\SalaryDisbursement::whereIn('status', ['successful', 'completed'])
                        ->whereYear('payment_date', $date->year)
                        ->whereMonth('payment_date', $date->month)
                        ->sum('amount');
            $salaryChart['labels'][] = $monthName;
            $salaryChart['data'][] = (float)$amount;
        }

        // Project Status Distribution
        $projectStatusCounts = \Modules\ProjectManagement\Models\Project::selectRaw('status, count(*) as count')
                                ->groupBy('status')
                                ->pluck('count', 'status')
                                ->toArray();
        
        // 6. Recent Activity
        $recentClients = Client::latest()->take(5)->get(['id', 'name', 'email', 'created_at']);
        $recentProjects = Project::with('client:id,name')->latest()->take(5)->get(['id', 'name', 'client_id', 'status']);
        $recentInvoices = \Modules\Accounting\Models\Invoice::with('client')->latest()->take(5)->get();
        $recentInquiries = \Modules\Contact\Models\ContactSubmission::latest()->take(5)->get();
        $recentSalaries = \Modules\Admin\Models\SalaryDisbursement::with('user')->latest()->take(5)->get();
        
        $recentTickets = collect([]);
        if (class_exists(\Modules\ClientPortal\Models\SupportTicket::class)) {
             $recentTickets = \Modules\ClientPortal\Models\SupportTicket::with('client')->latest()->take(5)->get();
        }

        // 7. Executive Insights (Investor & Project Management)
        $investorInsights = [
            'stock_price' => 0,
            'price_change' => 0,
            'next_meeting' => null,
            'latest_report' => null
        ];
        
        if (class_exists(\Modules\Investor\Models\StockData::class)) {
            $latestStock = \Modules\Investor\Models\StockData::latest()->first();
            if ($latestStock) {
                $investorInsights['stock_price'] = $latestStock->price;
                $previousStock = \Modules\Investor\Models\StockData::where('id', '<', $latestStock->id)->latest()->first();
                if ($previousStock && $previousStock->price > 0) {
                    $investorInsights['price_change'] = (($latestStock->price - $previousStock->price) / $previousStock->price) * 100;
                }
            }
        }
        
        if (class_exists(\Modules\Investor\Models\ShareholderMeeting::class)) {
            $investorInsights['next_meeting'] = \Modules\Investor\Models\ShareholderMeeting::where('meeting_date', '>=', now())
                                                    ->orderBy('meeting_date')
                                                    ->first();
        }

        $projectInsights = [
            'on_track' => 0,
            'delayed' => 0,
            'upcoming_milestones' => []
        ];

        if (class_exists(\Modules\ProjectManagement\Models\Project::class)) {
            $projectInsights['on_track'] = \Modules\ProjectManagement\Models\Project::where('status', 'in_progress')->count();
            $projectInsights['delayed'] = \Modules\ProjectManagement\Models\Project::where('status', 'delayed')->count();
        }

        if (class_exists(\Modules\ProjectManagement\Models\Milestone::class)) {
            $projectInsights['upcoming_milestones'] = \Modules\ProjectManagement\Models\Milestone::with('project')
                                                        ->where('due_date', '>=', now())
                                                        ->orderBy('due_date')
                                                        ->take(3)
                                                        ->get();
        }

        // 8. Financial Health
        $financialHealth = [
            'overdue_invoices' => 0,
            'pending_quotes' => 0,
            'cash_flow' => []
        ];

        if (class_exists(\Modules\Accounting\Models\Invoice::class)) {
            $financialHealth['overdue_invoices'] = \Modules\Accounting\Models\Invoice::where('status', 'unpaid')
                                                        ->where('due_date', '<', now())
                                                        ->sum('total_amount');
        }

        if (class_exists(\Modules\Accounting\Models\Quote::class)) {
            $financialHealth['pending_quotes'] = \Modules\Accounting\Models\Quote::where('status', 'pending')->count();
        }

        // 9. Bank Balance (from SafeHaven)
        $safeHavenBalance = 0;
        try {
            $safeHavenService = new \Modules\Accounting\Services\SafeHavenService();
            $accountNumber = \Modules\Settings\Models\Setting::get('safehaven_settlement_account_number');
            if ($accountNumber) {
                $response = $safeHavenService->getAccount($accountNumber);
                if ($response['success']) {
                    $safeHavenBalance = $response['data']['accountBalance'] ?? 0;
                } else {
                    \Illuminate\Support\Facades\Log::warning("SafeHaven Balance Fetch Failed: " . ($response['message'] ?? 'Unknown error'));
                }
            } else {
                \Illuminate\Support\Facades\Log::warning("SafeHaven Balance Fetch Skipped: Settlement account number not configured in settings");
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("SafeHaven Balance Error: " . $e->getMessage());
        }

        return $this->sendResponse([
            'stats' => [
                'total_clients' => (int) $activeClients,
                'active_projects' => (int) $activeProjects,
                'unpaid_invoices_count' => \Modules\Accounting\Models\Invoice::where('status', '!=', 'paid')->count(),
                'total_revenue' => (float) $totalRevenue,
                'revenue_this_month' => (float) $revenueThisMonth,
                'revenue_growth' => (float) $revenueGrowth,
                'total_visitors' => (int) $totalVisitors,
                'ai_usage' => (int) $aiUsage,
                'staff_online' => (int) $staffOnline,
                'total_salaries' => (float) $totalSalaries,
                'salaries_this_month' => (float) $salariesThisMonth,
                'safe_haven_balance' => (float) $safeHavenBalance,
            ],
            'charts' => [
                'revenue_chart' => $revenueChart,
                'visitor_chart' => $visitorChart,
                'salary_chart' => $salaryChart,
                'device_distribution' => $deviceDistribution,
                'top_countries' => $topCountries,
                'lead_status_breakdown' => $leadStatusBreakdown,
                'ai_history' => $aiHistory,
                'project_status_counts' => $projectStatusCounts,
            ],
            'recent_clients' => $recentClients,
            'recent_projects' => $recentProjects,
            'recent_invoices' => $recentInvoices,
            'recent_inquiries' => $recentInquiries,
            'recent_salaries' => $recentSalaries,
            'recent_tickets' => $recentTickets,
            'investor_insights' => $investorInsights,
            'project_insights' => $projectInsights,
            'financial_health' => $financialHealth,
        ], 'Admin Dashboard metrics retrieved successfully.');
    }
}
