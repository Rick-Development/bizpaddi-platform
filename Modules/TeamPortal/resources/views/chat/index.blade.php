@extends('teamportal::layouts.app')

@section('title', 'Team Chat')

@push('styles')
<style>
    .chat-card-height {
        height: calc(100vh - 7rem);
        min-height: 600px;
    }
    .chat-scroll::-webkit-scrollbar {
        width: 5px;
    }
    .chat-scroll::-webkit-scrollbar-track {
        background: transparent;
    }
    .chat-scroll::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 9999px;
    }
    .chat-scroll::-webkit-scrollbar-thumb:hover {
        background-color: #94a3b8;
    }
    .conversation-item {
        transition: all 0.15s ease-in-out;
    }
    .conversation-item:hover {
        background-color: #ffffff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .conversation-item.active {
        background-color: #ffffff;
        border-left: 4px solid #FB4C4B;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }
    .message-bubble-me {
        background: linear-gradient(135deg, #FB4C4B 0%, #e03b3a 100%);
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(251, 76, 75, 0.2);
    }
    .message-bubble-other {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        color: #1e293b;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
    }
    .status-dot-online {
        background-color: #10b981; /* emerald-500 */
    }
    .status-dot-offline {
        background-color: #cbd5e1; /* slate-300 */
    }
</style>
@endpush

@section('content')
<div class="px-2 sm:px-4 lg:px-6 max-w-7xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden chat-card-height flex flex-col md:flex-row">
        
        {{-- ===== LEFT PANEL: CONVERSATIONS & CONTACTS ===== --}}
        <div class="w-full md:w-80 lg:w-96 border-b md:border-b-0 md:border-r border-gray-200 bg-gray-50/60 flex flex-col h-full flex-shrink-0">
            
            {{-- Panel Header & Search --}}
            <div class="p-4 border-b border-gray-200 bg-white">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-[#FB4C4B]"></span>
                        <h2 class="text-sm font-bold text-gray-900">Team Chat</h2>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center gap-1">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span> WebSocket Presence
                    </span>
                </div>

                {{-- Search Box --}}
                <div class="relative">
                    <input type="text" id="contact-search" placeholder="Search team members..." 
                           class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FB4C4B]/30 focus:border-[#FB4C4B] text-xs transition-all placeholder-gray-400"
                           onkeyup="filterContacts()">
                    <svg class="h-4 w-4 text-gray-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            {{-- Conversations & Contacts Scroll Container --}}
            <div class="flex-1 overflow-y-auto chat-scroll p-3 space-y-4" id="conversations-list-container">
                
                {{-- Active Conversations --}}
                <div>
                    <div class="flex items-center justify-between px-2 mb-2">
                        <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Active Chats</span>
                        <span class="text-[10px] text-gray-500 font-medium">{{ count($conversations) }}</span>
                    </div>

                    <div id="active-conversations-list" class="space-y-1">
                        @forelse($conversations as $convo)
                            <button onclick="loadConversation({{ $convo['id'] }}, this)" 
                                    class="w-full text-left p-3 rounded-xl flex items-center gap-3 conversation-item group relative"
                                    id="convo-btn-{{ $convo['id'] }}"
                                    data-user-key="{{ $convo['other_user_key'] ?? '' }}">
                                <div class="relative flex-shrink-0">
                                    @if($convo['avatar'])
                                        <img src="{{ $convo['avatar'] }}" class="h-10 w-10 rounded-xl object-cover ring-1 ring-gray-200" alt="">
                                    @else
                                        <div class="h-10 w-10 rounded-xl flex items-center justify-center text-white font-bold text-xs ring-1 ring-gray-200" style="background: linear-gradient(135deg, #191C47 0%, #FB4C4B 100%);">
                                            {{ strtoupper(substr($convo['name'], 0, 2)) }}
                                        </div>
                                    @endif
                                    <span class="status-dot status-dot-offline absolute -bottom-0.5 -right-0.5 h-2.5 w-2.5 border-2 border-white rounded-full transition-colors" data-status-key="{{ $convo['other_user_key'] ?? '' }}"></span>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-xs font-semibold text-gray-900 truncate">{{ $convo['name'] }}</h4>
                                        <span class="text-[10px] text-gray-400 font-medium">{{ $convo['last_message_time'] }}</span>
                                    </div>
                                    <p class="text-[11px] text-gray-500 truncate mt-0.5" id="convo-last-msg-{{ $convo['id'] }}">{{ $convo['last_message'] }}</p>
                                </div>

                                <span class="h-5 min-w-[20px] px-1.5 bg-[#FB4C4B] text-white rounded-full text-[10px] font-bold flex items-center justify-center shadow-xs {{ $convo['unread_count'] > 0 ? '' : 'hidden' }}" 
                                      id="convo-badge-{{ $convo['id'] }}">{{ $convo['unread_count'] }}</span>
                            </button>
                        @empty
                            <div class="text-center py-6 px-4 rounded-xl border border-dashed border-gray-200 bg-white" id="no-convos-msg">
                                <p class="text-xs text-gray-500 font-medium">No open conversations</p>
                                <p class="text-[11px] text-gray-400 mt-0.5">Click a team member below to start</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Team Contacts Directory --}}
                <div class="pt-2 border-t border-gray-200">
                    <div class="flex items-center justify-between px-2 mb-2">
                        <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Start New Chat</span>
                        <span class="text-[10px] text-gray-500 font-medium">{{ count($contacts) }} members</span>
                    </div>

                    <div id="all-contacts-list" class="space-y-1">
                        @foreach($contacts as $contact)
                            <button onclick="startChat({{ $contact->id }}, '{{ addslashes($contact->morph_class) }}', this)" 
                                    class="w-full text-left p-2.5 rounded-xl flex items-center gap-3 hover:bg-white transition-all contact-row group"
                                    data-name="{{ strtolower($contact->name) }}"
                                    data-user-key="{{ $contact->morph_class }}_{{ $contact->id }}">
                                <div class="relative flex-shrink-0">
                                    @if(method_exists($contact, 'getFirstMediaUrl') && $contact->getFirstMediaUrl('avatar'))
                                        <img src="{{ $contact->getFirstMediaUrl('avatar') }}" class="h-8 w-8 rounded-xl object-cover ring-1 ring-gray-200" alt="">
                                    @else
                                        <div class="h-8 w-8 rounded-xl flex items-center justify-center text-white font-bold text-[11px]" style="background: linear-gradient(135deg, #191C47 0%, #3B82F6 100%);">
                                            {{ strtoupper(substr($contact->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <span class="status-dot status-dot-offline absolute -bottom-0.5 -right-0.5 h-2 w-2 border border-white rounded-full transition-colors" data-status-key="{{ $contact->morph_class }}_{{ $contact->id }}"></span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-xs font-medium text-gray-900 truncate group-hover:text-[#FB4C4B] transition-colors">{{ $contact->name }}</h4>
                                    <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[9px] font-medium bg-gray-100 text-gray-600 mt-0.5">{{ $contact->type_label }}</span>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== RIGHT PANEL: CHAT WINDOW ===== --}}
        <div class="flex-1 flex flex-col h-full bg-slate-50/50 relative overflow-hidden" id="chat-window-wrapper">
            
            {{-- Placeholder View --}}
            <div id="chat-placeholder" class="flex-1 flex flex-col items-center justify-center p-8 text-center bg-white">
                <div class="h-16 w-16 rounded-2xl bg-red-50 text-[#FB4C4B] flex items-center justify-center mb-4 border border-red-100 shadow-xs">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025 10.338 10.338 0 01-1.99-4.774 10.314 10.314 0 011.99-4.774M21 12c0-4.556-4.03-8.25-9-8.25a9.764 9.764 0 00-2.555.337A5.972 5.972 0 005.41 3.03a5.97 5.97 0 00-.474.065 4.48 4.48 0 01.978 2.025 10.338 10.338 0 00-1.99 4.774" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900">Select a Conversation</h3>
                <p class="text-xs text-gray-500 mt-1 max-w-xs leading-relaxed">Choose a contact from the left sidebar to start messaging in real-time.</p>
            </div>

            {{-- Main Active Chat View --}}
            <div id="chat-active" style="display:none;" class="flex-1 flex flex-col h-full overflow-hidden">
                
                {{-- Chat Header --}}
                <div class="px-6 py-3.5 border-b border-gray-200 bg-white flex items-center justify-between z-10 shadow-2xs">
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <div id="active-user-avatar" class="h-10 w-10 rounded-xl flex items-center justify-center text-white font-bold text-xs ring-1 ring-gray-200"></div>
                            <span id="active-header-dot" class="absolute -bottom-0.5 -right-0.5 h-2.5 w-2.5 bg-gray-300 border-2 border-white rounded-full transition-colors"></span>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900" id="active-user-name">...</h3>
                            <span class="text-[11px] font-medium text-gray-400" id="active-user-status">Offline</span>
                        </div>
                    </div>

                    {{-- Header Controls --}}
                    <div class="flex items-center gap-2">
                        <button onclick="if(currentConversationId) openChatWindow(currentConversationId, currentContactName, currentContactAvatar)" 
                                class="p-2 rounded-xl text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-all" title="Refresh conversation">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Messages List --}}
                <div class="flex-1 p-4 md:p-6 overflow-y-auto chat-scroll space-y-4 bg-slate-50/50" id="messages-list">
                    {{-- Dynamic content --}}
                </div>

                {{-- Message Input Bar --}}
                <div class="p-3 md:p-4 border-t border-gray-200 bg-white">
                    <form id="message-form" onsubmit="event.preventDefault(); sendChatMessage();" class="flex items-center gap-2">
                        <div class="flex-1 flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-2xl px-4 py-1.5 focus-within:bg-white focus-within:border-[#FB4C4B] focus-within:ring-2 focus-within:ring-[#FB4C4B]/20 transition-all shadow-inner">
                            
                            <input type="text" id="message-input" placeholder="Type a message... (Press Enter to send)" required
                                   class="flex-1 bg-transparent border-0 text-xs md:text-sm text-gray-800 placeholder-gray-400 focus:outline-none py-2"
                                   autocomplete="off">

                            <button type="button" onclick="insertQuickEmoji('👍')" class="text-gray-400 hover:text-yellow-500 transition-colors text-sm px-1" title="Thumbs Up">👍</button>
                            <button type="button" onclick="insertQuickEmoji('🚀')" class="text-gray-400 hover:text-gray-700 transition-colors text-sm px-1" title="Rocket">🚀</button>
                        </div>

                        <button type="submit" 
                                class="h-11 w-11 rounded-2xl bg-[#FB4C4B] hover:bg-[#e03b3a] active:scale-95 text-white shadow-md hover:shadow-lg transition-all duration-150 flex items-center justify-center flex-shrink-0 cursor-pointer">
                            <svg class="h-5 w-5 transform rotate-90" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
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
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    let currentConversationId = null;
    let currentChannel = null;
    let currentContactName = '';
    let currentContactAvatar = null;
    let currentContactKey = null;
    const onlineUserKeys = new Set();

    document.addEventListener('DOMContentLoaded', function() {
        initPresenceChannel();
    });

    // Real-time WebSocket Presence Tracking
    function initPresenceChannel() {
        if (typeof window.Echo === 'undefined' || !window.Echo) {
            console.warn('Echo is not initialized yet.');
            return;
        }

        try {
            window.Echo.join('online')
                .here((users) => {
                    onlineUserKeys.clear();
                    users.forEach(u => onlineUserKeys.add(u.key));
                    syncAllPresenceDots();
                })
                .joining((user) => {
                    onlineUserKeys.add(user.key);
                    updateSinglePresenceDot(user.key, true);
                })
                .leaving((user) => {
                    onlineUserKeys.delete(user.key);
                    updateSinglePresenceDot(user.key, false);
                });
        } catch(e) {
            console.error('Presence channel error:', e);
        }
    }

    function syncAllPresenceDots() {
        document.querySelectorAll('[data-status-key]').forEach(dot => {
            const key = dot.getAttribute('data-status-key');
            if (onlineUserKeys.has(key)) {
                dot.classList.remove('status-dot-offline');
                dot.classList.add('status-dot-online');
            } else {
                dot.classList.remove('status-dot-online');
                dot.classList.add('status-dot-offline');
            }
        });
        updateActiveHeaderStatus();
    }

    function updateSinglePresenceDot(userKey, isOnline) {
        document.querySelectorAll(`[data-status-key="${userKey}"]`).forEach(dot => {
            if (isOnline) {
                dot.classList.remove('status-dot-offline');
                dot.classList.add('status-dot-online');
            } else {
                dot.classList.remove('status-dot-online');
                dot.classList.add('status-dot-offline');
            }
        });
        updateActiveHeaderStatus();
    }

    function updateActiveHeaderStatus() {
        if (!currentContactKey) return;
        const statusText = document.getElementById('active-user-status');
        const statusDot = document.getElementById('active-header-dot');
        
        if (onlineUserKeys.has(currentContactKey)) {
            if (statusText) {
                statusText.innerText = 'Online';
                statusText.className = 'text-[11px] font-medium text-emerald-600';
            }
            if (statusDot) {
                statusDot.className = 'absolute -bottom-0.5 -right-0.5 h-2.5 w-2.5 bg-emerald-500 border-2 border-white rounded-full transition-colors';
            }
        } else {
            if (statusText) {
                statusText.innerText = 'Offline';
                statusText.className = 'text-[11px] font-medium text-gray-400';
            }
            if (statusDot) {
                statusDot.className = 'absolute -bottom-0.5 -right-0.5 h-2.5 w-2.5 bg-gray-300 border-2 border-white rounded-full transition-colors';
            }
        }
    }

    function filterContacts() {
        const query = document.getElementById('contact-search').value.toLowerCase();
        const contacts = document.querySelectorAll('.contact-row');
        contacts.forEach(contact => {
            const name = contact.getAttribute('data-name');
            contact.style.display = name.includes(query) ? '' : 'none';
        });
    }

    function insertQuickEmoji(emoji) {
        const input = document.getElementById('message-input');
        input.value += ' ' + emoji;
        input.focus();
    }

    function startChat(contactId, contactType, btn) {
        const originalOpacity = btn.style.opacity;
        btn.disabled = true;
        btn.style.opacity = '0.6';

        axios.post("{{ route('team.chat.conversations.start') }}", {
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            contact_id: contactId,
            contact_type: contactType
        }).then(response => {
            const convoId = response.data.conversation_id;

            btn.disabled = false;
            btn.style.opacity = originalOpacity;

            const nameEl = btn.querySelector('h4');
            const avatarImg = btn.querySelector('img');
            currentContactName = nameEl ? nameEl.innerText : 'Chat';
            currentContactAvatar = avatarImg ? avatarImg.src : null;

            axios.get("{{ route('team.chat.conversations') }}")
                .then(res => {
                    renderConversationsList(res.data.conversations);
                    openChatWindow(convoId, currentContactName, currentContactAvatar);
                });
        }).catch(err => {
            btn.disabled = false;
            btn.style.opacity = originalOpacity;
            console.error('Error starting conversation:', err.response ? err.response.data : err);
            alert('Could not start conversation. Please try again.');
        });
    }

    function loadConversation(convoId, element) {
        document.querySelectorAll('.conversation-item').forEach(item => item.classList.remove('active'));
        element.classList.add('active');

        const nameEl = element.querySelector('h4');
        const avatarImg = element.querySelector('img');
        const name = nameEl ? nameEl.innerText : 'Chat';
        const avatar = avatarImg ? avatarImg.src : null;
        const userKey = element.getAttribute('data-user-key');

        openChatWindow(convoId, name, avatar, userKey);
    }

    function openChatWindow(convoId, name, avatarSrc, userKey = null) {
        currentConversationId = convoId;
        currentContactName = name;
        currentContactAvatar = avatarSrc;
        currentContactKey = userKey;

        document.getElementById('chat-placeholder').style.display = 'none';
        const chatActive = document.getElementById('chat-active');
        chatActive.style.display = 'flex';

        const activeAvatar = document.getElementById('active-user-avatar');
        document.getElementById('active-user-name').innerText = name;
        
        if (avatarSrc) {
            activeAvatar.innerHTML = `<img src="${avatarSrc}" class="h-full w-full object-cover rounded-xl">`;
        } else {
            activeAvatar.innerText = name.substring(0, 2).toUpperCase();
            activeAvatar.style.background = 'linear-gradient(135deg, #191C47 0%, #FB4C4B 100%)';
        }

        const activeBtn = document.getElementById(`convo-btn-${convoId}`);
        if (activeBtn) {
            document.querySelectorAll('.conversation-item').forEach(item => item.classList.remove('active'));
            activeBtn.classList.add('active');
            if (!currentContactKey) {
                currentContactKey = activeBtn.getAttribute('data-user-key');
            }
        }

        updateActiveHeaderStatus();

        axios.get(`/team/chat/conversations/${convoId}/messages`)
            .then(response => {
                renderMessages(response.data.messages);

                const badge = document.getElementById(`convo-badge-${convoId}`);
                if (badge) {
                    badge.classList.add('hidden');
                    badge.innerText = '0';
                }

                if (typeof updateSidebarBadges === 'function') updateSidebarBadges();
                listenToConversation(convoId);
            })
            .catch(err => {
                console.error('Error loading messages:', err);
            });
    }

    function listenToConversation(convoId) {
        if (typeof window.Echo === 'undefined' || !window.Echo) {
            return;
        }

        if (currentChannel) {
            try {
                window.Echo.leave(`conversation.${currentChannel}`);
            } catch(e) {}
        }
        
        currentChannel = convoId;

        try {
            window.Echo.private(`conversation.${convoId}`)
                .listen('.Modules\\ProjectManagement\\Events\\MessageSent', (e) => {
                    appendReceivedMessage(e);
                });
        } catch(e) {
            console.error('Echo channel listener error:', e);
        }
    }

    function sendChatMessage() {
        const input = document.getElementById('message-input');
        const content = input.value.trim();
        if (!content || !currentConversationId) return;

        input.value = '';

        axios.post(`/team/chat/conversations/${currentConversationId}/messages`, {
            content: content
        }).then(response => {
            appendSentMessage(response.data.message);
            
            const lastMsgEl = document.getElementById(`convo-last-msg-${currentConversationId}`);
            if (lastMsgEl) {
                lastMsgEl.innerText = content;
            }
        }).catch(err => {
            console.error('Error sending message:', err);
        });
    }

    function renderConversationsList(conversations) {
        const list = document.getElementById('active-conversations-list');
        const noMsg = document.getElementById('no-convos-msg');
        
        if (conversations.length > 0 && noMsg) {
            noMsg.classList.add('hidden');
        }

        let html = '';
        conversations.forEach(convo => {
            let avatarHtml = '';
            if (convo.avatar) {
                avatarHtml = `<img src="${convo.avatar}" class="h-10 w-10 rounded-xl object-cover ring-1 ring-gray-200" alt="">`;
            } else {
                avatarHtml = `<div class="h-10 w-10 rounded-xl flex items-center justify-center text-white font-bold text-xs ring-1 ring-gray-200" style="background: linear-gradient(135deg, #191C47 0%, #FB4C4B 100%);">${convo.name.substring(0, 2).toUpperCase()}</div>`;
            }

            const activeClass = currentConversationId === convo.id ? 'active' : '';
            const badgeClass = convo.unread_count > 0 ? '' : 'hidden';

            html += `
                <button onclick="loadConversation(${convo.id}, this)" 
                        class="w-full text-left p-3 rounded-xl flex items-center gap-3 conversation-item group relative ${activeClass}"
                        id="convo-btn-${convo.id}"
                        data-user-key="${convo.other_user_key || ''}">
                    <div class="relative flex-shrink-0">
                        ${avatarHtml}
                        <span class="status-dot status-dot-offline absolute -bottom-0.5 -right-0.5 h-2.5 w-2.5 border-2 border-white rounded-full transition-colors" data-status-key="${convo.other_user_key || ''}"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-semibold text-gray-900 truncate">${convo.name}</h4>
                            <span class="text-[10px] text-gray-400 font-medium">${convo.last_message_time}</span>
                        </div>
                        <p class="text-[11px] text-gray-500 truncate mt-0.5" id="convo-last-msg-${convo.id}">${convo.last_message}</p>
                    </div>
                    <span class="h-5 min-w-[20px] px-1.5 bg-[#FB4C4B] text-white rounded-full text-[10px] font-bold flex items-center justify-center shadow-xs ${badgeClass}" 
                          id="convo-badge-${convo.id}">${convo.unread_count}</span>
                </button>
            `;
        });
        list.innerHTML = html;
        syncAllPresenceDots();
    }

    function renderMessages(messages) {
        const list = document.getElementById('messages-list');
        
        if (messages.length === 0) {
            list.innerHTML = `
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <div class="h-10 w-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400 mb-2">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025 10.338 10.338 0 01-1.99-4.774 10.314 10.314 0 011.99-4.774" />
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-gray-700">No messages yet</p>
                    <p class="text-[11px] text-gray-400 mt-0.5">Send a message below to start chatting!</p>
                </div>
            `;
            return;
        }

        list.innerHTML = '';
        messages.forEach(msg => {
            appendMessageHTML(msg);
        });
        
        scrollToBottom();
    }

    function appendMessageHTML(msg) {
        const list = document.getElementById('messages-list');
        const isMe = msg.is_me;
        
        let avatarHtml = '';
        if (msg.sender_avatar) {
            avatarHtml = `<img src="${msg.sender_avatar}" class="h-8 w-8 rounded-xl object-cover ring-1 ring-gray-200">`;
        } else {
            avatarHtml = `<div class="h-8 w-8 rounded-xl flex items-center justify-center text-white font-bold text-[10px] ring-1 ring-gray-200" style="background: linear-gradient(135deg, #191C47 0%, #FB4C4B 100%);">${msg.sender_name.substring(0,2).toUpperCase()}</div>`;
        }

        const alignClass = isMe ? 'flex-row-reverse' : '';
        const bubbleClass = isMe ? 'message-bubble-me text-white rounded-2xl rounded-tr-xs' : 'message-bubble-other text-slate-800 rounded-2xl rounded-tl-xs';

        const html = `
            <div class="flex items-end gap-2.5 ${alignClass} group">
                <div class="flex-shrink-0 mb-1">${avatarHtml}</div>
                <div class="max-w-[78%] md:max-w-[70%]">
                    ${!isMe ? `<span class="text-[10px] font-semibold text-gray-500 ml-1 mb-1 block">${msg.sender_name}</span>` : ''}
                    <div class="px-4 py-2.5 text-xs md:text-sm leading-relaxed ${bubbleClass}">
                        <p class="whitespace-pre-line">${msg.content}</p>
                        <div class="text-[9px] opacity-80 mt-1 flex items-center gap-1 ${isMe ? 'justify-end text-white/90' : 'text-gray-400'}">
                            <span>${msg.formatted_time}</span>
                            ${isMe ? '<svg class="h-3 w-3 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>' : ''}
                        </div>
                    </div>
                </div>
            </div>
        `;
        list.insertAdjacentHTML('beforeend', html);
    }

    function appendSentMessage(msg) {
        const list = document.getElementById('messages-list');
        if (list.querySelector('.text-gray-700')) {
            list.innerHTML = '';
        }
        appendMessageHTML(msg);
        scrollToBottom();
    }

    function appendReceivedMessage(e) {
        if (e.conversation_id === currentConversationId) {
            const list = document.getElementById('messages-list');
            if (list.querySelector('.text-gray-700')) {
                list.innerHTML = '';
            }
            appendMessageHTML({
                id: e.id,
                content: e.content,
                sender_id: e.sender_id,
                sender_type: e.sender_type,
                sender_name: e.sender_name,
                sender_avatar: e.sender_avatar,
                is_me: false,
                formatted_time: e.formatted_time
            });
            scrollToBottom();
            axios.get(`/team/chat/conversations/${currentConversationId}/messages`);
        } else {
            const badge = document.getElementById(`convo-badge-${e.conversation_id}`);
            if (badge) {
                badge.classList.remove('hidden');
                let count = parseInt(badge.innerText) || 0;
                badge.innerText = count + 1;
            }
            axios.get("{{ route('team.chat.conversations') }}").then(res => {
                renderConversationsList(res.data.conversations);
            });
        }
    }

    function scrollToBottom() {
        const list = document.getElementById('messages-list');
        list.scrollTop = list.scrollHeight;
    }
</script>
@endpush
