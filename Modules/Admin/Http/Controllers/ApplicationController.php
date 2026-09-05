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

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->input('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->input('to_date'));
        }

        $applications = $query->latest()->get();

        if ($request->ajax() || $request->has('ajax')) {
            $viewName = view()->exists('modules.admin.applications.partials.table') 
                ? 'modules.admin.applications.partials.table' 
                : 'admin::applications.partials.table';

            return response()->json([
                'html'  => view($viewName, compact('applications'))->render(),
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

        $rawPath = $application->resume_path;

        // Clean path from any domain or leading storage/
        $cleanPath = preg_replace('/^https?:\/\/[^\/]+/', '', $rawPath);
        $cleanPath = preg_replace('/^\/?storage\//', '', $cleanPath);
        $cleanPath = ltrim($cleanPath, '/');
        $filename = basename($cleanPath);

        $possiblePaths = [
            storage_path('app/public/' . $cleanPath),
            storage_path('app/public/resumes/' . $filename),
            storage_path('app/private/' . $cleanPath),
            storage_path('app/private/resumes/' . $filename),
            storage_path('app/' . $cleanPath),
            storage_path('app/resumes/' . $filename),
            public_path('storage/' . $cleanPath),
            public_path('storage/resumes/' . $filename),
        ];

        foreach ($possiblePaths as $fullPath) {
            if (file_exists($fullPath) && !is_dir($fullPath)) {
                return response()->download($fullPath, $filename);
            }
        }

        return back()->with('error', "Resume file ({$filename}) could not be located on the server disk.");
    }
}
