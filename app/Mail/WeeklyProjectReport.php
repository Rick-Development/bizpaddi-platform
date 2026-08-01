<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Modules\Clients\Models\Client;
use Modules\ProjectManagement\Models\Project;
use Modules\ProjectManagement\Models\Task;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class WeeklyProjectReport extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $client;
    public $project;
    public $reportData;

    /**
     * Create a new message instance.
     */
    public function __construct(Client $client, Project $project)
    {
        $this->client = $client;
        $this->project = $project;
        $this->reportData = $this->compileReportData();
    }

    /**
     * Compile weekly report data
     */
    protected function compileReportData()
    {
        $lastWeekStart = Carbon::now()->subWeek()->startOfWeek();
        $lastWeekEnd = Carbon::now()->subWeek()->endOfWeek();

        return [
            'tasksCompletedLastWeek' => Task::where('project_id', $this->project->id)
                ->where('status', 'done')
                ->whereBetween('completed_at', [$lastWeekStart, $lastWeekEnd])
                ->get(),
            
            'upcomingMilestones' => $this->project->milestones()
                ->where('status', 'pending')
                ->where('due_date', '>=', Carbon::now())
                ->orderBy('due_date', 'asc')
                ->limit(5)
                ->get(),
            
            'pendingApprovals' => Task::where('project_id', $this->project->id)
                ->where('requires_client_approval', true)
                ->where('approval_status', 'pending')
                ->get(),
            
            'overallProgress' => $this->calculateProgress(),
            'weekRange' => [
                'start' => $lastWeekStart->format('M d, Y'),
                'end' => $lastWeekEnd->format('M d, Y')
            ]
        ];
    }

    /**
     * Calculate overall project progress
     */
    protected function calculateProgress()
    {
        $totalTasks = Task::where('project_id', $this->project->id)->count();
        $completedTasks = Task::where('project_id', $this->project->id)
            ->where('status', 'done')
            ->count();

        return $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 2) : 0;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Weekly Progress Report: {$this->project->name}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.projects.weekly-report',
            with: [
                'client' => $this->client,
                'project' => $this->project,
                'reportData' => $this->reportData,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $pdf = Pdf::loadView('emails.projects.weekly-report-pdf', [
            'client' => $this->client,
            'project' => $this->project,
            'reportData' => $this->reportData,
        ]);

        return [
            Attachment::fromData(fn () => $pdf->output(), "weekly-report-{$this->project->id}.pdf")
                ->withMime('application/pdf'),
        ];
    }
}
