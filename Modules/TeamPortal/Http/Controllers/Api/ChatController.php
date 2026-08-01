<?php

namespace Modules\TeamPortal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\ClientPortal\Models\Chat;
use Modules\ClientPortal\Models\ChatMessage;

class ChatController extends ApiController
{
    /**
     * Get all chats for the authenticated team member
     */
    public function index(Request $request)
    {
        $chats = Chat::where('user_id', $request->user()->id)
            ->with(['admin'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return $this->sendResponse($chats, 'Chats retrieved successfully.');
    }

    /**
     * Create a new chat
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ]);

        $chat = Chat::create([
            'user_id' => $request->user()->id,
            'subject' => $validated['subject'] ?? 'New Chat',
        ]);

        $messageData = [
            'client_chat_id' => $chat->id,
            'sender_id' => $request->user()->id,
            'sender_type' => 'user',
            'message' => $validated['message'],
        ];

        // Handle file attachment
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('chat-attachments', 'public');
            
            $messageData['attachment_path'] = $path;
            $messageData['attachment_name'] = $file->getClientOriginalName();
            $messageData['attachment_type'] = $file->getMimeType();
        }

        $message = ChatMessage::create($messageData);

        return $this->sendResponse($chat->load('messages'), 'Chat created successfully.', 201);
    }

    /**
     * Get a specific chat with all messages
     */
    public function show(Request $request, $id)
    {
        $chat = Chat::where('user_id', $request->user()->id)
            ->with(['messages', 'admin'])
            ->findOrFail($id);

        // Mark all admin messages as read
        $chat->messages()
            ->where('sender_type', '!=', 'user')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return $this->sendResponse($chat, 'Chat retrieved successfully.');
    }

    /**
     * Send a message in an existing chat
     */
    public function sendMessage(Request $request, $id)
    {
        $chat = Chat::where('user_id', $request->user()->id)->findOrFail($id);

        if ($chat->is_closed) {
            return $this->sendError('This chat is closed', 403);
        }

        $validated = $request->validate([
            'message' => 'nullable|string',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ]);

        if (empty($validated['message']) && !$request->hasFile('attachment')) {
            return $this->sendError('Message or attachment is required', 422);
        }

        $messageData = [
            'client_chat_id' => $chat->id,
            'sender_id' => $request->user()->id,
            'sender_type' => 'user',
            'message' => $validated['message'] ?? null,
        ];

        // Handle file attachment
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('chat-attachments', 'public');
            
            $messageData['attachment_path'] = $path;
            $messageData['attachment_name'] = $file->getClientOriginalName();
            $messageData['attachment_type'] = $file->getMimeType();
        }

        $message = ChatMessage::create($messageData);
        $chat->touch(); // Update chat's updated_at timestamp

        return $this->sendResponse($message, 'Message sent successfully.', 201);
    }

    /**
     * Close a chat
     */
    public function close(Request $request, $id)
    {
        $chat = Chat::where('user_id', $request->user()->id)->findOrFail($id);
        
        $chat->update(['is_closed' => true]);

        return $this->sendResponse([], 'Chat closed successfully.');
    }

    /**
     * Delete a chat
     */
    public function destroy(Request $request, $id)
    {
        $chat = Chat::where('user_id', $request->user()->id)->findOrFail($id);
        
        // Delete all attachments
        foreach ($chat->messages as $message) {
            if ($message->attachment_path) {
                Storage::disk('public')->delete($message->attachment_path);
            }
        }

        $chat->delete();

        return $this->sendResponse([], 'Chat deleted successfully.');
    }
}
