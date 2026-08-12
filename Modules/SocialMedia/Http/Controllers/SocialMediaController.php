<?php

namespace Modules\SocialMedia\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Modules\SocialMedia\Models\SmmPost;
use Modules\SocialMedia\Models\SmmLead;
use Modules\SocialMedia\Models\SmmCampaign;
use Modules\SocialMedia\Models\SmmSocialInbox;
use Modules\SocialMedia\Models\SmmAnalytic;
use Modules\SocialMedia\Models\SmmContentCalendar;

class SocialMediaController extends Controller
{
    /**
     * Social Media Dashboard
     */
    public function index()
    {
        $now = Carbon::now();

        // ── KPI Stats ─────────────────────────────────────────────────────────
        $totalImpressions   = SmmAnalytic::sum('impressions');
        $totalEngagements   = SmmAnalytic::selectRaw('SUM(likes + comments + shares) as total')->value('total') ?? 0;
        $totalLeads         = SmmLead::count();
        $leadsThisMonth     = SmmLead::whereMonth('created_at', $now->month)->count();
        $scheduledPosts     = SmmPost::where('status', 'scheduled')->count();
        $activeCampaigns    = SmmCampaign::count();
        $engagementRate     = $totalImpressions > 0
            ? round(($totalEngagements / $totalImpressions) * 100, 2)
            : 0;

        // ── Platform breakdown ─────────────────────────────────────────────────
        $platforms = ['linkedin', 'x', 'instagram', 'facebook'];
        $platformStats = [];
        foreach ($platforms as $platform) {
            $platformStats[$platform] = [
                'impressions'  => SmmAnalytic::where('platform', $platform)->sum('impressions'),
                'engagements'  => SmmAnalytic::where('platform', $platform)
                                     ->selectRaw('SUM(likes + comments + shares) as total')
                                     ->value('total') ?? 0,
            ];
        }

        // ── Recent posts (published/scheduled) ─────────────────────────────────
        $recentPosts = SmmPost::whereIn('status', ['published', 'scheduled'])
            ->latest()
            ->take(5)
            ->get();

        // ── Active campaigns ─────────────────────────────────────────────────
        $campaigns = SmmCampaign::latest()->take(5)->get();

        // ── Agent logs (latest inbox interactions) ────────────────────────────
        $agentLogs = SmmSocialInbox::latest()->take(6)->get();

        // ── Reach trend (last 7 months aggregated) ────────────────────────────
        $trendLabels = [];
        $trendData   = [];
        for ($i = 6; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $trendLabels[] = $month->format('M');
            $trendData[]   = SmmAnalytic::whereYear('tracked_at', $month->year)
                                ->whereMonth('tracked_at', $month->month)
                                ->sum('impressions');
        }

        return view('socialmedia::index', compact(
            'totalImpressions', 'totalEngagements', 'totalLeads',
            'leadsThisMonth', 'scheduledPosts', 'activeCampaigns',
            'engagementRate', 'platformStats', 'recentPosts',
            'campaigns', 'agentLogs', 'trendLabels', 'trendData'
        ));
    }

    /**
     * Social Inbox
     */
    public function inbox()
    {
        $totalUnread   = SmmSocialInbox::where('status', 'unread')->count();
        $hotLeads      = SmmLead::where('intent_level', 'high')->count();
        $escalated     = SmmSocialInbox::where('category', 'escalated')->count();
        $resolvedToday = SmmSocialInbox::where('status', 'resolved')
                            ->whereDate('updated_at', today())
                            ->count();

        // Thread list: latest inbox items
        $threads = SmmSocialInbox::latest()->take(20)->get();

        // Active thread (most recent unread, or just most recent)
        $activeThread = SmmSocialInbox::where('status', 'unread')->latest()->first()
                     ?? SmmSocialInbox::latest()->first();

        return view('socialmedia::inbox', compact(
            'totalUnread', 'hotLeads', 'escalated', 'resolvedToday',
            'threads', 'activeThread'
        ));
    }

