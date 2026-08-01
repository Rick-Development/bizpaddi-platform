<?php

namespace Modules\AI\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\AI\Services\ChatAgent;
use Modules\AI\Models\ChatConversation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    protected $agent;

    public function __construct(ChatAgent $agent)
    {
        $this->agent = $agent;
    }

    /**
     * Start a new chat session.
     */
    public function start(Request $request)
    {
        try {
//            Log::info('Chat Start Request', [
//                'input' => $request->all(),
//                'session_id' => $request->input('session_id'),
//            ]);

            // Check if visitor has existing active session in session/cookie?
            // For simplicity, we just create a new one or continue if session_id passed
            $sessionId = $request->input('session_id');

            if ($sessionId) {
                $conversation = ChatConversation::where('session_id', $sessionId)->first();
                if ($conversation) {
                    $response = [
                        'status' => 'success',
                        'session_id' => $conversation->session_id,
                        'history' => $conversation->messages()->latest()->limit(50)->get()->reverse()->values(),
                    ];
//                    Log::info('Chat Start Response (Existing)', $response);
                    return response()->json($response);
                }
            }

            $conversation = $this->agent->startConversation([
                'session_id' => (string) Str::uuid(),
                'visitor_name' => 'Guest', // Could come from request
            ]);

            $response = [
                'status' => 'success',
                'session_id' => $conversation->session_id,
                'messages' => [],
            ];
            
//            Log::info('Chat Start Response (New)', $response);
            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Chat Start Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Send a message to the chatbot.
     */
    public function send(Request $request)
    {
        try {
//            Log::info('Chat Send Request', [
//                'input' => $request->all(),
//                'session_id' => $request->input('session_id'),
//                'message' => $request->input('message'),
//            ]);

            $request->validate([
                'session_id' => 'required|string',
                'message' => 'required|string',
            ]);

            $conversation = ChatConversation::where('session_id', $request->session_id)->firstOrFail();

//            Log::info('Found Conversation', ['conversation_id' => $conversation->id]);

            // Process message via Agent
            $response = $this->agent->sendMessage($conversation, $request->message);

            $result = [
                'status' => 'success',
                'message' => $response->message,
                'role' => $response->role,
            ];

//            Log::info('Chat Send Response', $result);
            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Chat Send Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
