<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Careers\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::withCount('applications')->latest()->get();
        return view('admin::jobs.index', compact('jobs'));
    }

    public function create()
    {
        return view('admin::jobs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'type' => 'required|in:full-time,part-time,contract,remote',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Job::create($validated);

        return redirect()->route('admin.jobs.index')
                        ->with('success', 'Job posting created successfully.');
    }

    public function edit(Job $job)
    {
        return view('admin::jobs.edit', compact('job'));
    }

    public function update(Request $request, Job $job)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'type' => 'required|in:full-time,part-time,contract,remote',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $job->update($validated);

        return redirect()->route('admin.jobs.index')
                        ->with('success', 'Job posting updated successfully.');
    }

    public function destroy(Job $job)
    {
        $job->delete();

        return redirect()->route('admin.jobs.index')
                        ->with('success', 'Job posting deleted successfully.');
    }
}
