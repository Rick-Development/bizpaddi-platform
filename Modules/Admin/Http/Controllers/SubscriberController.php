<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Communications\Models\Subscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function index()
    {
        $subscribers = Subscriber::latest()->get();
        return view('admin::subscribers.index', compact('subscribers'));
    }

    public function create()
    {
        return view('admin::subscribers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:subscribers,email',
            'name' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Subscriber::create($validated);

        return redirect()->route('admin.subscribers.index')
                        ->with('success', 'Subscriber added successfully.');
    }

    public function edit(Subscriber $subscriber)
    {
        return view('admin::subscribers.edit', compact('subscriber'));
    }

    public function update(Request $request, Subscriber $subscriber)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:subscribers,email,' . $subscriber->id,
            'name' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $subscriber->update($validated);

        return redirect()->route('admin.subscribers.index')
                        ->with('success', 'Subscriber updated successfully.');
    }

    public function destroy(Subscriber $subscriber)
    {
        $subscriber->delete();

        return redirect()->route('admin.subscribers.index')
                        ->with('success', 'Subscriber removed successfully.');
    }
}
