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
            'type' => 'required|string|in:full-time,part-time,contract,remote,internship',
            'workplace_type' => 'nullable|string|in:remote,hybrid,on-site',
            'experience_level' => 'nullable|string|max:100',
            'description' => 'required|string',
            'responsibilities' => 'nullable|string',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'currency' => 'nullable|string|max:10',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'salary_period' => 'nullable|string|in:hour,month,year,project',
            'skills' => 'nullable|string|max:1000',
            'application_deadline' => 'nullable|date',
            'status' => 'nullable|string|in:published,draft,closed',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');

        if (($validated['status'] ?? 'published') === 'published') {
            $validated['published_at'] = now();
        }

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
            'type' => 'required|string|in:full-time,part-time,contract,remote,internship',
            'workplace_type' => 'nullable|string|in:remote,hybrid,on-site',
            'experience_level' => 'nullable|string|max:100',
            'description' => 'required|string',
            'responsibilities' => 'nullable|string',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'currency' => 'nullable|string|max:10',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'salary_period' => 'nullable|string|in:hour,month,year,project',
            'skills' => 'nullable|string|max:1000',
            'application_deadline' => 'nullable|date',
            'status' => 'nullable|string|in:published,draft,closed',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');

        if (($validated['status'] ?? 'published') === 'published' && !$job->published_at) {
            $validated['published_at'] = now();
        }

        if (($validated['status'] ?? '') === 'closed' && !$job->closed_at) {
            $validated['closed_at'] = now();
        }

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
