<?php

namespace Modules\AI\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\AI\Models\ChatConversation;
use Illuminate\Http\Request;

class ChatHistoryController extends Controller
{
    /**
     * Display listing of chat conversations.
     */
    public function index()
    {
        $conversations = ChatConversation::withCount('messages')
            ->latest()
            ->paginate(20);

        return view('ai::admin.chat.index', compact('conversations'));
    }

    /**
     * Display content of a chat conversation.
     */
    public function show($id)
    {
        $conversation = ChatConversation::with('messages')->findOrFail($id);
        return view('ai::admin.chat.show', compact('conversation'));
    }
}
