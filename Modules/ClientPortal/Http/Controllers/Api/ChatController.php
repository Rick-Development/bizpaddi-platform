<?php

namespace Modules\ClientPortal\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\ClientPortal\Models\Chat;
use Modules\ClientPortal\Models\ChatMessage;

class ChatController extends Controller
{
    /**
     * Get all chats for the authenticated user
     */
    public function index(Request $request)
    {
        $chats = Chat::where('user_id', $request->user()->id)
            ->with(['admin'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $chats,
        ]);
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

        return response()->json([
            'success' => true,
            'data' => $chat->load('messages'),
            'message' => 'Chat created successfully',
        ], 201);
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

        return response()->json([
            'success' => true,
            'data' => $chat,
        ]);
    }

    /**
     * Send a message in an existing chat
     */
    public function sendMessage(Request $request, $id)
    {
        $chat = Chat::where('user_id', $request->user()->id)->findOrFail($id);

        if ($chat->is_closed) {
            return response()->json([
                'success' => false,
                'message' => 'This chat is closed',
            ], 403);
        }

        $validated = $request->validate([
            'message' => 'nullable|string',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ]);

        if (empty($validated['message']) && !$request->hasFile('attachment')) {
            return response()->json([
                'success' => false,
                'message' => 'Message or attachment is required',
            ], 422);
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

        return response()->json([
            'success' => true,
            'data' => $message,
            'message' => 'Message sent successfully',
        ], 201);
    }

    /**
     * Close a chat
     */
    public function close(Request $request, $id)
    {
        $chat = Chat::where('user_id', $request->user()->id)->findOrFail($id);
        
        $chat->update(['is_closed' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Chat closed successfully',
        ]);
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

        return response()->json([
            'success' => true,
            'message' => 'Chat deleted successfully',
        ]);
    }
}
