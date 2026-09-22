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
        $excludedCategories = [
            'Store',
            'Client Engagement',
            'Cold Outreach',
            'Newsletter',
            'Seasonal Newsletter',
            'AI BDM / High-Value Leads',
            'Marketing',
            'Leads',
            'Clients',
            'Sales',
        ];

        $templates = EmailTemplate::where('is_active', true)
            ->whereNotIn('category', $excludedCategories)
            ->where(function($query) {
                $query->whereNull('category')
                      ->orWhere(function($q) {
                          $q->where('category', 'not like', '%lead%')
                            ->where('category', 'not like', '%client%')
                            ->where('category', 'not like', '%outreach%')
                            ->where('category', 'not like', '%marketing%')
                            ->where('category', 'not like', '%newsletter%')
                            ->where('category', 'not like', '%store%')
                            ->where('category', 'not like', '%sales%');
                      });
            })
            ->orderBy('category')
            ->orderBy('name')
            ->get();
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

        $selectedProject = null;
        if ($request->project_id) {
            $selectedProject = \Modules\ProjectManagement\Models\Project::find($request->project_id);
        }

        foreach ($recipients as $recipient) {
            if (!$recipient->email) {
                continue;
            }

            $nameParts = explode(' ', trim($recipient->name ?? ''));
            $firstName = $nameParts[0] ?? '';
            $lastName = count($nameParts) > 1 ? end($nameParts) : '';

            $project = $selectedProject ?: (method_exists($recipient, 'assignedProjects') ? $recipient->assignedProjects()->first() : null);
            $projectName = $project ? $project->name : ($selectedProject ? $selectedProject->name : 'General Project');
            $projectUrl = $project ? url('/admin/projects/' . $project->id) : url('/admin');
            $dueDate = ($project && !empty($project->deadline)) ? \Carbon\Carbon::parse($project->deadline)->format('F j, Y') : now()->addDays(7)->format('F j, Y');

            // Calculate real-time live performance metrics matching /admin/team-performance
            try {
                $perfMetrics = \Modules\ProjectManagement\Services\PerformanceEngine::calculate(
                    $recipient->id,
                    \Carbon\Carbon::now()->startOfMonth(),
                    \Carbon\Carbon::now()->endOfMonth()
                );
                $rawScore = $perfMetrics['score'] ?? 0;
            } catch (\Throwable $e) {
                $perfRecord = \DB::table('pm_team_performance')
                    ->where('user_id', $recipient->id)
                    ->orderBy('year', 'desc')
                    ->orderBy('month', 'desc')
                    ->first();
                $rawScore = $perfRecord ? (float) $perfRecord->score : 0;
                $perfMetrics = [];
            }

            $userScore = number_format($rawScore, 1);
            $performanceUrl = url('/admin/team-performance');

            $tableRows = '<tr><td style="padding: 8px; border: 1px solid #e5e7eb;">' . htmlspecialchars($recipient->name ?? 'Team Member') . '</td><td style="padding: 8px; border: 1px solid #e5e7eb; text-align: center;">' . ($perfMetrics['tasks']['completed'] ?? 1) . '</td><td style="padding: 8px; border: 1px solid #e5e7eb; text-align: center;">' . ($perfMetrics['attendance']['missed_checkins'] ?? 0) . '</td><td style="padding: 8px; border: 1px solid #e5e7eb; text-align: center; font-weight: bold; color: ' . ($rawScore >= 80 ? '#16a34a' : '#dc2626') . ';">' . $userScore . '%</td></tr>';

            $replacements = [
                'name' => $recipient->name ?? '',
                'first_name' => $firstName,
                'last_name' => $lastName,
                'employee_name' => $recipient->name ?? '',
                'assignee_name' => $recipient->name ?? '',
                'pm_name' => $recipient->name ?? '',
                'email' => $recipient->email,
                'department' => $recipient->department ?? 'General',
                'role' => $recipient->roles->pluck('name')->first() ?? 'Team Member',

                // Performance metrics
                'score' => $userScore,
                'avg_score' => $userScore,
                'average_team_score' => '85',
                'performance_url' => $performanceUrl,
                'scorecard_url' => $performanceUrl,
                'reviews_url' => $performanceUrl,
                'attendance_url' => url('/admin/team-utilities'),
                'checkin_url' => url('/team/dashboard'),
                'checkout_url' => url('/team/dashboard'),

                // Project & Tasks
                'project_name' => $projectName,
                'project_url' => $projectUrl,
                'projects_count' => '1',
                'projects_status' => 'In Progress',
                'projects_summary' => 'Active milestones progressing',
                'projects_audit' => 'All systems operational',
                'milestone_title' => 'Core Deliverable Milestone',
                'task_title' => 'Assigned Project Task',
                'task_description' => 'Current sprint assignment',
                'task_url' => url('/admin'),
                'due_date' => $dueDate,
                'task_due_date' => $dueDate,
                'pending_percentage' => '15',
                'completed_tasks' => '1',
                'pending_reviews' => '0',
                'overdue_count' => '0',
                'tasks_list' => 'Milestone Task 1, Milestone Task 2',
                'points_earned' => '5',
                'table_rows' => $tableRows,

                // Violations & Blockers
                'reason' => 'Monthly performance below target threshold',
                'violation_type' => 'Attendance / Task delay alert',
                'violation_details' => 'Performance score fell below standard threshold',
                'blocker_details' => 'No active blocker reported',
                'resolved_blockers' => '1',

                // Attendance / General Stats
                'duration' => '8 hrs',
                'start_time' => '09:00 AM',
                'end_time' => '05:00 PM',
                'total_members' => (string) count($recipients),
                'on_time' => (string) count($recipients),
                'late' => '0',
                'absent' => '0',
                'checked_in_names' => $recipient->name ?? 'Team',
                'late_names' => 'None',
                'absent_names' => 'None',
                'active_clock_ins' => '1',
                'leads_count' => '0',
                'new_leads' => '0',
                'clients_count' => '0',
                'invoices_count' => '0',
                'invoices_total' => '$0.00',
                'open_tickets' => '0',
                'resolved_tickets' => '0',
                'avg_response_hours' => '1.5',

                // Company & Site Info
                'site_name' => config('app.name', 'Rick Development LTD'),
                'company' => config('app.name', 'Rick Development LTD'),
                'company_name' => config('app.name', 'Rick Development LTD'),
                'site_url' => config('app.url', url('/')),
                'date' => now()->format('F j, Y'),
                'current_date' => now()->format('F j, Y'),
                'dashboard_url' => url('/admin'),
                'admin_url' => url('/admin'),
            ];

            $personalizedSubject = $subject;
            $personalizedMessage = $messageBody;

            foreach ($replacements as $key => $val) {
                $pattern = '/\{\{\s*(&nbsp;)*' . preg_quote($key, '/') . '(&nbsp;)*\s*\}\}/i';
                $personalizedSubject = preg_replace_callback($pattern, fn() => (string) $val, $personalizedSubject);
                $personalizedMessage = preg_replace_callback($pattern, fn() => (string) $val, $personalizedMessage);
            }

            Mail::to($recipient->email)->send(new TeamAnnouncementEmail($personalizedSubject, $personalizedMessage));
            $sentCount++;
        }

        return redirect()->route('admin.team-emails.create')
            ->with('success', "Email successfully sent to {$sentCount} team member(s).");
    }
}
