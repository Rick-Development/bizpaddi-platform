<?php

namespace Modules\Investor\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvestorController extends Controller
{
    /**
     * Display the investor relations overview page.
     */
    public function index()
    {
        $latestReports = \Modules\Investor\Models\FinancialReport::published()->latest('report_date')->take(3)->get();
        $latestPressReleases = \Modules\Investor\Models\PressRelease::published()->latest('published_at')->take(3)->get();
        $latestStockData = \Modules\Investor\Models\StockData::latest('date')->first();
        $upcomingMeeting = \Modules\Investor\Models\ShareholderMeeting::upcoming()->orderBy('meeting_date')->first();

        return view('investor::index', compact('latestReports', 'latestPressReleases', 'latestStockData', 'upcomingMeeting'));
    }

    /**
     * Display financial reports.
     */
    public function financialReports()
    {
        $reports = \Modules\Investor\Models\FinancialReport::published()
            ->orderBy('fiscal_year', 'desc')
            ->orderBy('report_date', 'desc')
            ->paginate(12);
            
        return view('investor::financial-reports', compact('reports'));
    }

    /**
     * Display press releases.
     */
    public function pressReleases()
    {
        $pressReleases = \Modules\Investor\Models\PressRelease::published()
            ->latest('published_at')
            ->paginate(12);
            
        return view('investor::press-releases.index', compact('pressReleases'));
    }

    /**
     * Display a specific press release.
     */
    public function showPressRelease($slug)
    {
        $pressRelease = \Modules\Investor\Models\PressRelease::published()->where('slug', $slug)->firstOrFail();
        
        return view('investor::press-releases.show', compact('pressRelease'));
    }

    /**
     * Display stock data.
     */
    public function stockData()
    {
        $stockData = \Modules\Investor\Models\StockData::orderBy('date', 'desc')->paginate(20);
        $latestData = \Modules\Investor\Models\StockData::latest('date')->first();
        
        return view('investor::stock-data', compact('stockData', 'latestData'));
    }

    /**
     * Display shareholder meetings.
     */
    public function shareholderMeetings()
    {
        $upcomingMeetings = \Modules\Investor\Models\ShareholderMeeting::upcoming()->orderBy('meeting_date')->get();
        $pastMeetings = \Modules\Investor\Models\ShareholderMeeting::past()->orderBy('meeting_date', 'desc')->paginate(10);
        
        return view('investor::shareholder-meetings', compact('upcomingMeetings', 'pastMeetings'));
    }
}
