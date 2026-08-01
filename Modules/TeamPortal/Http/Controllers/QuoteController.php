<?php

namespace Modules\TeamPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Accounting\Models\Quote;

class QuoteController extends Controller
{
    public function index(Request $request)
    {
        // RBAC Check
        if (!auth()->user()->can('quotes.view')) {
            abort(403);
        }

        $query = Quote::query();

        if ($request->filled('search')) {
            $query->where('reference', 'like', "%{$request->search}%")
                  ->orWhereHas('client', function($q) use ($request) {
                      $q->where('name', 'like', "%{$request->search}%")
                        ->orWhere('company_name', 'like', "%{$request->search}%");
                  });
        }

        $quotes = $query->latest()->paginate(10);

        return view('teamportal::quotes.index', compact('quotes'));
    }

    public function show($id)
    {
        // RBAC Check
        if (!auth()->user()->can('quotes.view')) {
            abort(403);
        }

        $quote = Quote::with(['client', 'items'])->findOrFail($id);

        return view('teamportal::quotes.show', compact('quote'));
    }
}
