<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Careers\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = Application::with(['job', 'candidate'])->latest()->get();
        return view('admin::applications.index', compact('applications'));
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
}
