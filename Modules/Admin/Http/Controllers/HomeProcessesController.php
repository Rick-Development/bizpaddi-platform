<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Home\Models\HomeProcess;

class HomeProcessesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $processes = HomeProcess::orderBy('step_number')->get();
        return view('admin::home.processes.index', compact('processes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin::home.processes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'step_number' => 'required|integer',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:255',
        ]);

        HomeProcess::create($validated);

        return redirect()->route('admin.home.processes.index')->with('success', 'Process step created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $process = HomeProcess::findOrFail($id);
        return view('admin::home.processes.edit', compact('process'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'step_number' => 'required|integer',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:255',
        ]);

        $process = HomeProcess::findOrFail($id);
        $process->update($validated);

        return redirect()->route('admin.home.processes.index')->with('success', 'Process step updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $process = HomeProcess::findOrFail($id);
        $process->delete();

        return redirect()->route('admin.home.processes.index')->with('success', 'Process step deleted successfully.');
    }
}
