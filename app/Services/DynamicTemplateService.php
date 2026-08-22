<?php

namespace App\Services;

use Modules\Communications\Models\EmailTemplate;

class DynamicTemplateService
{
    /**
     * Provision all 38 default system templates if they don't already exist.
     */
    public static function provisionTemplates(): void
    {
        $templates = self::getDefaultTemplates();

        foreach ($templates as $key => $tpl) {
            $exists = EmailTemplate::where('name', $tpl['name'])->exists();
            if (!$exists) {
                EmailTemplate::create([
                    'name' => $tpl['name'],
                    'subject' => $tpl['subject'],
                    'content' => $tpl['content'],
                    'category' => $tpl['category'] ?? 'System',
                    'is_active' => true,
                ]);
            }
        }
    }

    /**
     * Get array of all default email templates.
     */
    public static function getDefaultTemplates(): array
    {
        return [
            // ==========================================
            // 1. Attendance & Work Sessions (Developers)
            // ==========================================
            [
                'name' => 'Daily Check-in Reminder',
                'subject' => 'Daily Reminder: Time to Clock In!',
                'category' => 'Attendance',
                'content' => '<p>Good morning {{name}},</p><p>This is a reminder to clock in and start your daily work log session.</p><p>Keeping your sessions tracked helps measure productivity and ensures task hours are logged correctly.</p><p><a href="{{checkin_url}}" style="display: inline-block; padding: 10px 20px; background-color: #fc4c4b; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">Clock In Now</a></p><p>Have a productive day!<br>Team {{site_name}}</p>'
            ],
            [
                'name' => 'Late Check-in Alert',
                'subject' => 'Late Check-in Alert — Time is ticking!',
                'category' => 'Attendance',
                'content' => '<p>Hello {{name}},</p><p>You still haven\'t clocked in today, and the check-in window has closed.</p><p>Please clock in immediately to avoid negative marks on your daily attendance scorecard.</p><p><a href="{{checkin_url}}" style="display: inline-block; padding: 10px 20px; background-color: #fc4c4b; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">Clock In Now</a></p><p>Best regards,<br>Team {{site_name}}</p>'
            ],
            [
                'name' => 'Missed Check-in Alert',
                'subject' => 'Missed Check-in Alert — Absence Recorded',
                'category' => 'Attendance',
                'content' => '<p>Hello {{name}},</p><p>You have missed the daily check-in window. A missed check-in violation has been logged to your performance scorecard.</p><p>If this is an error or you are on leave, please contact your project manager.</p><p>Best regards,<br>Team {{site_name}}</p>'
            ],
            [
                'name' => 'Mid-day Progress Check',
                'subject' => 'Mid-day Progress Check — Keep it going!',
                'category' => 'Attendance',
                'content' => '<p>Hi {{name}},</p><p>We are halfway through the workday. Please take a moment to update your task statuses on the Kanban board and ensure you log your break time.</p><p><a href="{{dashboard_url}}" style="display: inline-block; padding: 10px 20px; background-color: #fc4c4b; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">Update Kanban Tasks</a></p><p>Keep up the great work!<br>Team {{site_name}}</p>'
            ],
            [
                'name' => 'Inactive Work Session Alert',
                'subject' => 'Warning: Inactive Work Session Detected',
                'category' => 'Attendance',
                'content' => '<p>Hello {{name}},</p><p>Your current work log session has been running for over 6 hours without any task updates or comments.</p><p>Please make sure to update your cards or clock out if you have finished working.</p><p><a href="{{checkout_url}}" style="display: inline-block; padding: 10px 20px; background-color: #fc4c4b; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">Clock Out / Update Log</a></p><p>Thanks,<br>Team {{site_name}}</p>'
            ],
            [
                'name' => 'Daily Check-out Reminder',
                'subject' => 'Daily Reminder: Remember to Clock Out',
                'category' => 'Attendance',
                'content' => '<p>Hi {{name}},</p><p>The workday is winding down. Please remember to clock out and log the actual tasks you completed today.</p><p><a href="{{checkout_url}}" style="display: inline-block; padding: 10px 20px; background-color: #fc4c4b; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">Clock Out & Log Tasks</a></p><p>Have a great evening!<br>Team {{site_name}}</p>'
            ],
            [
                'name' => 'Missed Check-out Alert',
                'subject' => 'Alert: Missed Clock-out',
                'category' => 'Attendance',
                'content' => '<p>Hello {{name}},</p><p>You are still clocked in, but the workday ended several hours ago.</p><p>Please clock out immediately to prevent the system from auto-terminating your session and issuing an attendance penalty.</p><p><a href="{{checkout_url}}" style="display: inline-block; padding: 10px 20px; background-color: #fc4c4b; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">Clock Out Now</a></p><p>Thanks,<br>Team {{site_name}}</p>'
            ],
            [
                'name' => 'Work Session Summary',
                'subject' => 'Daily Work Session Summary',
                'category' => 'Attendance',
                'content' => '<p>Hello {{name}},</p><p>You have successfully checked out. Here is a summary of your session:</p><ul><li><strong>Start Time:</strong> {{start_time}}</li><li><strong>End Time:</strong> {{end_time}}</li><li><strong>Duration:</strong> {{duration}} minutes</li><li><strong>Completed Tasks:</strong> {{completed_tasks}}</li><li><strong>Performance Points Earned:</strong> {{points_earned}}</li></ul><p>Thank you for logging your hours,<br>Team {{site_name}}</p>'
            ],

            // ==========================================
            // 2. Task & Project Management (Developers)
            // ==========================================
            [
                'name' => 'Task Assigned',
                'subject' => 'New Task Assigned: {{task_title}}',
                'category' => 'Task Management',
                'content' => '<p>Hello {{assignee_name}},</p><p>You have been assigned to the following task:</p><h3>{{task_title}}</h3><p><strong>Description:</strong><br>{{task_description}}</p><p><strong>Due Date:</strong> {{task_due_date}}</p><p><a href="{{task_url}}" style="display: inline-block; padding: 10px 20px; background-color: #fc4c4b; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">View Task Details</a></p><p>Best regards,<br>Team {{site_name}}</p>'
            ],
            [
                'name' => 'Task Due Soon',
                'subject' => 'Reminder: Task "{{task_title}}" is due soon',
                'category' => 'Task Management',
                'content' => '<p>Hello {{assignee_name}},</p><p>This is a reminder that your task <strong>{{task_title}}</strong> is due within 48 hours.</p><p><strong>Due Date:</strong> {{task_due_date}}</p><p><a href="{{task_url}}" style="display: inline-block; padding: 10px 20px; background-color: #fc4c4b; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">Go to Task</a></p><p>Thanks,<br>Team {{site_name}}</p>'
            ],
            [
                'name' => 'Task Due Today',
                'subject' => 'Urgent: Task "{{task_title}}" is due today',
                'category' => 'Task Management',
                'content' => '<p>Hello {{assignee_name}},</p><p>Your task <strong>{{task_title}}</strong> is due today. Please make sure to complete it or coordinate with your PM.</p><p><strong>Due Date:</strong> {{task_due_date}}</p><p><a href="{{task_url}}" style="display: inline-block; padding: 10px 20px; background-color: #fc4c4b; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">Go to Task</a></p><p>Thanks,<br>Team {{site_name}}</p>'
            ],
            [
                'name' => 'Task Overdue',
                'subject' => 'Overdue: Task "{{task_title}}" requires immediate attention',
                'category' => 'Task Management',
                'content' => '<p>Hello {{assignee_name}},</p><p>Your task <strong>{{task_title}}</strong> is now overdue.</p><p>Please update the task status immediately or contact your project manager.</p><p><strong>Due Date:</strong> {{task_due_date}}</p><p><a href="{{task_url}}" style="display: inline-block; padding: 10px 20px; background-color: #fc4c4b; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">View Overdue Task</a></p><p>Best regards,<br>Team {{site_name}}</p>'
            ],
            [
                'name' => 'Task Inactivity Alert',
                'subject' => 'Attention Required: Inactive Task "{{task_title}}"',
                'category' => 'Task Management',
                'content' => '<p>Hello {{assignee_name}},</p><p>Your task <strong>{{task_title}}</strong> has been in progress for over 3 days without updates or comments.</p><p>Please post an update or progress report on this task card.</p><p><a href="{{task_url}}" style="display: inline-block; padding: 10px 20px; background-color: #fc4c4b; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">Update Task Card</a></p><p>Thanks,<br>Team {{site_name}}</p>'
            ],
            [
                'name' => 'Task Blocked Alert',
                'subject' => 'Task Blocked Alert: {{task_title}}',
                'category' => 'Task Management',
                'content' => '<p>Hello,</p><p>The task <strong>{{task_title}}</strong> has been marked as <strong>Blocked</strong> by {{name}}.</p><p><strong>Reason/Notes:</strong><br>{{reason}}</p><p><a href="{{task_url}}" style="display: inline-block; padding: 10px 20px; background-color: #fc4c4b; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">View Blocked Task</a></p><p>Best regards,<br>Team {{site_name}}</p>'
            ],
            [
                'name' => 'Review Pending Reminder',
                'subject' => 'PM Reminder: Task reviews pending',
                'category' => 'Project Manager Alerts',
                'content' => '<p>Hello PM,</p><p>You have tasks waiting in the <strong>In Review</strong> column for over 24 hours.</p><p>Please review the submitted tasks to prevent bottlenecks in the project delivery path.</p><p><a href="{{reviews_url}}" style="display: inline-block; padding: 10px 20px; background-color: #fc4c4b; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">Go to Review Queue</a></p><p>Best regards,<br>Team {{site_name}}</p>'
            ],
            [
                'name' => 'Performance Warning',
                'subject' => 'Scorecard Warning: Performance below target',
                'category' => 'Performance',
                'content' => '<p>Hello {{name}},</p><p>This is a warning that your monthly performance score has fallen below the 70% target threshold.</p><p>Your current score is: <strong>{{score}}%</strong></p><p>Please review your scorecard to identify and resolve any task delays or check-in violations.</p><p><a href="{{performance_url}}" style="display: inline-block; padding: 10px 20px; background-color: #fc4c4b; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">View Performance Scorecard</a></p><p>Best regards,<br>Team {{site_name}}</p>'
            ],
            [
                'name' => 'Daily Team Performance Summary',
                'subject' => 'Daily Team Performance Summary',
                'category' => 'Reports',
                'content' => '<p>Hello PM / Admin,</p><p>Here is the daily team attendance and clock-in summary:</p><ul><li><strong>Total Active Members:</strong> {{total_members}}</li><li><strong>Checked In on Time:</strong> {{on_time}}</li><li><strong>Checked In Late:</strong> {{late}}</li><li><strong>Absent/Missed Check-in:</strong> {{absent}}</li></ul><p><a href="{{admin_url}}" style="display: inline-block; padding: 10px 20px; background-color: #fc4c4b; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">Go to Management Dashboard</a></p><p>Best regards,<br>Team {{site_name}}</p>'
            ],
            [
                'name' => 'Weekly CEO Executive Summary',
                'subject' => 'Weekly CEO Executive Summary',
                'category' => 'Reports',
                'content' => '<p>Hello CEO / Admin,</p><p>Here is your weekly operational summary:</p><ul><li><strong>Projects Completed / Active:</strong> {{projects_status}}</li><li><strong>Total Invoices Raised:</strong> {{invoices_total}}</li><li><strong>New Leads Captured:</strong> {{leads_count}}</li><li><strong>Overall Team Average Score:</strong> {{avg_score}}%</li></ul><p><a href="{{admin_url}}" style="display: inline-block; padding: 10px 20px; background-color: #fc4c4b; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">Go to Admin Panel</a></p><p>Best regards,<br>Team {{site_name}}</p>'
            ],

            // ==========================================
            // 3. Project Manager Specific Alerts (PM)
            // ==========================================
            [
                'name' => 'Team Daily Check-in Summary',
                'subject' => 'PM Alert: Team Daily Check-in Summary',
                'category' => 'Project Manager Alerts',
                'content' => '<p>Hello PM,</p><p>The daily check-in window is closed. Here is today\'s status for your project team:</p><ul><li><strong>Checked In:</strong> {{checked_in_names}}</li><li><strong>Late:</strong> {{late_names}}</li><li><strong>Absent:</strong> {{absent_names}}</li></ul><p><a href="{{attendance_url}}" style="display: inline-block; padding: 10px 20px; background-color: #fc4c4b; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">View Details</a></p><p>Best regards,<br>Team {{site_name}}</p>'
            ],
            [
                'name' => 'Team Attendance Exception',
                'subject' => 'PM Alert: Attendance Violation Recorded',
                'category' => 'Project Manager Alerts',
                'content' => '<p>Hello PM,</p><p>An attendance violation has been logged for a team member:</p><p><strong>Employee:</strong> {{employee_name}}<br><strong>Violation Type:</strong> {{violation_type}}<br><strong>Details:</strong> {{violation_details}}</p><p>Best regards,<br>Team {{site_name}}</p>'
            ],
            [
                'name' => 'Employee Blocker Alert',
                'subject' => 'PM Alert: Task Blocked by Employee',
                'category' => 'Project Manager Alerts',
                'content' => '<p>Hello PM,</p><p>A task in your project has been marked as <strong>Blocked</strong> by the assignee:</p><p><strong>Task:</strong> {{task_title}}<br><strong>Assignee:</strong> {{employee_name}}<br><strong>Blocker Description:</strong> {{reason}}</p><p><a href="{{task_url}}" style="display: inline-block; padding: 10px 20px; background-color: #fc4c4b; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">Go to Task Card</a></p><p>Best regards,<br>Team {{site_name}}</p>'
            ],
            [
                'name' => 'Task Due Soon — PM Alert',
                'subject' => 'PM Alert: Tasks Due in 24 Hours',
                'category' => 'Project Manager Alerts',
                'content' => '<p>Hello PM,</p><p>The following tasks in your projects are due within the next 24 hours:</p><ul>{{tasks_list}}</ul><p>Please follow up with assignees to ensure timely delivery.</p><p>Best regards,<br>Team {{site_name}}</p>'
            ],
            [
                'name' => 'Task Due Today — PM Alert',
                'subject' => 'PM Alert: Incomplete Tasks Due Today',
                'category' => 'Project Manager Alerts',
                'content' => '<p>Hello PM,</p><p>The following tasks in your projects are due today and remain incomplete:</p><ul>{{tasks_list}}</ul><p>Best regards,<br>Team {{site_name}}</p>'
            ],
            [
                'name' => 'Task Overdue — PM Alert',
                'subject' => 'PM Alert: Overdue Task "{{task_title}}"',
                'category' => 'Project Manager Alerts',
                'content' => '<p>Hello PM,</p><p>The task <strong>{{task_title}}</strong> assigned to {{assignee_name}} has passed its due date without completion.</p><p><strong>Due Date:</strong> {{task_due_date}}</p><p><a href="{{task_url}}" style="display: inline-block; padding: 10px 20px; background-color: #fc4c4b; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">View Overdue Task</a></p><p>Best regards,<br>Team {{site_name}}</p>'
            ],
            [
                'name' => 'Critical Task Overdue — PM Escalation',
                'subject' => 'PM Escalation: Task Overdue > 48 Hours',
                'category' => 'Project Manager Alerts',
                'content' => '<p>Hello PM,</p><p><strong>Escalation:</strong> The task <strong>{{task_title}}</strong> remains incomplete and is now over 48 hours overdue.</p><p><strong>Assignee:</strong> {{assignee_name}}<br><strong>Due Date:</strong> {{task_due_date}}</p><p><a href="{{task_url}}" style="display: inline-block; padding: 10px 20px; background-color: #fc4c4b; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">Review Escalated Task</a></p><p>Best regards,<br>Team {{site_name}}</p>'
            ],
            [
                'name' => 'Task Not Started — PM Alert',
                'subject' => 'PM Alert: Task Not Started (3-Day Limit)',
                'category' => 'Project Manager Alerts',
                'content' => '<p>Hello PM,</p><p>The task <strong>{{task_title}}</strong> was assigned to {{assignee_name}} over 3 days ago but remains in "To Do" / Untouched status.</p><p><a href="{{task_url}}" style="display: inline-block; padding: 10px 20px; background-color: #fc4c4b; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">Check Task Card</a></p><p>Best regards,<br>Team {{site_name}}</p>'
            ],
            [
                'name' => 'Task Inactivity — PM Alert',
                'subject' => 'PM Alert: Task Inactivity (No Progress Logged)',
                'category' => 'Project Manager Alerts',
                'content' => '<p>Hello PM,</p><p>The task <strong>{{task_title}}</strong> assigned to {{assignee_name}} has been in progress for over 3 days without updates, work logs, or comments.</p><p><a href="{{task_url}}" style="display: inline-block; padding: 10px 20px; background-color: #fc4c4b; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">Go to Task Card</a></p><p>Best regards,<br>Team {{site_name}}</p>'
            ],
            [
                'name' => 'Task Submitted for Review',
                'subject' => 'PM Alert: Task Submitted for Review',
                'category' => 'Project Manager Alerts',
                'content' => '<p>Hello PM,</p><p>The task <strong>{{task_title}}</strong> has been submitted for review by {{employee_name}}.</p><p><a href="{{task_url}}" style="display: inline-block; padding: 10px 20px; background-color: #fc4c4b; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">Go to Review Panel</a></p><p>Best regards,<br>Team {{site_name}}</p>'
            ],
            [
                'name' => 'Task Reopened — PM Alert',
                'subject' => 'PM Alert: Completed Task Reopened',
                'category' => 'Project Manager Alerts',
                'content' => '<p>Hello PM,</p><p>The previously completed task <strong>{{task_title}}</strong> has been reopened by {{employee_name}}.</p><p><a href="{{task_url}}" style="display: inline-block; padding: 10px 20px; background-color: #fc4c4b; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">Review Reopened Task</a></p><p>Best regards,<br>Team {{site_name}}</p>'
            ],
            [
                'name' => 'Project Blocker Alert',
                'subject' => 'PM Alert: Project Path Blocked',
                'category' => 'Project Manager Alerts',
                'content' => '<p>Hello PM,</p><p>A critical blocker has been flagged on project <strong>{{project_name}}</strong>.</p><p><strong>Details:</strong><br>{{blocker_details}}</p><p><a href="{{project_url}}" style="display: inline-block; padding: 10px 20px; background-color: #fc4c4b; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">Go to Project Board</a></p><p>Best regards,<br>Team {{site_name}}</p>'
            ],
            [
                'name' => 'Project Deadline Risk Alert',
                'subject' => 'PM Warning: Project Deadline Risk',
                'category' => 'Project Manager Alerts',
                'content' => '<p>Hello PM,</p><p>The project <strong>{{project_name}}</strong> is within 7 days of its deadline, but has <strong>{{pending_percentage}}%</strong> of its tasks incomplete.</p><p><strong>Due Date:</strong> {{due_date}}</p><p><a href="{{project_url}}" style="display: inline-block; padding: 10px 20px; background-color: #fc4c4b; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">Go to Project Dashboard</a></p><p>Best regards,<br>Team {{site_name}}</p>'
            ],
            [
                'name' => 'Milestone Approaching',
                'subject' => 'PM Reminder: Milestone due in 3 days',
                'category' => 'Project Manager Alerts',
                'content' => '<p>Hello PM,</p><p>The milestone <strong>{{milestone_title}}</strong> in project <strong>{{project_name}}</strong> is due within 3 days.</p><p><strong>Due Date:</strong> {{due_date}}</p><p><a href="{{project_url}}" style="display: inline-block; padding: 10px 20px; background-color: #fc4c4b; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">View Milestone</a></p><p>Best regards,<br>Team {{site_name}}</p>'
            ],
            [
                'name' => 'Milestone Missed',
                'subject' => 'PM Alert: Milestone Missed',
                'category' => 'Project Manager Alerts',
                'content' => '<p>Hello PM,</p><p>The milestone <strong>{{milestone_title}}</strong> in project <strong>{{project_name}}</strong> has missed its due date and remains incomplete.</p><p><strong>Original Due Date:</strong> {{due_date}}</p><p><a href="{{project_url}}" style="display: inline-block; padding: 10px 20px; background-color: #fc4c4b; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">View Milestone</a></p><p>Best regards,<br>Team {{site_name}}</p>'
            ],
            [
                'name' => 'Daily Project Performance Summary',
                'subject' => 'PM Report: Daily Project Performance Summary',
                'category' => 'Reports',
                'content' => '<p>Hello PM,</p><p>Here is today\'s update for your managed projects:</p><ul>{{projects_summary}}</ul><p>Best regards,<br>Team {{site_name}}</p>'
            ],
            [
                'name' => 'Weekly Team Performance Report',
                'subject' => 'PM Report: Weekly Team Performance Scorecard',
                'category' => 'Reports',
                'content' => '<p>Hello PM,</p><p>Here is the weekly performance scorecard for your team members:</p><table border="1" cellpadding="5" style="border-collapse: collapse;"><thead><tr><th>Employee</th><th>Tasks Done</th><th>Attendance violations</th><th>Score</th></tr></thead><tbody>{{table_rows}}</tbody></table><p>Best regards,<br>Team {{site_name}}</p>'
            ],
            [
                'name' => 'Weekly Project Performance Report',
                'subject' => 'PM Report: Weekly Project Status Audit',
                'category' => 'Reports',
                'content' => '<p>Hello PM,</p><p>Here is the weekly status audit of your active projects:</p><ul>{{projects_audit}}</ul><p>Best regards,<br>Team {{site_name}}</p>'
            ],
            [
                'name' => 'PM Weekly Accountability Report',
                'subject' => 'Weekly PM Accountability Scorecard',
                'category' => 'Reports',
                'content' => '<p>Hello {{pm_name}},</p><p>Here is your weekly management metrics scorecard:</p><ul><li><strong>Managed Projects:</strong> {{projects_count}}</li><li><strong>Average Review Response Time:</strong> {{avg_response_hours}} hours</li><li><strong>Pending Code Reviews:</strong> {{pending_reviews}}</li><li><strong>Blocked Tasks Resolved:</strong> {{resolved_blockers}}</li><li><strong>Overdue Task Count:</strong> {{overdue_count}}</li></ul><p>Best regards,<br>Management Team</p>'
            ],
            [
                'name' => 'Admin Daily Report',
                'subject' => 'System Daily Operations Report',
                'category' => 'Reports',
                'content' => '<p>Hello Admin,</p><p>Daily operational report summary:</p><ul><li><strong>New Leads:</strong> {{new_leads}}</li><li><strong>Invoices Raised:</strong> {{invoices_count}}</li><li><strong>Support Tickets Open:</strong> {{open_tickets}}</li><li><strong>Active Clock-ins:</strong> {{active_clock_ins}}</li></ul><p>Best regards,<br>System Service</p>'
            ],
            [
                'name' => 'Admin Weekly Report',
                'subject' => 'System Weekly Consolidated Operations Audit',
                'category' => 'Reports',
                'content' => '<p>Hello Admin,</p><p>Consolidated weekly audit metrics:</p><ul><li><strong>Invoices Completed:</strong> {{invoices_total}}</li><li><strong>New Client Registrations:</strong> {{clients_count}}</li><li><strong>Pending Tickets Resolved:</strong> {{resolved_tickets}}</li><li><strong>Team Scorecard Average:</strong> {{average_team_score}}%</li></ul><p>Best regards,<br>System Service</p>'
            ],
        ];
    }
}
