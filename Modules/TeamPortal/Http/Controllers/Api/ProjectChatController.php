<?php

namespace Modules\TeamPortal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\Project;
use Modules\ProjectManagement\Models\ProjectConversation;
use Modules\ProjectManagement\Models\ProjectMessage;
use Modules\ProjectManagement\Models\ChatMessageRead;

class ProjectChatController extends ApiController
{
    /**
     * GET /api/v1/team/projects/{id}/chat
     * Get chat messages for a specific project.
     * Query Params: type=client_team (default) | internal_team | dm
     */
    public function index(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);
        $user = $request->user();
        $type = $request->query('type', 'client_team');
        
        // Verify project access
        $hasAccess = $project->manager_id === $user->id || 
                    $project->tasks()->where('assigned_to', $user->id)->exists();
                    
        if (!$hasAccess) {
             return $this->sendError('You do not have access to this project.', 403);
        }

        // Find or create conversation
        $conversation = ProjectConversation::firstOrCreate(
            [
                'project_id' => $project->id,
                'type' => $type,
            ]
        );

        // If it's a DM, we might need to verify participants. For now, we enforce visibility:
        // A team member can always see internal_team and client_team if they have project access.
        // For strict visibility, we check `chat_participants` if it's populated. 
        // If chat_participants is empty, we assume general project access.
        if ($conversation->participants()->exists()) {
            $isParticipant = $conversation->participants()
                ->where('user_id', $user->id)
                ->where('user_type', $user->getMorphClass())
                ->exists();

            if (!$isParticipant) {
                return $this->sendError('You are not a participant in this conversation.', 403);
            }
        }

        $messages = $conversation->messages()
            ->with([
                'sender' => function($morphTo) {
                    $morphTo->morphWith([\App\Models\User::class => [], \Modules\Clients\Models\Client::class => [], \App\Models\Admin::class => []]);
                },
                'reads',
                'parent'
            ])
            ->latest()
            ->paginate(50);

        return $this->sendResponse([
            'conversation' => $conversation,
            'messages' => $messages
        ], 'Project chat messages retrieved successfully.');
    }

    /**
     * POST /api/v1/team/projects/{id}/chat
     * Send a new message to the project chat.
     */
    public function store(Request $request, $projectId)
    {
        $request->validate([
            'content' => 'required|string',
            'type' => 'nullable|string|in:client_team,internal_team,dm',
            'parent_id' => 'nullable|exists:project_messages,id',
            'attachment' => 'nullable|file|max:20480|mimes:jpeg,png,jpg,gif,pdf,mp3,wav,mp4,mov', // 20MB limit
        ]);

        $project = Project::findOrFail($projectId);
        $user = $request->user();
        $type = $request->input('type', 'client_team');

        $hasAccess = $project->manager_id === $user->id || 
                    $project->tasks()->where('assigned_to', $user->id)->exists();
                    
        if (!$hasAccess) {
             return $this->sendError('You do not have access to this project.', 403);
        }

        $conversation = ProjectConversation::where('project_id', $project->id)
            ->where('type', $type)
            ->firstOrFail();

        if ($conversation->status === 'suspended' || $conversation->status === 'terminated') {
            return $this->sendError("This conversation is {$conversation->status}.", 403);
        }

        $message = $conversation->messages()->create([
            'sender_id' => $user->id,
            'sender_type' => $user->getMorphClass(),
            'content' => $request->content,
            'parent_id' => $request->parent_id,
        ]);

        if ($request->hasFile('attachment')) {
            $message->addMediaFromRequest('attachment')->toMediaCollection('chat_attachments');
        }

        $message->load(['sender', 'parent', 'media']);

        return $this->sendResponse($message, 'Message sent successfully.', 201);
    }

    /**
     * PUT /api/v1/team/projects/chat/messages/{messageId}
     */
    public function updateMessage(Request $request, $messageId)
    {
        $request->validate(['content' => 'required|string']);

        $message = ProjectMessage::findOrFail($messageId);
        $user = $request->user();

        if ($message->sender_id != $user->id || $message->sender_type != $user->getMorphClass()) {
            return $this->sendError('Unauthorized to edit this message.', 403);
        }

        // Enforce 5 minute rule
        if ($message->created_at->diffInMinutes(now()) > 5) {
            return $this->sendError('Messages can only be edited within 5 minutes of sending.', 403);
        }

        $message->update(['content' => $request->content]);
        $message->load(['sender', 'parent', 'media']);

        return $this->sendResponse($message, 'Message updated successfully.');
    }

    /**
     * DELETE /api/v1/team/projects/chat/messages/{messageId}
     */
    public function deleteMessage(Request $request, $messageId)
    {
        $message = ProjectMessage::findOrFail($messageId);
        $user = $request->user();

        if ($message->sender_id != $user->id || $message->sender_type != $user->getMorphClass()) {
            return $this->sendError('Unauthorized to delete this message.', 403);
        }

        if ($message->created_at->diffInMinutes(now()) > 5) {
            return $this->sendError('Messages can only be deleted within 5 minutes of sending.', 403);
        }

        $message->delete();

        return $this->sendResponse([], 'Message deleted successfully.');
    }

    /**
     * POST /api/v1/team/projects/chat/messages/{messageId}/read
     */
    public function markAsRead(Request $request, $messageId)
    {
        $message = ProjectMessage::findOrFail($messageId);
        $user = $request->user();

        ChatMessageRead::firstOrCreate([
            'message_id' => $message->id,
            'user_id' => $user->id,
            'user_type' => $user->getMorphClass(),
        ]);

        return $this->sendResponse([], 'Message marked as read.');
    }

    /**
     * POST /api/v1/team/projects/{id}/chat/status
     */
    public function updateConversationStatus(Request $request, $projectId)
    {
        $request->validate([
            'type' => 'required|string|in:client_team,internal_team,dm',
            'status' => 'required|in:active,suspended,terminated',
        ]);

        $project = Project::findOrFail($projectId);
        $user = $request->user();

        // Only manager or admin can update status
        if ($project->manager_id !== $user->id) {
            return $this->sendError('Only project managers can change chat status.', 403);
        }

        $conversation = ProjectConversation::where('project_id', $project->id)
            ->where('type', $request->type)
            ->firstOrFail();

        $conversation->update(['status' => $request->status]);

        return $this->sendResponse($conversation, "Conversation status updated to {$request->status}.");
    }
}
