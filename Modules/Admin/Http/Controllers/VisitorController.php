<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    public function index(Request $request)
    {
        if (!class_exists(\Modules\Analytics\Models\Visitor::class)) {
            abort(404, 'Analytics module not available');
        }

        $query = \Modules\Analytics\Models\Visitor::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ip_address', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('url', 'like', "%{$search}%");
            });
        }

        // Filter by country
        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        // Filter by device
        if ($request->filled('device')) {
            $query->where('device', $request->device);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $visitors = $query->latest()->paginate(50);

        // Get filter options
        $countries = \Modules\Analytics\Models\Visitor::whereNotNull('country')
            ->distinct()
            ->pluck('country')
            ->sort();

        $devices = \Modules\Analytics\Models\Visitor::whereNotNull('device')
            ->distinct()
            ->pluck('device')
            ->sort();

        // Statistics
        $stats = [
            'total' => \Modules\Analytics\Models\Visitor::count(),
            'today' => \Modules\Analytics\Models\Visitor::whereDate('created_at', today())->count(),
            'this_week' => \Modules\Analytics\Models\Visitor::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => \Modules\Analytics\Models\Visitor::whereMonth('created_at', now()->month)->count(),
            'unique_countries' => \Modules\Analytics\Models\Visitor::whereNotNull('country')->distinct('country')->count(),
            'avg_time_spent' => round(\Modules\Analytics\Models\Visitor::avg('time_spent')),
        ];

        return view('admin::visitors.index', compact('visitors', 'countries', 'devices', 'stats'));
    }

    public function show($id)
    {
        if (!class_exists(\Modules\Analytics\Models\Visitor::class)) {
            abort(404, 'Analytics module not available');
        }

        $visitor = \Modules\Analytics\Models\Visitor::findOrFail($id);

        // Get related sessions (same IP or session_id)
        $relatedSessions = \Modules\Analytics\Models\Visitor::where(function($q) use ($visitor) {
            $q->where('ip_address', $visitor->ip_address);
            if ($visitor->session_id) {
                $q->orWhere('session_id', $visitor->session_id);
            }
        })
        ->where('id', '!=', $visitor->id)
        ->latest()
        ->take(10)
        ->get();

        return view('admin::visitors.show', compact('visitor', 'relatedSessions'));
    }

    public function destroy($id)
    {
        if (!class_exists(\Modules\Analytics\Models\Visitor::class)) {
            abort(404, 'Analytics module not available');
        }

        $visitor = \Modules\Analytics\Models\Visitor::findOrFail($id);
        $visitor->delete();

        return redirect()->route('admin.visitors.index')
            ->with('success', 'Visitor session deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        if (!class_exists(\Modules\Analytics\Models\Visitor::class)) {
            abort(404, 'Analytics module not available');
        }

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:visitors,id'
        ]);

        \Modules\Analytics\Models\Visitor::whereIn('id', $request->ids)->delete();

        return redirect()->route('admin.visitors.index')
            ->with('success', count($request->ids) . ' visitor sessions deleted successfully.');
    }
}
