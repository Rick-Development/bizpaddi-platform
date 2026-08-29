<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Careers\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = Application::with(['job', 'candidate']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('candidate', function($candQ) use ($search) {
                    $candQ->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('job', function($jobQ) use ($search) {
                    $jobQ->where('title', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('job_id')) {
            $query->where('career_job_id', $request->input('job_id'));
        }

        $applications = $query->latest()->get();

        if ($request->ajax() || $request->has('ajax')) {
            return response()->json([
                'html'  => view('modules.admin.applications.partials.table', compact('applications'))->render(),
                'count' => $applications->count(),
            ]);
        }

        $jobs = \Modules\Careers\Models\Job::whereHas('applications')->get();

        return view('admin::applications.index', compact('applications', 'jobs'));
    }

    public function show(Application $application)
    {
        $application->load(['job', 'candidate']);
        return view('admin::applications.show', compact('application'));
    }

    public function update(Request $request, Application $application)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,reviewing,interview,rejected,accepted',
        ]);

        $application->update($validated);

        return redirect()->back()->with('success', 'Application status updated successfully.');
    }

    public function downloadResume(Application $application)
    {
        if (!$application->resume_path) {
            return back()->with('error', 'No resume file associated with this application.');
        }

        $path = $application->resume_path;

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            return \Illuminate\Support\Facades\Storage::disk('public')->download($path);
        }

        if (\Illuminate\Support\Facades\Storage::exists($path)) {
            return \Illuminate\Support\Facades\Storage::download($path);
        }

        $publicPath = storage_path('app/public/' . ltrim($path, '/'));
        if (file_exists($publicPath)) {
            return response()->download($publicPath);
        }

        $appPath = storage_path('app/' . ltrim($path, '/'));
        if (file_exists($appPath)) {
            return response()->download($appPath);
        }

        return back()->with('error', 'Resume file could not be found on the server disk.');
    }
}
