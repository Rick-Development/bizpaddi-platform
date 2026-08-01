<?php

namespace Modules\Mcp\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\AI\Services\ChatAgent;
use Modules\AI\Models\ChatConversation;
use Illuminate\Support\Facades\Log;

class ProcessAIRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userMessage;
    protected $userId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $userMessage, int $userId)
    {
        $this->userMessage = $userMessage;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ChatAgent $chatAgent)
    {
        Log::info("Processing AI Request for User {$this->userId}: {$this->userMessage}");

        try {
            // Find or create a temporary conversation context to attach this request to
            // For now, we'll create a new active conversation or use an existing one
            $conversation = ChatConversation::firstOrCreate(
                ['user_id' => $this->userId, 'status' => 'active'],
                [
                    'session_id' => \Illuminate\Support\Str::uuid(),
                    'visitor_name' => 'AI Generator', 
                    'visitor_email' => 'ai@system.local',
                    'status' => 'active',
                    'last_message_at' => now(),
                ]
            );

            // Execute the AI request
            // We pass the conversation context so it logs the interaction
            $response = $chatAgent->sendMessage($conversation, $this->userMessage);

            Log::info("AI Request Completed Successfully: " . json_encode($response));

            // Ideally, we'd notify the user here via WebSocket or Database Notification
            // For now, logging success is sufficient for backend verification

        } catch (\Exception $e) {
            Log::error("AI Request Failed: " . $e->getMessage());
            // Optionally notify user of failure
        }
    }
}
