<?php

namespace Modules\TeamPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Services\Models\Service;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        // RBAC Check
        if (!auth()->user()->can('services.view')) {
            abort(403);
        }

        $query = Service::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $services = $query->latest()->paginate(12);

        return view('teamportal::services.index', compact('services'));
    }

    public function show($id)
    {
        // RBAC Check
        if (!auth()->user()->can('services.view')) {
            abort(403);
        }

        $service = Service::findOrFail($id);

        return view('teamportal::services.show', compact('service'));
    }
}
