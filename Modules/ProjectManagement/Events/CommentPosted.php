<?php

namespace Modules\ProjectManagement\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\ProjectManagement\Models\Comment;

class CommentPosted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;

    /**
     * Create a new event instance.
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('task-comments'),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        $commenter = $this->comment->user;
        $task = $this->comment->commentable; // Can be a Task or a Project
        
        // Resolve commenter avatar url safely
        $avatarUrl = null;
        if ($commenter) {
            if (method_exists($commenter, 'getFirstMediaUrl') && $commenter->getFirstMediaUrl('avatar')) {
                $avatarUrl = $commenter->getFirstMediaUrl('avatar');
            }
        }

        // Get Task/Project specifics for authorization checks on the client side
        $projectId = null;
        $assignedToId = null;
        $managerId = null;
        $taskTitle = '';

        if ($this->comment->commentable_type === \Modules\ProjectManagement\Models\Task::class) {
            $projectId = $task ? $task->project_id : null;
            $assignedToId = $task ? $task->assigned_to : null;
            $managerId = ($task && $task->project) ? $task->project->manager_id : null;
            $taskTitle = $task ? $task->title : '';
        } elseif ($this->comment->commentable_type === \Modules\ProjectManagement\Models\Project::class) {
            $projectId = $task ? $task->id : null;
            $managerId = $task ? $task->manager_id : null;
            $taskTitle = $task ? $task->name : '';
        }

        return [
            'id' => $this->comment->id,
            'commentable_id' => $this->comment->commentable_id,
            'commentable_type' => $this->comment->commentable_type,
            'content' => $this->comment->content,
            'user_id' => $this->comment->user_id,
            'user_type' => $this->comment->user_type,
            'user_name' => $commenter ? $commenter->name : 'Unknown User',
            'user_avatar' => $avatarUrl,
            'task_title' => $taskTitle,
            'project_id' => $projectId,
            'assigned_to_id' => $assignedToId,
            'manager_id' => $managerId,
            'created_at' => $this->comment->created_at->toIso8601String(),
            'formatted_time' => $this->comment->created_at->diffForHumans(),
        ];
    }
}
