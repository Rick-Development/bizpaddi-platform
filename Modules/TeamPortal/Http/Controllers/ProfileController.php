<?php

namespace Modules\TeamPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Modules\ProjectManagement\Models\Task;
use Modules\ProjectManagement\Models\Project;

class ProfileController extends Controller
{
    /**
     * Show the user's profile overview page.
     */
    public function index()
    {
        $user = auth()->user();

        // Gather stats for the profile page
        $stats = [
            'total_tasks'       => $user->assignedTasks()->count(),
            'completed_tasks'   => $user->assignedTasks()->where('status', 'completed')->orWhere('status', 'done')->count(),
            'active_projects'   => $user->assignedProjects()->count(),
            'total_work_logs'   => $user->workLogs()->count(),
        ];

        $recentTasks = $user->assignedTasks()
            ->with('project')
            ->latest()
            ->take(5)
            ->get();

        return view('teamportal::profile.index', compact('user', 'stats', 'recentTasks'));
    }

    /**
     * Show the profile edit form.
     */
    public function edit()
    {
        $user = auth()->user();
        return view('teamportal::profile.edit', compact('user'));
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone'            => 'nullable|string|max:20',
            'bio'              => 'nullable|string|max:1000',
            'current_password' => 'nullable|required_with:password|string',
            'password'         => 'nullable|string|min:8|confirmed',
            'avatar'           => 'nullable|image|max:2048',
        ]);

        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('avatar')) {
            $user->clearMediaCollection('avatar');
            $user->addMediaFromRequest('avatar')->toMediaCollection('avatar');
        }

        unset($validated['current_password']);
        unset($validated['password_confirmation']);
        unset($validated['avatar']);

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }
}
