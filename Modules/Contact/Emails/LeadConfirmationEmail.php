<?php

namespace Modules\Contact\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LeadConfirmationEmail extends Mailable
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
        return $this->subject('Thank you for reaching out to Rick Development')
                    ->markdown('contact::emails.lead-confirmation');
    }
}
