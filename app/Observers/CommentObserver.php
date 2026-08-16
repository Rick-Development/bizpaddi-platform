<?php

namespace App\Observers;

use Modules\ProjectManagement\Models\Comment;
use App\Notifications\NewCommentNotification;
use App\Models\User;

class CommentObserver
{
    /**
     * Handle the Comment "created" event.
     */
    public function created(Comment $comment)
    {
        $commentable = $comment->commentable;
        if (!$commentable) return;

        $notifiedUserKeys = [];
        $notifiedUserKeys[] = $comment->user_type . ':' . $comment->user_id; // Exclude the commenter

        // If it's a Task
        if (class_basename($comment->commentable_type) === 'Task') {
            // Notify Task Assignee
            if ($commentable->assignee) {
                $assigneeKey = get_class($commentable->assignee) . ':' . $commentable->assignee->id;
                if (!in_array($assigneeKey, $notifiedUserKeys)) {
                    $commentable->assignee->notify(new NewCommentNotification($comment));
                    $notifiedUserKeys[] = $assigneeKey;
                }
            }
        }

        // If it's a Project or Task, notify PMs
        if (in_array(class_basename($comment->commentable_type), ['Task', 'Project'])) {
            $pms = User::whereHas('roles', function($q) {
                $q->where('name', 'project_manager');
            })->get();
            
            foreach($pms as $pm) {
                $pmKey = get_class($pm) . ':' . $pm->id;
                if (!in_array($pmKey, $notifiedUserKeys)) {
                    $pm->notify(new NewCommentNotification($comment));
                    $notifiedUserKeys[] = $pmKey;
                }
            }
        }
    }
}
