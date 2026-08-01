<?php

namespace Modules\Admin\Http\Controllers\Api\PM;

use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\Project;
use Modules\ProjectManagement\Models\ProjectConversation;
use Modules\ProjectManagement\Models\ProjectMessage;
use Modules\ProjectManagement\Models\ChatMessageRead;
use App\Http\Controllers\Controller;

class ProjectChatController extends Controller
{
    /**
     * GET /api/v1/admin/pm/projects/{id}/chat
     */
    public function index(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);
        $type = $request->query('type', 'internal_team');
        
        // Admin has full access to all projects and chats

        $conversation = ProjectConversation::firstOrCreate(
            [
                'project_id' => $project->id,
                'type' => $type,
            ]
        );

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

        return response()->json([
            'success' => true,
            'message' => 'Project chat messages retrieved successfully.',
            'data' => [
                'conversation' => $conversation,
                'messages' => $messages
            ]
        ]);
    }

    /**
     * POST /api/v1/admin/pm/projects/{id}/chat
     */
    public function store(Request $request, $projectId)
    {
        $request->validate([
            'content' => 'required|string',
            'type' => 'nullable|string|in:client_team,internal_team,dm',
            'parent_id' => 'nullable|exists:project_messages,id',
            'attachment' => 'nullable|file|max:20480|mimes:jpeg,png,jpg,gif,pdf,mp3,wav,mp4,mov',
        ]);

        $project = Project::findOrFail($projectId);
        $user = $request->user();
        $type = $request->input('type', 'internal_team');

        $conversation = ProjectConversation::where('project_id', $project->id)
            ->where('type', $type)
            ->firstOrFail();

        if ($conversation->status === 'suspended' || $conversation->status === 'terminated') {
            return response()->json([
                'success' => false,
                'message' => "This conversation is {$conversation->status}."
            ], 403);
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

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully.',
            'data' => $message
        ], 201);
    }

    /**
     * PUT /api/v1/admin/pm/projects/chat/messages/{messageId}
     */
    public function updateMessage(Request $request, $messageId)
    {
        $request->validate(['content' => 'required|string']);

        $message = ProjectMessage::findOrFail($messageId);
        $user = $request->user();

        // Admin can edit their own message within 5 mins, or maybe override? Let's keep the 5 min rule.
        if ($message->sender_id != $user->id || $message->sender_type != $user->getMorphClass()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized to edit this message.'], 403);
        }

        if ($message->created_at->diffInMinutes(now()) > 5) {
            return response()->json(['success' => false, 'message' => 'Messages can only be edited within 5 minutes of sending.'], 403);
        }

        $message->update(['content' => $request->content]);
        $message->load(['sender', 'parent', 'media']);

        return response()->json(['success' => true, 'message' => 'Message updated successfully.', 'data' => $message]);
    }

    /**
     * DELETE /api/v1/admin/pm/projects/chat/messages/{messageId}
     */
    public function deleteMessage(Request $request, $messageId)
    {
        $message = ProjectMessage::findOrFail($messageId);
        $user = $request->user();

        // Admin can delete any message, or just their own? Typically Admins can moderate.
        // If moderating, we don't enforce the 5 min rule for other people's messages.
        // But for their own, 5 mins. Let's allow admin full delete capability.
        
        $message->delete();

        return response()->json(['success' => true, 'message' => 'Message deleted successfully.', 'data' => []]);
    }

    /**
     * POST /api/v1/admin/pm/projects/chat/messages/{messageId}/read
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

        return response()->json(['success' => true, 'message' => 'Message marked as read.', 'data' => []]);
    }

    /**
     * POST /api/v1/admin/pm/projects/{id}/chat/status
     */
    public function updateConversationStatus(Request $request, $projectId)
    {
        $request->validate([
            'type' => 'required|string|in:client_team,internal_team,dm',
            'status' => 'required|in:active,suspended,terminated',
        ]);

        $project = Project::findOrFail($projectId);
        $user = $request->user();

        $conversation = ProjectConversation::where('project_id', $project->id)
            ->where('type', $request->type)
            ->firstOrFail();

        $conversation->update(['status' => $request->status]);

        return response()->json(['success' => true, 'message' => "Conversation status updated to {$request->status}.", 'data' => $conversation]);
    }
}
