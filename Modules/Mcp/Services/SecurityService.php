<?php

namespace Modules\Mcp\Services;

use Modules\Contact\Models\ContactSubmission;
use Illuminate\Support\Facades\Log;

class SecurityService
{
    /**
     * Analyze a contact submission for spam or threats.
     * In a real MCP setup, this would call an external AI agent.
     * For now, we simulate this with keywords or potential MCP hook.
     */
    public function analyze(ContactSubmission $submission)
    {
        // 1. Prepare data for analysis
        $content = "Subject: {$submission->subject}\nMessage: {$submission->message}\nEmail: {$submission->email}";
        
        // 2. Heuristic Check (Simulator for "AI Analysis")
        // In the future, replace this with $this->mcp->ask('analyze_spam', $content)
        
        $isSpam = $this->heuristicCheck($content);

        if ($isSpam) {
            Log::warning("SecurityService: Deleting SPAM submission ID {$submission->id} from {$submission->email}");
            $submission->delete(); // Soft delete or hard delete depending on model
            // If hard delete is needed and model uses SoftDeletes: $submission->forceDelete();
        }
    }

    protected function heuristicCheck($text)
    {
        $suspiciousKeywords = [
            'risk-free', 'winner', 'lottery', 'crypto investment', 
            'verify your account', 'urgent action', 'bank details',
            'inheritance', 'western union'
        ];

        foreach ($suspiciousKeywords as $keyword) {
            if (stripos($text, $keyword) !== false) {
                return true;
            }
        }

        return false;
    }
}