    /**
     * Content Calendar
     */
    public function calendar()
    {
        $now          = Carbon::now();
        $year         = $now->year;
        $month        = $now->month;
        $monthName    = $now->format('F Y');
        $firstDay     = Carbon::create($year, $month, 1)->dayOfWeek;
        $daysInMonth  = $now->daysInMonth;
        $today        = $now->day;

        // Pull this month's scheduled/draft posts grouped by day
        $monthPosts = SmmPost::whereYear('scheduled_for', $year)
            ->whereMonth('scheduled_for', $month)
            ->whereNotNull('scheduled_for')
            ->get()
            ->groupBy(fn($post) => Carbon::parse($post->scheduled_for)->day);

        // Upcoming posts (from today onward this month)
        $upcomingPosts = SmmPost::whereYear('scheduled_for', $year)
            ->whereMonth('scheduled_for', $month)
            ->whereDate('scheduled_for', '>=', today())
            ->orderBy('scheduled_for')
            ->take(10)
            ->get();

        // Stats
        $totalScheduled  = SmmPost::whereYear('scheduled_for', $year)
                               ->whereMonth('scheduled_for', $month)
                               ->count();
        $aiGenerated     = SmmPost::whereYear('scheduled_for', $year)
                               ->whereMonth('scheduled_for', $month)
                               ->where('status', 'ai_generated')
                               ->count();
        $publishedCount  = SmmPost::whereYear('scheduled_for', $year)
                               ->whereMonth('scheduled_for', $month)
                               ->where('status', 'published')
                               ->count();
        $pendingReview   = SmmPost::where('status', 'draft')->count();

        return view('socialmedia::calendar', compact(
            'year', 'month', 'monthName', 'firstDay', 'daysInMonth',
            'today', 'monthPosts', 'upcomingPosts',
            'totalScheduled', 'aiGenerated', 'publishedCount', 'pendingReview'
        ));
    }

    /**
     * Analytics
     */
    public function analytics()
    {
        // ── Top KPIs ──────────────────────────────────────────────────────────
        $totalImpressions  = SmmAnalytic::sum('impressions');
        $totalEngagements  = SmmAnalytic::selectRaw('SUM(likes + comments + shares) as total')->value('total') ?? 0;
        $avgEngagementRate = SmmAnalytic::avg('engagement_rate') ?? 0;
        $newFollowers      = 0; // No followers table yet — show 0 gracefully
        $leadsIdentified   = SmmLead::count();

        // ── Platform breakdown table ──────────────────────────────────────────
        $platforms = [
            'linkedin'  => ['label' => 'LinkedIn',   'icon' => 'in', 'bg' => '#dbeafe', 'fg' => '#1d4ed8'],
            'x'         => ['label' => 'X (Twitter)', 'icon' => 'X',  'bg' => '#f3f4f6', 'fg' => '#111'],
            'instagram' => ['label' => 'Instagram',   'icon' => 'ig', 'bg' => '#fce7f3', 'fg' => '#be185d'],
            'facebook'  => ['label' => 'Facebook',    'icon' => 'fb', 'bg' => '#eff6ff', 'fg' => '#1d4ed8'],
        ];
        foreach ($platforms as $key => &$p) {
            $stats = SmmAnalytic::where('platform', $key)
                ->selectRaw('SUM(impressions) as imp, SUM(likes+comments+shares) as eng, AVG(engagement_rate) as rate')
                ->first();
            $p['impressions']      = $stats->imp ?? 0;
            $p['engagements']      = $stats->eng ?? 0;
            $p['engagement_rate']  = round($stats->rate ?? 0, 2);
        }
        unset($p);

        // ── Trend data (last 8 data points by tracked_at) ─────────────────────
        $trendRaw = SmmAnalytic::selectRaw(
                'DATE(tracked_at) as day, SUM(impressions) as impressions, SUM(likes+comments+shares) as engagements'
            )
            ->groupBy('day')
            ->orderBy('day')
            ->take(30)
            ->get();

        $trendLabels      = $trendRaw->pluck('day')->map(fn($d) => Carbon::parse($d)->format('M j'))->toArray();
        $trendImpressions = $trendRaw->pluck('impressions')->toArray();
        $trendEngagements = $trendRaw->pluck('engagements')->toArray();

        // ── Top posts ─────────────────────────────────────────────────────────
        $topPosts = SmmPost::with('analytics')
            ->whereHas('analytics')
            ->get()
            ->sortByDesc(fn($p) => $p->analytics->sum('impressions'))
            ->take(3)
            ->values();

        return view('socialmedia::analytics', compact(
            'totalImpressions', 'totalEngagements', 'avgEngagementRate',
            'newFollowers', 'leadsIdentified', 'platforms',
            'trendLabels', 'trendImpressions', 'trendEngagements', 'topPosts'
        ));
    }

    // ── Boilerplate ──────────────────────────────────────────────────────────
    public function create()  { return view('socialmedia::create'); }
    public function store(\Illuminate\Http\Request $request) {}
    public function show($id) { return view('socialmedia::show'); }
    public function edit($id) { return view('socialmedia::edit'); }
    public function update(\Illuminate\Http\Request $request, $id) {}
    public function destroy($id) {}
}
