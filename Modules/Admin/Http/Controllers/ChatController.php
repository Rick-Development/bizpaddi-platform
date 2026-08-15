<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use Modules\ProjectManagement\Models\ProjectConversation;
use Modules\ProjectManagement\Models\ProjectMessage;
use Modules\ProjectManagement\Models\ChatParticipant;
use Modules\ProjectManagement\Models\ChatMessageRead;
use Modules\ProjectManagement\Events\MessageSent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display the chat dashboard for Admin.
     */
    public function index(Request $request, $conversation = null)
    {
        $currentUser = Auth::guard('admin')->user();
        \Illuminate\Support\Facades\Cache::put('online-' . get_class($currentUser) . '-' . $currentUser->id, true, now()->addSeconds(10));

        // 1. Fetch active conversations for the admin
        $conversations = $this->getConversationsQuery($currentUser);

        // 2. Extract user IDs & types of contacts who already have active direct conversations
        $existingChatKeys = collect($conversations)->map(function ($convo) {
            return $convo['partner_key'] ?? null;
        })->filter()->toArray();

        // 3. Fetch all potential contacts (Users & other Admins) and exclude existing chat partners
        $users = User::where('status', 'active')
            ->orderBy('name')
            ->get()
            ->map(function ($u) {
                $u->type_label = 'Team Member';
                $u->morph_class = get_class($u);
                $u->type = get_class($u);
                $u->key = 'User_' . $u->id;
                return $u;
            })
            ->reject(function ($u) use ($existingChatKeys) {
                return in_array($u->key, $existingChatKeys);
            });

        $admins = Admin::where('id', '!=', $currentUser->id)
            ->orderBy('name')
            ->get()
            ->map(function ($a) {
                $a->type_label = 'Admin';
                $a->morph_class = get_class($a);
                $a->type = get_class($a);
                $a->key = 'Admin_' . $a->id;
                return $a;
            })
            ->reject(function ($a) use ($existingChatKeys) {
                return in_array($a->key, $existingChatKeys);
            });

        $contacts = $users->concat($admins)->values();

        $selectedConversationId = $conversation ?? $request->query('conversation');

        return view('admin::chat.index', compact('contacts', 'conversations', 'selectedConversationId'));
    }


    /**
     * Get active conversations via AJAX.
     */
    public function getConversations(Request $request)
    {
        $currentUser = Auth::guard('admin')->user();
        \Illuminate\Support\Facades\Cache::put('online-' . get_class($currentUser) . '-' . $currentUser->id, true, now()->addSeconds(10));
        $conversations = $this->getConversationsQuery($currentUser);
        
        return response()->json([
            'conversations' => $conversations
        ]);
    }

    /**
     * Fetch messages for a specific conversation.
     */
    public function getMessages(Request $request, ProjectConversation $conversation)
    {
        $currentUser = Auth::guard('admin')->user();
        \Illuminate\Support\Facades\Cache::put('online-' . get_class($currentUser) . '-' . $currentUser->id, true, now()->addSeconds(10));

        // Ensure admin is a participant
        if (!$conversation->participants()->where('user_id', $currentUser->id)->where('user_type', get_class($currentUser))->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Mark incoming messages as read
        $updatedCount = ProjectMessage::where('conversation_id', $conversation->id)
            ->where(function ($q) use ($currentUser) {
                $q->where('sender_id', '!=', $currentUser->id)
                  ->orWhere('sender_type', '!=', get_class($currentUser));
            })
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        if ($updatedCount > 0) {
            broadcast(new \Modules\ProjectManagement\Events\MessageRead(
                $conversation->id,
                $currentUser->id,
                get_class($currentUser)
            ));
        }

        $partner = $conversation->participants()
            ->where(function ($q) use ($currentUser) {
                $q->where('user_id', '!=', $currentUser->id)
                  ->orWhere('user_type', '!=', get_class($currentUser));
            })->first();

        $partnerUser = $partner ? $partner->user : null;
        $partnerName = $partnerUser ? $partnerUser->name : 'Chat Room';
        $partnerAvatar = null;
        if ($partnerUser && method_exists($partnerUser, 'getFirstMediaUrl') && $partnerUser->getFirstMediaUrl('avatar')) {
            $partnerAvatar = $partnerUser->getFirstMediaUrl('avatar');
        }
        $partnerKey = null;
        if ($partnerUser) {
            $shortType = str_contains(get_class($partnerUser), 'Admin') ? 'Admin' : 'User';
            $partnerKey = $shortType . '_' . $partnerUser->id;
        }
        $partnerRole = ($partnerUser && str_contains(get_class($partnerUser), 'Admin')) ? 'Admin' : 'Team Member';

        // Find latest message time sent by partner
        $latestPartnerMessageTime = $conversation->messages()
            ->where(function ($q) use ($currentUser) {
                $q->where('sender_id', '!=', $currentUser->id)
                  ->orWhere('sender_type', '!=', get_class($currentUser));
            })
            ->max('created_at');

        $messages = $conversation->messages()
            ->with(['sender', 'reads'])
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) use ($currentUser, $latestPartnerMessageTime) {
                $isMe = ((int)$message->sender_id === (int)$currentUser->id) && 
                        (str_contains($message->sender_type, 'Admin') === str_contains(get_class($currentUser), 'Admin'));
                
                $isRead = false;
                if ($isMe) {
                    $hasPartnerRead = $message->read_at !== null || 
                                      $message->reads->where('user_id', '!=', $currentUser->id)->count() > 0;
                    $hasPartnerReplied = $latestPartnerMessageTime && ($latestPartnerMessageTime >= $message->created_at);
                    $isRead = $hasPartnerRead || $hasPartnerReplied;
                }


                $avatarUrl = null;
                if ($message->sender) {
                    if (method_exists($message->sender, 'getFirstMediaUrl') && $message->sender->getFirstMediaUrl('avatar')) {
                        $avatarUrl = $message->sender->getFirstMediaUrl('avatar');
                    }
                }

                return [
                    'id' => $message->id,
                    'content' => $message->content,
                    'sender_id' => $message->sender_id,
                    'sender_type' => $message->sender_type,
                    'sender_name' => $message->sender ? $message->sender->name : 'Unknown User',
                    'sender_avatar' => $avatarUrl,
                    'is_me' => $isMe,
                    'is_read' => $isRead,
                    'formatted_time' => $message->created_at->format('g:i A'),
                ];
            });

        $partnerIsOnline = $partnerUser ? \Illuminate\Support\Facades\Cache::has('online-' . $partner->user_type . '-' . $partner->user_id) : false;

        return response()->json([
            'partner_name' => $partnerName,
            'partner_avatar' => $partnerAvatar,
            'partner_key' => $partnerKey,
            'partner_role' => $partnerRole,
            'partner_is_online' => $partnerIsOnline,
            'messages' => $messages
        ]);
    }

    /**
     * Send a message inside a conversation.
     */
    public function sendMessage(Request $request, ProjectConversation $conversation)
    {
        $currentUser = Auth::guard('admin')->user();
        \Illuminate\Support\Facades\Cache::put('online-' . get_class($currentUser) . '-' . $currentUser->id, true, now()->addSeconds(10));

        // Ensure admin is a participant
        if (!$conversation->participants()->where('user_id', $currentUser->id)->where('user_type', get_class($currentUser))->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        // Mark all incoming messages in this conversation as read since sender is actively replying
        $updatedCount = ProjectMessage::where('conversation_id', $conversation->id)
            ->where(function ($q) use ($currentUser) {
                $q->where('sender_id', '!=', $currentUser->id)
                  ->orWhere('sender_type', '!=', get_class($currentUser));
            })
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        if ($updatedCount > 0) {
            broadcast(new \Modules\ProjectManagement\Events\MessageRead(
                $conversation->id,
                $currentUser->id,
                get_class($currentUser)
            ));
        }

        $message = ProjectMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $currentUser->id,
            'sender_type' => get_class($currentUser),
            'content' => $request->content,
        ]);

        // Touch conversation to update updated_at timestamp
        $conversation->touch();

        // Broadcast event over WebSockets instantly
        broadcast(new MessageSent($message));

        $avatarUrl = null;
        if (method_exists($currentUser, 'getFirstMediaUrl') && $currentUser->getFirstMediaUrl('avatar')) {
            $avatarUrl = $currentUser->getFirstMediaUrl('avatar');
        }

        return response()->json([
            'message' => [
                'id' => $message->id,
                'content' => $message->content,
                'sender_id' => $message->sender_id,
                'sender_type' => $message->sender_type,
                'sender_name' => $currentUser->name,
                'sender_avatar' => $avatarUrl,
                'is_me' => true,
                'is_read' => false,
                'formatted_time' => $message->created_at->format('g:i A'),
            ]
        ]);
    }

    /**
     * Mark all messages in a conversation as read.
     */
    public function markAsRead(Request $request, ProjectConversation $conversation)
    {
        $currentUser = Auth::guard('admin')->user();

        if (!$conversation->participants()->where('user_id', $currentUser->id)->where('user_type', get_class($currentUser))->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $updatedCount = ProjectMessage::where('conversation_id', $conversation->id)
            ->where(function ($q) use ($currentUser) {
                $q->where('sender_id', '!=', $currentUser->id)
                  ->orWhere('sender_type', '!=', get_class($currentUser));
            })
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        if ($updatedCount > 0) {
            broadcast(new \Modules\ProjectManagement\Events\MessageRead(
                $conversation->id,
                $currentUser->id,
                get_class($currentUser)
            ));
        }

        return response()->json(['success' => true]);
    }



    /**
     * Start or fetch DM conversation with a contact.
     */
    public function startConversation(Request $request)
    {
        $request->validate([
            'contact_id' => 'required|integer',
            'contact_type' => 'required|string',
        ]);

        $currentUser = Auth::guard('admin')->user();
        
        if ($request->contact_type === Admin::class || $request->contact_type === 'App\Models\Admin') {
            $targetUser = Admin::findOrFail($request->contact_id);
        } else {
            $targetUser = User::findOrFail($request->contact_id);
        }

        // Prevent chatting with self
        if ($currentUser->id === $targetUser->id && get_class($currentUser) === get_class($targetUser)) {
            return response()->json(['error' => 'Cannot start chat with yourself.'], 400);
        }

        $conversation = $this->findOrCreateDirectConversation($currentUser, $targetUser);

        return response()->json([
            'conversation_id' => $conversation->id
        ]);
    }

    /**
     * Get the count of unread messages for the sidebar badge.
     */
    public function getUnreadCount(Request $request)
    {
        $currentUser = Auth::guard('admin')->user();

        // Count messages in user's conversations where there is no read record for this admin
        $unreadCount = ProjectMessage::whereHas('conversation.participants', function ($query) use ($currentUser) {
            $query->where('user_id', $currentUser->id)->where('user_type', get_class($currentUser));
        })
        ->where('sender_id', '!=', $currentUser->id)
        ->whereDoesntHave('reads', function ($query) use ($currentUser) {
            $query->where('user_id', $currentUser->id)->where('user_type', get_class($currentUser));
        })
        ->count();

        return response()->json([
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Helper: Fetch formatted conversations list.
     */
    protected function getConversationsQuery($user)
    {
        $conversations = ProjectConversation::whereHas('participants', function ($query) use ($user) {
            $query->where('user_id', $user->id)->where('user_type', get_class($user));
        })
        ->where(function ($q) {
            $q->whereHas('messages')
              ->orWhere('type', '!=', 'direct');
        })
        ->with(['messages' => function ($q) {
            $q->latest();
        }, 'participants.user'])
        ->get()

        ->map(function ($convo) use ($user) {
            $lastMessage = $convo->messages->first();
            
            // Determine display name and avatar (the other participant)
            $otherParticipant = $convo->participants->first(function ($p) use ($user) {
                return !($p->user_id === $user->id && $p->user_type === get_class($user));
            });

            $name = $convo->name;
            $avatar = null;
            $typeLabel = 'Direct Message';

            if ($convo->type === 'direct' && $otherParticipant && $otherParticipant->user) {
                $name = $otherParticipant->user->name;
                $typeLabel = str_contains($otherParticipant->user_type, 'Admin') ? 'Admin' : 'Team Member';
                if (method_exists($otherParticipant->user, 'getFirstMediaUrl') && $otherParticipant->user->getFirstMediaUrl('avatar')) {
                    $avatar = $otherParticipant->user->getFirstMediaUrl('avatar');
                }
            } elseif ($convo->type === 'internal_team' || $convo->type === 'client_team') {
                $project = \Modules\ProjectManagement\Models\Project::find($convo->project_id);
                $name = $project ? $project->name : 'Project Chat';
                $typeLabel = 'Project Room';
            }

            // Calculate unread count
            $unreadCount = $convo->messages()
                ->where('sender_id', '!=', $user->id)
                ->whereDoesntHave('reads', function ($query) use ($user) {
                    $query->where('user_id', $user->id)->where('user_type', get_class($user));
                })
                ->count();

            $shortType = $otherParticipant ? (str_contains($otherParticipant->user_type, 'Admin') ? 'Admin' : 'User') : null;
            $isOnline = $otherParticipant ? \Illuminate\Support\Facades\Cache::has('online-' . $otherParticipant->user_type . '-' . $otherParticipant->user_id) : false;

            return [
                'id' => $convo->id,
                'name' => $name ?? 'General Room',
                'avatar' => $avatar,
                'type' => $convo->type,
                'type_label' => $typeLabel,
                'partner_key' => $otherParticipant ? ($shortType . '_' . $otherParticipant->user_id) : null,
                'is_online' => $isOnline,
                'unread_count' => $unreadCount,
                'last_message' => $lastMessage ? substr($lastMessage->content, 0, 50) : 'No messages yet',
                'last_message_time' => $lastMessage ? $lastMessage->created_at->diffForHumans() : '',
                'updated_timestamp' => $convo->updated_at->timestamp,
            ];

        })
        ->sortByDesc('updated_timestamp')
        ->values()
        ->toArray();

        return $conversations;
    }

    /**
     * Helper: Find or create direct DM conversation.
     */
    protected function findOrCreateDirectConversation($userA, $userB)
    {
        $conversation = ProjectConversation::where('type', 'direct')
            ->whereHas('participants', function ($query) use ($userA) {
                $query->where('user_id', $userA->id)->where('user_type', get_class($userA));
            })
            ->whereHas('participants', function ($query) use ($userB) {
                $query->where('user_id', $userB->id)->where('user_type', get_class($userB));
            })
            ->first();

        if (!$conversation) {
            $conversation = ProjectConversation::create([
                'type' => 'direct',
                'status' => 'active'
            ]);

            ChatParticipant::create([
                'conversation_id' => $conversation->id,
                'user_id' => $userA->id,
                'user_type' => get_class($userA)
            ]);

            ChatParticipant::create([
                'conversation_id' => $conversation->id,
                'user_id' => $userB->id,
                'user_type' => get_class($userB)
            ]);
        }

        return $conversation;
    }
}
