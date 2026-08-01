<?php

namespace Modules\TeamPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Clients\Models\Client;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        // RBAC Check
        if (!auth()->user()->can('clients.view')) {
             abort(403);
        }

        $query = Client::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('company_name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
        }

        $clients = $query->latest()->paginate(10);

        return view('teamportal::clients.index', compact('clients'));
    }

    public function show($id)
    {
        // RBAC Check
        if (!auth()->user()->can('clients.view')) {
             abort(403);
        }

        $client = Client::with(['projects.tasks', 'invoices', 'supportTickets'])->findOrFail($id);

        return view('teamportal::clients.show', compact('client'));
    }
}
