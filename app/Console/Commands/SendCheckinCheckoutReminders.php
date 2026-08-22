<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Modules\TeamPortal\Models\WorkLog;
use App\Notifications\OperationalMailNotification;
use Carbon\Carbon;

class SendCheckinCheckoutReminders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'team:checkin-checkout-reminders';

    /**
     * The console command description.
     */
    protected $description = 'Send daily attendance reminders and alerts to team members and PMs.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Use Nigeria / West African Time (WAT) for time-of-day checks
        $now = Carbon::now('Africa/Lagos');
        $hour = $now->hour;
        $todayStr = $now->format('Y-m-d');

        $this->info("Running attendance reminders for hour: {$hour} (WAT) on {$todayStr}");

        // Fetch active internal team members (excluding clients/superadmins if they don't do check-ins)
        $team = User::where('status', 'active')
            ->whereIn('role', ['developer', 'project_manager', 'sales_marketing'])
            ->get();

        // Fetch active Project Managers to target PM specific summaries/alerts
        $pms = User::where('status', 'active')
            ->whereIn('role', ['pm', 'project_manager', 'Project Manager'])
            ->get();

        // Load today's worklogs
        $todayWorkLogs = WorkLog::whereDate('start_time', $todayStr)->get();

        // 1. Daily Check-in Reminder (09:00 - 09:59 WAT)
        if ($hour === 9) {
            foreach ($team as $member) {
                $hasCheckIn = $todayWorkLogs->contains('user_id', $member->id);
                if (!$hasCheckIn) {
                    $member->notify(new OperationalMailNotification('Daily Check-in Reminder', [
                        'name' => $member->name,
                        'checkin_url' => route('team.work-logs.index'),
                    ]));
                }
            }
            $this->info('Daily Check-in Reminders dispatched.');
        }

        // 2. Late Check-in Alert & Team Daily Check-in Summary (10:00 - 10:59 WAT)
        if ($hour === 10) {
            $checkedInNames = [];
            $lateNames = [];
            $absentNames = [];

            foreach ($team as $member) {
                $hasCheckIn = $todayWorkLogs->contains('user_id', $member->id);
                if (!$hasCheckIn) {
                    $lateNames[] = $member->name;
                    $member->notify(new OperationalMailNotification('Late Check-in Alert', [
                        'name' => $member->name,
                        'checkin_url' => route('team.work-logs.index'),
                    ]));
                } else {
                    $checkedInNames[] = $member->name;
                }
            }

            // Send daily summary to Project Managers
            foreach ($pms as $pm) {
                $pm->notify(new OperationalMailNotification('Team Daily Check-in Summary', [
                    'checked_in_names' => empty($checkedInNames) ? 'None' : implode(', ', $checkedInNames),
                    'late_names' => empty($lateNames) ? 'None' : implode(', ', $lateNames),
                    'absent_names' => empty($absentNames) ? 'None' : implode(', ', $absentNames),
                    'attendance_url' => route('admin.work-logs.index'),
                ]));
            }
            $this->info('Late Check-in Alerts & PM Check-in Summaries dispatched.');
        }

        // 3. Missed Check-in Alert & PM Exception Alert (11:00 - 11:59 WAT)
        if ($hour === 11) {
            foreach ($team as $member) {
                $hasCheckIn = $todayWorkLogs->contains('user_id', $member->id);
                if (!$hasCheckIn) {
                    // Send alert to employee
                    $member->notify(new OperationalMailNotification('Missed Check-in Alert', [
                        'name' => $member->name,
                    ]));

                    // Notify PMs of attendance exception
                    foreach ($pms as $pm) {
                        $pm->notify(new OperationalMailNotification('Team Attendance Exception', [
                            'employee_name' => $member->name,
                            'violation_type' => 'Missed Daily Check-in',
                            'violation_details' => "Failed to clock in by 11:00 AM on {$todayStr}.",
                        ]));
                    }
                }
            }
            $this->info('Missed Check-in Alerts dispatched.');
        }

        // 4. Mid-day Progress Check (13:00 - 13:59 WAT)
        if ($hour === 13) {
            foreach ($team as $member) {
                // Check if currently clocked in
                $activeSession = WorkLog::where('user_id', $member->id)
                    ->where('status', 'in_progress')
                    ->first();

                if ($activeSession) {
                    $member->notify(new OperationalMailNotification('Mid-day Progress Check', [
                        'name' => $member->name,
                        'dashboard_url' => route('team.kanban.index'),
                    ]));
                }
            }
            $this->info('Mid-day Progress Checks dispatched.');
        }

        // 5. Daily Check-out Reminder (18:00 - 18:59 WAT)
        if ($hour === 18) {
            foreach ($team as $member) {
                $activeSession = WorkLog::where('user_id', $member->id)
                    ->where('status', 'in_progress')
                    ->first();

                if ($activeSession) {
                    $member->notify(new OperationalMailNotification('Daily Check-out Reminder', [
                        'name' => $member->name,
                        'checkout_url' => route('team.work-logs.index'),
                    ]));
                }
            }
            $this->info('Daily Check-out Reminders dispatched.');
        }

        // 6. Missed Check-out Alert & PM Exception Alert (21:00 - 21:59 WAT)
        if ($hour === 21) {
            foreach ($team as $member) {
                $activeSession = WorkLog::where('user_id', $member->id)
                    ->where('status', 'in_progress')
                    ->first();

                if ($activeSession) {
                    // Send warning to employee
                    $member->notify(new OperationalMailNotification('Missed Check-out Alert', [
                        'name' => $member->name,
                        'checkout_url' => route('team.work-logs.index'),
                    ]));

                    // Notify PMs of attendance exception
                    foreach ($pms as $pm) {
                        $pm->notify(new OperationalMailNotification('Team Attendance Exception', [
                            'employee_name' => $member->name,
                            'violation_type' => 'Missed Check-out',
                            'violation_details' => 'Still clocked in at 9:00 PM without ending their session.',
                        ]));
                    }
                }
            }
            $this->info('Missed Check-out Warnings dispatched.');
        }

        // 7. Inactive Work Session Alert (Every Hour)
        // Check active work logs running for > 6 hours without updates
        $inactiveSessions = WorkLog::where('status', 'in_progress')
            ->where('updated_at', '<', Carbon::now()->subHours(6))
            ->get();

        foreach ($inactiveSessions as $session) {
            $user = $session->user;
            if ($user) {
                $user->notify(new OperationalMailNotification('Inactive Work Session Alert', [
                    'name' => $user->name,
                    'checkout_url' => route('team.work-logs.index'),
                ]));
            }
        }
        $this->info(count($inactiveSessions) . ' inactive sessions processed.');

        return Command::SUCCESS;
    }
}
