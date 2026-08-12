<?php


namespace Modules\TeamPortal\Http\Controllers\BDM\Portfolio;

use App\Http\Controllers\Controller;
use Modules\Portfolio\Models\Project;
use Modules\Portfolio\Models\Industry;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('industry')->latest()->paginate(10);
        return view('teamportal::bdm.portfolio.projects.index', compact('projects'));
    }

    public function create()
    {
        $industries = Industry::all();
        return view('teamportal::bdm.portfolio.projects.create', compact('industries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'client' => 'required|string|max:255',
            'industry_id' => 'required|exists:industries,id',
            'description' => 'required|string',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'problem_solved' => 'nullable|string',
            'solution' => 'nullable|string',
            'impact_metrics' => 'nullable|string',
            'is_featured' => 'boolean',
            'featured_image' => 'nullable|image|max:10240',
            'gallery.*' => 'nullable|image|max:10240',
        ]);

        $validated['is_featured'] = $request->has('is_featured');
        
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('projects', 'public');
        }

        if ($request->hasFile('gallery')) {
            $galleryPaths = [];
            foreach ($request->file('gallery') as $image) {
                $galleryPaths[] = $image->store('projects/gallery', 'public');
            }
            $validated['gallery'] = $galleryPaths;
        }

        Project::create($validated);

        return redirect()->route('team.bdm.projects.index')->with('success', 'Project created successfully.');
    }

    public function edit(Project $project)
    {
        $industries = Industry::all();
        return view('teamportal::bdm.portfolio.projects.edit', compact('project', 'industries'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'client' => 'required|string|max:255',
            'industry_id' => 'required|exists:industries,id',
            'description' => 'required|string',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'problem_solved' => 'nullable|string',
            'solution' => 'nullable|string',
            'impact_metrics' => 'nullable|string',
            'is_featured' => 'boolean',
            'featured_image' => 'nullable|image|max:10240',
            'gallery.*' => 'nullable|image|max:10240',
        ]);

        $validated['is_featured'] = $request->has('is_featured');
        
        if ($request->hasFile('featured_image')) {
            // Delete old image if exists
            if ($project->featured_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($project->featured_image)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($project->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('projects', 'public');
        }

        if ($request->hasFile('gallery')) {
            $galleryPaths = $project->gallery ?? [];
            foreach ($request->file('gallery') as $image) {
                $galleryPaths[] = $image->store('projects/gallery', 'public');
            }
            $validated['gallery'] = $galleryPaths;
        }

        $project->update($validated);

        return redirect()->route('team.bdm.projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        if ($project->featured_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($project->featured_image)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($project->featured_image);
        }
        
        if ($project->gallery) {
            foreach ($project->gallery as $image) {
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($image)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($image);
                }
            }
        }

        $project->delete();
        return redirect()->route('team.bdm.projects.index')->with('success', 'Project deleted successfully.');
    }
}
