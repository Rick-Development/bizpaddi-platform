<?php

namespace Modules\Contact\Observers;

use Modules\Contact\Models\ContactSubmission;
use Modules\Mcp\Services\SecurityService;

class ContactSubmissionObserver
{
    protected $securityService;

    public function __construct(SecurityService $securityService)
    {
        $this->securityService = $securityService;
    }

    /**
     * Handle the ContactSubmission "created" event.
     */
    public function created(ContactSubmission $contactSubmission): void
    {
        // Run security analysis
        $this->securityService->analyze($contactSubmission);
    }
}
