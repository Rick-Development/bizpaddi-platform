@extends('teamportal::layouts.app')

@section('title', 'Team Portal Chat')

@push('styles')
<style>
    .chat-card-height {
        height: calc(100vh - 8rem);
        min-height: 640px;
    }
    .chat-scroll::-webkit-scrollbar {
        width: 5px;
    }
    .chat-scroll::-webkit-scrollbar-track {
        background: transparent;
    }
    .chat-scroll::-webkit-scrollbar-thumb {
        background-color: rgba(148, 163, 184, 0.3);
        border-radius: 9999px;
    }
    .chat-scroll::-webkit-scrollbar-thumb:hover {
        background-color: rgba(148, 163, 184, 0.5);
    }
    .conversation-item {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }
    .conversation-item:hover {
        background-color: #F1F5F9;
    }
    .conversation-item.active {
        background: linear-gradient(90deg, rgba(251, 76, 75, 0.08) 0%, rgba(25, 28, 71, 0.05) 100%);
        border-left: 4px solid #FB4C4B;
    }
    .message-bubble-me {
        background: linear-gradient(135deg, #191C47 0%, #2b3070 100%);
        color: #FFFFFF;
        box-shadow: 0 4px 14px rgba(25, 28, 71, 0.15);
    }
    .message-bubble-other {
        background: #FFFFFF;
        color: #1E293B;
        border: 1px solid #E2E8F0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }
    .status-dot-online {
        background-color: #10B981;
        box-shadow: 0 0 0 2px #FFFFFF, 0 0 8px rgba(16, 185, 129, 0.6);
    }
    .status-dot-offline {
        background-color: #94A3B8;
        box-shadow: 0 0 0 2px #FFFFFF;
    }
</style>
@endpush

@section('content')
<div class="px-2 sm:px-4 lg:px-6 max-w-7xl mx-auto">
    
    {{-- Top Header Section --}}
    <div class="mb-4 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-2.5">
                <span>Team Chat Workspace</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#FB4C4B] text-white">
                    Team Member
                </span>
            </h1>
            <p class="text-xs text-gray-500 mt-1">Real-time messaging with your team members and management.</p>
        </div>
    </div>

    {{-- Main Container Card --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden chat-card-height flex flex-col md:flex-row">
        
        {{-- ===== LEFT PANEL: CONVERSATIONS & CONTACTS ===== --}}
        <div class="w-full md:w-80 lg:w-96 border-b md:border-b-0 md:border-r border-gray-200 flex flex-col h-full bg-slate-50/60 flex-shrink-0">
            
            {{-- Panel Header & Search --}}
            <div class="p-4 border-b border-gray-200 bg-white">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="h-2.5 w-2.5 rounded-full bg-[#FB4C4B]"></div>
                        <h2 class="text-sm font-bold text-gray-900 tracking-wide uppercase">Conversations</h2>
                    </div>
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-gray-100 text-gray-600">
                        {{ count($conversations) }} Active
                    </span>
                </div>

                {{-- Search Box --}}
                <div class="relative">
                    <input type="text" id="contact-search" placeholder="Search team members..." 
                           class="w-full pl-9 pr-4 py-2 bg-gray-100/80 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#FB4C4B] text-xs transition-all placeholder-gray-400"
                           onkeyup="filterContacts()">
                    <svg class="h-4 w-4 text-gray-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            {{-- Conversations List Container --}}
            <div class="flex-1 overflow-y-auto chat-scroll p-3 space-y-4" id="conversations-list-container">
                
                {{-- Active Conversations Section --}}
                <div>
                    <div class="flex items-center justify-between px-2 mb-2">
                        <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Active Chats</span>
                    </div>

                    <div id="active-conversations-list" class="space-y-1">
                        @forelse($conversations as $convo)
                            <button onclick="loadConversation({{ $convo['id'] }}, this)" 
                                    class="w-full text-left p-3 rounded-2xl flex items-center gap-3 conversation-item group relative"
                                    id="convo-btn-{{ $convo['id'] }}">
                                <div class="relative flex-shrink-0">
                                    @if($convo['avatar'])
                                        <img src="{{ $convo['avatar'] }}" class="h-11 w-11 rounded-full object-cover border-2 border-white shadow-xs" alt="">
                                    @else
                                        <div class="h-11 w-11 rounded-full flex items-center justify-center text-white font-bold text-xs border-2 border-white shadow-xs" style="background: linear-gradient(135deg, #191C47 0%, #FB4C4B 100%);">
                                            {{ strtoupper(substr($convo['name'] ?? 'U', 0, 2)) }}
                                        </div>
                                    @endif
                                    {{-- Presence Indicator Dot --}}
                                    <span class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-white {{ ($convo['is_online'] ?? false) ? 'status-dot-online' : 'status-dot-offline' }}" 
                                          id="presence-dot-{{ $convo['partner_key'] ?? $convo['id'] }}"></span>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-0.5">
                                        <h3 class="text-xs font-bold text-gray-900 truncate group-hover:text-[#FB4C4B] transition-colors">
                                            {{ $convo['name'] }}
                                        </h3>
                                        <span class="text-[10px] text-gray-400 font-medium">
                                            {{ $convo['last_message_time'] ? \Carbon\Carbon::parse($convo['last_message_time'])->diffForHumans() : '' }}
                                        </span>
                                    </div>
                                    
                                    <div class="flex items-center justify-between">
                                        <p class="text-xs text-gray-500 truncate max-w-[180px]">
                                            {{ $convo['last_message'] ?? 'No messages yet' }}
                                        </p>
                                        @if(($convo['unread_count'] ?? 0) > 0)
                                            <span class="h-5 min-w-[20px] px-1.5 rounded-full bg-[#FB4C4B] text-white text-[10px] font-bold flex items-center justify-center shadow-xs">
                                                {{ $convo['unread_count'] }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </button>
                        @empty
                            <p class="text-xs text-gray-400 italic px-3 py-2">No active discussions.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Start New Chat Directory Section --}}
                <div class="pt-2 border-t border-gray-200/80">
                    <div class="flex items-center justify-between px-2 mb-2">
                        <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Start New Chat</span>
                    </div>

                    <div id="team-contacts-list" class="space-y-1">
                        @forelse($contacts as $contact)
                            <button onclick="startDirectChat({{ $contact['id'] }}, '{{ addslashes($contact['type']) }}')"
                                    class="w-full text-left p-2.5 rounded-2xl flex items-center gap-3 hover:bg-gray-100 transition-all contact-search-item"
                                    data-name="{{ strtolower($contact['name']) }}">
                                <div class="relative flex-shrink-0">
                                    @if($contact['avatar'])
                                        <img src="{{ $contact['avatar'] }}" class="h-9 w-9 rounded-full object-cover border border-gray-200" alt="">
                                    @else
                                        <div class="h-9 w-9 rounded-full flex items-center justify-center text-white font-bold text-[10px] shadow-xs" style="background: linear-gradient(135deg, #191C47 0%, #FB4C4B 100%);">
                                            {{ strtoupper(substr($contact['name'], 0, 2)) }}
                                        </div>
                                    @endif
                                    <span class="absolute bottom-0 right-0 h-2.5 w-2.5 rounded-full border-2 border-white {{ ($contact['is_online'] ?? false) ? 'status-dot-online' : 'status-dot-offline' }}" 
                                          id="presence-dot-{{ $contact['key'] }}"></span>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h4 class="text-xs font-semibold text-gray-800 truncate">{{ $contact['name'] }}</h4>
                                    <span class="text-[10px] text-gray-400 font-medium">{{ str_contains($contact['type'], 'Admin') ? 'Admin' : 'Team Member' }}</span>
                                </div>

                                <span class="h-7 w-7 rounded-xl bg-gray-100 text-gray-500 flex items-center justify-center hover:bg-[#FB4C4B] hover:text-white transition-all shadow-xs">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                </span>
                            </button>
                        @empty
                            <p class="text-xs text-gray-400 italic px-3 py-2">No additional team members found.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>

        {{-- ===== RIGHT PANEL: CHAT CANVAS ===== --}}
        <div class="flex-1 flex flex-col h-full bg-white relative">
            
            {{-- Default Placeholder View --}}
            <div id="chat-placeholder" class="flex-1 flex flex-col items-center justify-center p-8 text-center bg-slate-50/40">
                <div class="h-20 w-20 rounded-3xl bg-gradient-to-tr from-[#191C47] to-[#FB4C4B] text-white flex items-center justify-center shadow-lg shadow-red-500/10 mb-4 transform hover:scale-105 transition-transform">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a.596.596 0 01-.743-.65l.398-2.617A8.995 8.995 0 013 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-gray-900">Select a Conversation</h3>
                <p class="text-xs text-gray-500 mt-1 max-w-sm">Choose an active conversation from the left sidebar or start a new chat with any team member.</p>
            </div>

            {{-- Active Chat View --}}
            <div id="active-chat-container" class="hidden flex-1 flex flex-col h-full">
                
                {{-- Active Chat Header --}}
                <div class="px-6 py-3.5 border-b border-gray-200 bg-white/90 backdrop-blur-md flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-3.5">
                        <div class="relative">
                            <div id="active-partner-avatar" class="h-11 w-11 rounded-full object-cover border-2 border-white shadow-xs"></div>
                            <span class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-white status-dot-offline" id="active-partner-dot"></span>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900" id="active-partner-name">...</h3>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-[11px] text-gray-500 font-medium" id="active-partner-status">Offline</span>
                                <span class="text-gray-300">•</span>
                                <span class="text-[11px] text-[#FB4C4B] font-semibold" id="active-partner-role">Team Member</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Messages Canvas --}}
                <div class="flex-1 overflow-y-auto chat-scroll p-6 space-y-4 bg-slate-50/50" id="messages-container">
                    {{-- Dynamically populated --}}
                </div>

                {{-- Input Composer --}}
                <div class="p-4 border-t border-gray-200 bg-white">
                    <form onsubmit="sendMessage(event)" class="flex items-center gap-3">
                        <input type="text" id="message-input" placeholder="Type a message..." 
                               class="flex-1 px-4 py-3 bg-gray-100/90 border border-gray-200 rounded-2xl text-gray-800 text-xs focus:outline-none focus:ring-2 focus:ring-[#FB4C4B] focus:bg-white transition-all placeholder-gray-400"
                               autocomplete="off">

                        <button type="submit" id="send-btn" 
                                class="px-5 py-3 rounded-2xl bg-gradient-to-r from-[#191C47] to-[#FB4C4B] text-white text-xs font-bold shadow-md hover:shadow-lg active:scale-95 transition-all flex items-center gap-2">
                            <span>Send</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                            </svg>
                        </button>
                    </form>
                </div>

            </div>

        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    let activeConversationId = null;
    let activeEchoChannel = null;
    window.onlineUsersSet = new Set();
    window.activePartnerKey = null;
    window.activePartnerIsOnline = false;

    const currentUserId = {{ auth()->id() }};
    const currentUserType = "{{ addslashes(get_class(auth()->user())) }}";
    const initialSelectedConvoId = "{{ $selectedConversationId ?? '' }}";

    document.addEventListener("DOMContentLoaded", () => {
        // Echo Presence Channel online listener
        if (typeof window.Echo !== 'undefined' && window.Echo) {
            try {
                window.Echo.join('online')
                    .here((users) => {
                        window.onlineUsersSet.clear();
                        users.forEach(u => {
                            window.onlineUsersSet.add(u.key);
                            updatePresenceStatus(u.key, true);
                        });
                    })
                    .joining((user) => {
                        window.onlineUsersSet.add(user.key);
                        updatePresenceStatus(user.key, true);
                    })
                    .leaving((user) => {
                        window.onlineUsersSet.delete(user.key);
                        updatePresenceStatus(user.key, false);
                    });
            } catch(e) {
                console.error('Echo presence join error:', e);
            }
        }

        // Auto load conversation if URL contains ID
        if (initialSelectedConvoId) {
            const btn = document.getElementById(`convo-btn-${initialSelectedConvoId}`);
            loadConversation(initialSelectedConvoId, btn);
        }
    });

    function updatePresenceStatus(userKey, isOnline) {
        const dot = document.getElementById(`presence-dot-${userKey}`);
        if (dot) {
            if (isOnline) {
                dot.classList.remove('status-dot-offline');
                dot.classList.add('status-dot-online');
            } else {
                dot.classList.remove('status-dot-online');
                dot.classList.add('status-dot-offline');
            }
        }

        if (window.activePartnerKey === userKey) {
            window.activePartnerIsOnline = isOnline;
            const headerDot = document.getElementById('active-partner-dot');
            const headerStatus = document.getElementById('active-partner-status');
            if (headerDot) {
                if (isOnline) {
                    headerDot.classList.remove('status-dot-offline');
                    headerDot.classList.add('status-dot-online');
                    if (headerStatus) headerStatus.innerText = 'Online';
                } else {
                    headerDot.classList.remove('status-dot-online');
                    headerDot.classList.add('status-dot-offline');
                    if (headerStatus) headerStatus.innerText = 'Offline';
                }
            }

            // Update unread ticks to reflect online delivery state
            updateUnreadTicksOnlineState(isOnline);
        }
    }

    function filterContacts() {
        const q = document.getElementById('contact-search').value.toLowerCase();
        document.querySelectorAll('.contact-search-item').forEach(el => {
            const name = el.getAttribute('data-name');
            if (name.includes(q)) {
                el.classList.remove('hidden');
            } else {
                el.classList.add('hidden');
            }
        });
    }

    function loadConversation(conversationId, element = null) {
        activeConversationId = conversationId;

        // Update URL to /team/chat/{id} seamlessly
        if (history.pushState) {
            history.pushState(null, null, `/team/chat/${conversationId}`);
        }

        // Highlight active item
        document.querySelectorAll('.conversation-item').forEach(el => el.classList.remove('active'));
        if (element) {
            element.classList.add('active');
        } else {
            const activeBtn = document.getElementById(`convo-btn-${conversationId}`);
            if (activeBtn) activeBtn.classList.add('active');
        }

        // Show active container
        document.getElementById('chat-placeholder').classList.add('hidden');
        document.getElementById('active-chat-container').classList.remove('hidden');

        // Leave existing Echo channel
        if (activeEchoChannel && window.Echo) {
            window.Echo.leave(`conversation.${activeEchoChannel}`);
        }

        // Fetch Messages via API
        axios.get(`/team/chat-api/conversations/${conversationId}/messages`)
            .then(res => {
                const data = res.data;
                
                // Update partner header
                window.activePartnerKey = data.partner_key;
                window.activePartnerIsOnline = window.onlineUsersSet.has(data.partner_key);

                document.getElementById('active-partner-name').innerText = data.partner_name;
                document.getElementById('active-partner-role').innerText = data.partner_role;

                const avatarBox = document.getElementById('active-partner-avatar');
                if (data.partner_avatar) {
                    avatarBox.innerHTML = `<img src="${data.partner_avatar}" class="h-11 w-11 rounded-full object-cover border-2 border-white shadow-xs" alt="">`;
                } else {
                    avatarBox.innerHTML = `<div class="h-11 w-11 rounded-full flex items-center justify-center text-white font-bold text-xs shadow-xs" style="background: linear-gradient(135deg, #191C47 0%, #FB4C4B 100%);">${data.partner_name.substring(0, 2).toUpperCase()}</div>`;
                }

                updatePresenceStatus(data.partner_key, window.activePartnerIsOnline);

                // Render Messages
                renderMessages(data.messages);

                // Listen for real-time messages & read receipts on conversation channel
                activeEchoChannel = conversationId;
                if (window.Echo) {
                    window.Echo.private(`conversation.${conversationId}`)
                        .listen('MessageSent', (e) => appendRealtimeMessage(e))
                        .listen('.MessageSent', (e) => appendRealtimeMessage(e))
                        .listen('.Modules\\ProjectManagement\\Events\\MessageSent', (e) => appendRealtimeMessage(e))
                        .listen('MessageRead', (e) => updateAllTicksToRead())
                        .listen('.MessageRead', (e) => updateAllTicksToRead())
                        .listen('.Modules\\ProjectManagement\\Events\\MessageRead', (e) => updateAllTicksToRead());
                }
            })
            .catch(err => {
                console.error('Failed to load conversation messages:', err);
            });
    }

    // Fast 2-second background poll fallback to guarantee instant delivery without page refresh
    setInterval(() => {
        if (activeConversationId) {
            axios.get(`/team/chat-api/conversations/${activeConversationId}/messages`)
                .then(res => {
                    const messages = res.data.messages || [];
                    let hasNew = false;
                    messages.forEach(msg => {
                        if (!document.getElementById(`msg-${msg.id}`)) {
                            const isMe = (parseInt(msg.sender_id) === currentUserId) && 
                         (msg.sender_type.includes('Admin') === currentUserType.includes('Admin'));

                            appendRealtimeMessage(msg);
                            hasNew = true;
                        }
                    });
                })
                .catch(() => {});
        }
    }, 2000);



    function startDirectChat(userId, userType) {
        axios.post('/team/chat-api/conversations/start', {
            contact_id: userId,
            contact_type: userType
        })
        .then(res => {
            if (res.data.conversation_id) {
                window.location.href = `/team/chat/${res.data.conversation_id}`;
            }
        })
        .catch(err => {
            console.error('Failed to start chat:', err);
        });
    }

    function renderMessages(messages) {
        const container = document.getElementById('messages-container');
        container.innerHTML = '';

        if (!messages || messages.length === 0) {
            container.innerHTML = `<p class="text-xs text-gray-400 text-center py-8">No messages yet. Send a message to start the discussion!</p>`;
            return;
        }

        messages.forEach(msg => {
            const isMe = (parseInt(msg.sender_id) === currentUserId) && 
                         (msg.sender_type.includes('Admin') === currentUserType.includes('Admin'));

            const html = buildMessageBubble(msg, isMe);
            container.insertAdjacentHTML('beforeend', html);
        });

        scrollToBottom();
    }

    function getTickHtml(isRead) {
        // 1. Double Coral Red Ticks (Read)
        if (isRead) {
            return `<span class="tick-status text-[#FB4C4B] font-bold text-xs flex items-center -space-x-1" data-read="true" title="Read">
                        <span>✓</span><span>✓</span>
                    </span>`;
        }
        
        // 2. Double Gray Ticks (Delivered / Counterpart Online)
        if (window.activePartnerIsOnline) {
            return `<span class="tick-status text-slate-400 font-bold text-xs flex items-center -space-x-1" data-read="false" title="Delivered">
                        <span>✓</span><span>✓</span>
                    </span>`;
        }

        // 3. Single Gray Tick (Sent / Counterpart Offline)
        return `<span class="tick-status text-slate-400 font-bold text-xs" data-read="false" title="Sent">✓</span>`;
    }

    function buildMessageBubble(msg, isMe) {
        const bubbleClass = isMe ? 'message-bubble-me' : 'message-bubble-other';
        const alignClass = isMe ? 'justify-end' : 'justify-start';
        const tickHtml = isMe ? getTickHtml(msg.is_read) : '';

        return `
            <div class="flex ${alignClass} mb-3 msg-row" id="msg-${msg.id}">
                <div class="max-w-[75%] md:max-w-[65%]">
                    <div class="px-4 py-3 rounded-2xl text-xs md:text-sm leading-relaxed ${bubbleClass}">
                        ${escapeHtml(msg.content)}
                    </div>
                    <div class="flex items-center gap-1.5 mt-1 px-1 ${isMe ? 'justify-end' : 'justify-start'}">
                        <span class="text-[10px] text-gray-400 font-medium">${msg.formatted_time}</span>
                        ${tickHtml}
                    </div>
                </div>
            </div>
        `;
    }

    function appendRealtimeMessage(msg) {
        const container = document.getElementById('messages-container');
        const isMe = (parseInt(msg.sender_id) === currentUserId) && 
                     (msg.sender_type.includes('Admin') === currentUserType.includes('Admin'));

        
        if (container.querySelector('p')) {
            container.innerHTML = '';
        }

        // If incoming message from counterpart, update all previous ticks to Double Primary Red Read ticks!
        if (!isMe) {
            updateAllTicksToRead();
            if (activeConversationId) {
                axios.post(`/team/chat-api/conversations/${activeConversationId}/read`).catch(() => {});
            }
        }

        // Don't duplicate message if already appended locally
        if (document.getElementById(`msg-${msg.id}`)) return;

        const html = buildMessageBubble(msg, isMe);
        container.insertAdjacentHTML('beforeend', html);
        scrollToBottom();
    }


    function updateAllTicksToRead() {
        document.querySelectorAll('.tick-status').forEach(el => {
            el.setAttribute('data-read', 'true');
            el.className = 'tick-status text-[#FB4C4B] font-bold text-xs flex items-center -space-x-1';
            el.innerHTML = '<span>✓</span><span>✓</span>';
            el.setAttribute('title', 'Read');
        });
    }

    function updateUnreadTicksOnlineState(isOnline) {
        document.querySelectorAll('.tick-status[data-read="false"]').forEach(el => {
            if (isOnline) {
                el.className = 'tick-status text-slate-400 font-bold text-xs flex items-center -space-x-1';
                el.innerHTML = '<span>✓</span><span>✓</span>';
                el.setAttribute('title', 'Delivered');
            } else {
                el.className = 'tick-status text-slate-400 font-bold text-xs';
                el.innerHTML = '✓';
                el.setAttribute('title', 'Sent');
            }
        });
    }

    function sendMessage(e) {
        e.preventDefault();
        const input = document.getElementById('message-input');
        const content = input.value.trim();

        if (!content || !activeConversationId) return;

        input.value = '';

        axios.post(`/team/chat-api/conversations/${activeConversationId}/messages`, {
            content: content
        })
        .then(res => {
            if (res.data.message) {
                appendRealtimeMessage(res.data.message);
            }
        })
        .catch(err => {
            console.error('Failed to send message:', err);
        });
    }

    function scrollToBottom() {
        const container = document.getElementById('messages-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.innerText = text;
        return div.innerHTML;
    }
</script>
@endpush
