<?php

namespace Modules\ClientPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $client = auth()->guard('client')->user();

        $stats = [
            'active_projects' => $client->projects()->where('status', 'active')->count(),
            'pending_invoices' => $client->invoices()->where('status', '!=', 'paid')->count(),
            'support_tickets' => $client->supportTickets()->count(),
            'total_invested' => $client->invoices()->where('status', 'paid')->sum('total_amount'),
        ];

        // Chart 1: Project Status Distribution
        $projectDistribution = [
            'labels' => ['Active', 'Completed', 'On Hold', 'Cancelled'],
            'data' => [
                $client->projects()->where('status', 'active')->count(),
                $client->projects()->where('status', 'completed')->count(),
                $client->projects()->where('status', 'on_hold')->count(),
                $client->projects()->where('status', 'cancelled')->count(),
            ]
        ];

        // Chart 2: Monthly Payment History (last 6 months)
        $months = [];
        $amounts = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M');
            $amounts[] = $client->invoices()
                ->where('status', 'paid')
                ->whereYear('paid_at', $date->year)
                ->whereMonth('paid_at', $date->month)
                ->sum('total_amount');
        }

        $paymentHistory = [
            'labels' => $months,
            'data' => $amounts
        ];
        
        return view('clientportal::dashboard', compact('client', 'stats', 'projectDistribution', 'paymentHistory'));
    }
}
