<?php

namespace App\Observers;

use Modules\ProjectManagement\Models\ProjectMessage;
use App\Notifications\NewMessageNotification;

class ProjectMessageObserver
{
    /**
     * Handle the ProjectMessage "created" event.
     */
    public function created(ProjectMessage $message)
    {
        if (!$message->conversation) {
            return;
        }

        // Notify all participants in the conversation except the sender
        $participants = $message->conversation->participants;
        
        foreach ($participants as $participant) {
            $user = $participant->user;
            $sender = $message->sender;
            
            if ($user && $sender && !$user->is($sender)) {
                $user->notify(new NewMessageNotification($message));
            } elseif ($user && !$sender) {
                // Fallback if sender is somehow null
                if ($user->id !== $message->sender_id) {
                    $user->notify(new NewMessageNotification($message));
                }
            }
        }
    }
}
