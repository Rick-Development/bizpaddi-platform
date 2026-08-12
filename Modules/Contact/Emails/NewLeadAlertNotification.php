<?php

namespace Modules\Contact\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewLeadAlertNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $lead;

    /**
     * Create a new message instance.
     */
    public function __construct(\Modules\Contact\Models\Lead $lead) 
    {
        $this->lead = $lead;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->subject('New Project Lead Received: ' . $this->lead->name)
                    ->markdown('contact::emails.new-lead-alert');
    }
}
