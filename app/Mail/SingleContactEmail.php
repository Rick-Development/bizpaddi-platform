<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SingleContactEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subjectLine;
    public $contentBody;

    /**
     * Create a new message instance.
     */
    public function __construct($subject, $content)
    {
        $this->subjectLine = $subject;
        $this->contentBody = $content;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectLine,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.single-contact',
            with: [
                'contentBody' => $this->contentBody,
                'subject' => $this->subjectLine,
            ],
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
