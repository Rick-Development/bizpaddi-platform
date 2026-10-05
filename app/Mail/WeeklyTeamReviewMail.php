<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class WeeklyTeamReviewMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public User $user;
    public array $assessment;
    public ?string $customNote;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, array $assessment, ?string $customNote = null)
    {
        $this->user = $user;
        $this->assessment = $assessment;
        $this->customNote = $customNote;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $periodLabel = $this->assessment['periodLabel'] ?? 'This Week';
        return new Envelope(
            subject: "Weekly Performance Review: {$this->user->name} ({$periodLabel})",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.team.weekly-review',
            with: [
                'user'        => $this->user,
                'assessment'  => $this->assessment,
                'customNote'  => $this->customNote,
                'portalUrl'   => url('/team/dashboard'),
                'tasksUrl'    => url('/team/my-tasks'),
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
        return [];
    }
}
