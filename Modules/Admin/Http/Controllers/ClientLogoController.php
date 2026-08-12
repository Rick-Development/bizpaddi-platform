<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ClientLogo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientLogoController extends Controller
{
    public function index()
    {
        $logos = ClientLogo::orderBy('sort_order')->get();
        return view('admin::client_logos.index', compact('logos'));
    }

    public function create()
    {
        return view('admin::client_logos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image_path' => 'required|image|max:5120',
            'is_active' => 'boolean',
            'sort_order' => 'integer'
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['image_path'] = $request->file('image_path')->store('client_logos', 'public');

        ClientLogo::create($validated);
        return redirect()->route('admin.client-logos.index')->with('success', 'Logo created.');
    }

    public function edit(ClientLogo $clientLogo)
    {
        return view('admin::client_logos.edit', compact('clientLogo'));
    }

    public function update(Request $request, ClientLogo $clientLogo)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image_path' => 'nullable|image|max:5120',
            'is_active' => 'boolean',
            'sort_order' => 'integer'
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        if ($request->hasFile('image_path')) {
            if ($clientLogo->image_path) {
                Storage::disk('public')->delete($clientLogo->image_path);
            }
            $validated['image_path'] = $request->file('image_path')->store('client_logos', 'public');
        }

        $clientLogo->update($validated);
        return redirect()->route('admin.client-logos.index')->with('success', 'Logo updated.');
    }

    public function destroy(ClientLogo $clientLogo)
    {
        if ($clientLogo->image_path) {
            Storage::disk('public')->delete($clientLogo->image_path);
        }
        $clientLogo->delete();
        return redirect()->route('admin.client-logos.index')->with('success', 'Logo deleted.');
    }
}
