<?php

namespace Modules\Careers\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Careers\Models\Job;
use Modules\Careers\Models\Application;
use Modules\Careers\Models\Candidate;

class CareersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobs = Job::where('is_active', true)->latest()->get();
        return view('careers::index', compact('jobs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('careers::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) 
    {
        $request->validate([
            'job_id' => 'required|exists:career_jobs,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'linkedin' => 'nullable|url|max:255',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB
            'cover_letter' => 'nullable|string',
        ]);

        $resumePath = $request->file('resume')->store('resumes', 'public');

        $candidate = Candidate::firstOrCreate(
            ['email' => $request->email],
            [
                'name' => $request->name,
                'phone' => $request->phone,
                'linkedin' => $request->linkedin,
            ]
        );

        Application::create([
            'career_job_id' => $request->job_id,
            'candidate_id' => $candidate->id,
            'cover_letter' => $request->cover_letter,
            'resume_path' => $resumePath,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Your application has been submitted successfully! Our talent acquisition team will review your profile and reach out shortly.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $job = Job::where('is_active', true)
            ->where(function ($q) use ($id) {
                $q->where('id', $id)->orWhere('slug', $id);
            })
            ->firstOrFail();

        $relatedJobs = Job::where('is_active', true)
            ->where('id', '!=', $job->id)
            ->where('department', $job->department)
            ->latest()
            ->take(3)
            ->get();

        if ($relatedJobs->isEmpty()) {
            $relatedJobs = Job::where('is_active', true)
                ->where('id', '!=', $job->id)
                ->latest()
                ->take(3)
                ->get();
        }

        return view('careers::show', compact('job', 'relatedJobs'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('careers::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
