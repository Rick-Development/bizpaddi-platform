<?php

namespace Modules\Admin\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Modules\Clients\Models\Client;

class ClientController extends ApiController
{
    /**
     * GET /api/v1/admin/clients
     * List all clients.
     */
    public function index(Request $request)
    {
        $clients = Client::latest()->paginate(20);
        return $this->sendResponse($clients, 'Clients retrieved successfully.');
    }

    /**
     * POST /api/v1/admin/clients
     * Create a new client.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:clients,email',
            'password'     => 'required|string|min:8',
            'company_name' => 'nullable|string|max:255',
            'phone'        => 'nullable|string|max:20',
            'address'      => 'nullable|string',
            'city'         => 'nullable|string',
            'state'        => 'nullable|string',
            'zip'          => 'nullable|string',
            'country'      => 'nullable|string',
            'tax_id'       => 'nullable|string',
            'industry'     => 'nullable|string',
            'website'      => 'nullable|url',
            'description'  => 'nullable|string',
            'is_active'    => 'nullable|boolean',
        ]);

        $validated['password']  = Hash::make($validated['password']);
        $validated['is_active'] = $request->boolean('is_active', true);

        $client = Client::create($validated);

        return $this->sendResponse($client, 'Client created successfully.', 201);
    }

    /**
     * GET /api/v1/admin/clients/{id}
     * Get specific client details.
     */
    public function show($id)
    {
        $client = Client::with(['projects', 'invoices', 'quotes'])->find($id);

        if (!$client) {
            return $this->sendError('Client not found.', 404);
        }

        return $this->sendResponse($client, 'Client details retrieved successfully.');
    }

    /**
     * PUT /api/v1/admin/clients/{id}
     * Update client.
     */
    public function update(Request $request, $id)
    {
        $client = Client::find($id);

        if (!$client) {
            return $this->sendError('Client not found.', 404);
        }

        $validated = $request->validate([
            'name'         => 'sometimes|string|max:255',
            'email'        => ['sometimes', 'email', Rule::unique('clients', 'email')->ignore($id)],
            'password'     => 'nullable|string|min:8',
            'company_name' => 'nullable|string|max:255',
            'phone'        => 'nullable|string|max:20',
            'address'      => 'nullable|string',
            'city'         => 'nullable|string',
            'state'        => 'nullable|string',
            'zip'          => 'nullable|string',
            'country'      => 'nullable|string',
            'tax_id'       => 'nullable|string',
            'industry'     => 'nullable|string',
            'website'      => 'nullable|url',
            'description'  => 'nullable|string',
            'is_active'    => 'nullable|boolean',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $client->update($validated);

        return $this->sendResponse($client->fresh(), 'Client updated successfully.');
    }

    /**
     * DELETE /api/v1/admin/clients/{id}
     * Delete client.
     */
    public function destroy($id)
    {
        $client = Client::find($id);

        if (!$client) {
            return $this->sendError('Client not found.', 404);
        }

        $client->delete();

        return $this->sendResponse([], 'Client deleted successfully.');
    }
}
