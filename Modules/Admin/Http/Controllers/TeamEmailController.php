<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Modules\Admin\Models\Department;
use Spatie\Permission\Models\Role;
use Modules\Communications\Models\EmailTemplate;
use Modules\Admin\Emails\TeamAnnouncementEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TeamEmailController extends Controller
{
    public function create()
    {
        $teamMembers = User::where('status', 'active')->orderBy('name')->get();
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $roles = Role::orderBy('name')->get();
        $templates = EmailTemplate::where('is_active', true)->orderBy('name')->get();
        $projects = \Modules\ProjectManagement\Models\Project::orderBy('name')->get();

        return view('admin::team-emails.create', compact('teamMembers', 'departments', 'roles', 'templates', 'projects'));
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'recipient_type' => 'required|in:all_active,department,role,selected_users,project',
            'department' => 'required_if:recipient_type,department|nullable|string|exists:departments,name',
            'role' => 'required_if:recipient_type,role|nullable|string|exists:roles,name',
            'project_id' => 'required_if:recipient_type,project|nullable|integer|exists:pm_projects,id',
            'users' => 'required_if:recipient_type,selected_users|nullable|array',
            'users.*' => 'exists:users,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $query = User::where('status', 'active');

        switch ($request->recipient_type) {
            case 'all_active':
                break;
            case 'department':
                $query->where('department', $request->department);
                break;
            case 'role':
                $query->role($request->role);
                break;
            case 'project':
                $project = \Modules\ProjectManagement\Models\Project::with('teamMembers')->find($request->project_id);
                if ($project) {
                    $memberIds = $project->teamMembers->pluck('id')->toArray();
                    if ($project->manager_id) {
                        $memberIds[] = $project->manager_id;
                    }
                    $query->whereIn('id', array_unique($memberIds));
                } else {
                    $query->whereRaw('1 = 0');
                }
                break;
            case 'selected_users':
                $query->whereIn('id', $request->users);
                break;
        }

        $recipients = $query->get();

        if ($recipients->isEmpty()) {
            return back()->withInput()->with('error', 'No active team members matched your criteria.');
        }

        $subject = $request->subject;
        $messageBody = $request->message;
        $sentCount = 0;

        foreach ($recipients as $recipient) {
            if ($recipient->email) {
                Mail::to($recipient->email)->send(new TeamAnnouncementEmail($subject, $messageBody));
                $sentCount++;
            }
        }

        return redirect()->route('admin.team-emails.create')
            ->with('success', "Email successfully sent to {$sentCount} team member(s).");
    }
}
