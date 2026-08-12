<?php

namespace Modules\TeamPortal\Http\Controllers\BDM;

use App\Http\Controllers\Controller;
use Modules\Clients\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::latest()->paginate(20);
        return view('teamportal::bdm.clients.index', compact('clients'));
    }

    public function create()
    {
        return view('teamportal::bdm.clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'password' => 'required|string|min:8',
            'company_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'zip' => 'nullable|string',
            'country' => 'nullable|string',
            'tax_id' => 'nullable|string',
            'industry' => 'nullable|string',
            'website' => 'nullable|url',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active');

        Client::create($validated);

        return redirect()->route('team.bdm.clients.index')
            ->with('success', 'Client created successfully.');
    }

    public function edit(Client $client)
    {
        return view('teamportal::bdm.clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('clients', 'email')->ignore($client->id)],
            'password' => 'nullable|string|min:8', // Metadata: Allow empty password for no change
            'company_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'zip' => 'nullable|string',
            'country' => 'nullable|string',
            'tax_id' => 'nullable|string',
            'industry' => 'nullable|string',
            'website' => 'nullable|url',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        $validated['is_active'] = $request->has('is_active');

        $client->update($validated);

        return redirect()->route('team.bdm.clients.index')
            ->with('success', 'Client updated successfully.');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('team.bdm.clients.index')
            ->with('success', 'Client deleted successfully.');
    }
    public function show(Client $client)
    {
        return view('teamportal::bdm.clients.show', compact('client'));
    }
}
