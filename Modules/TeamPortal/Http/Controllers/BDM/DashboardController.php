<?php

namespace Modules\TeamPortal\Http\Controllers\BDM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Contact\Models\Lead;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth('web')->user();
        
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();

        // Base query for this BDM's assigned leads
        $myLeads = Lead::where('assigned_to', $user->id);

        // Daily KPIs
        $dailyInbound = (clone $myLeads)->whereDate('created_at', $today)->count();
        $dailyProspects = (clone $myLeads)->whereDate('created_at', $today)->where('status', 'new')->count();
        $dailyOutreach = (clone $myLeads)->whereDate('last_contacted_at', $today)->count();
        
        // Weekly KPIs
        $weeklyInbound = (clone $myLeads)->where('created_at', '>=', $startOfWeek)->count();
        $weeklyProspects = (clone $myLeads)->where('created_at', '>=', $startOfWeek)->where('status', 'new')->count();
        $weeklyOutreach = (clone $myLeads)->where('last_contacted_at', '>=', $startOfWeek)->count();

        // Monthly KPIs
        $monthlyInbound = (clone $myLeads)->where('created_at', '>=', $startOfMonth)->count();
        $monthlyProspects = (clone $myLeads)->where('created_at', '>=', $startOfMonth)->where('status', 'new')->count();
        $monthlyOutreach = (clone $myLeads)->where('last_contacted_at', '>=', $startOfMonth)->count();

        // Deals
        $closedDeals = (clone $myLeads)->where('status', 'converted')->count();
        $totalDealValue = (clone $myLeads)->where('status', 'converted')->sum('value');

        return view('teamportal::bdm.dashboard', compact(
            'dailyInbound', 'dailyProspects', 'dailyOutreach',
            'weeklyInbound', 'weeklyProspects', 'weeklyOutreach',
            'monthlyInbound', 'monthlyProspects', 'monthlyOutreach',
            'closedDeals', 'totalDealValue'
        ));
    }
}
