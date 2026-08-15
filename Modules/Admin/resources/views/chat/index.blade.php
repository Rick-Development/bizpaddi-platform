@extends('admin::layouts.app')

@section('title', 'Admin Chat')

@push('styles')
<style>
    .chat-card-height {
        height: calc(100vh - 7rem);
        min-height: 620px;
    }
    .chat-scroll::-webkit-scrollbar {
        width: 5px;
    }
    .chat-scroll::-webkit-scrollbar-track {
        background: transparent;
    }
    .chat-scroll::-webkit-scrollbar-thumb {
        background-color: rgba(148, 163, 184, 0.2);
        border-radius: 9999px;
    }
    .chat-scroll::-webkit-scrollbar-thumb:hover {
        background-color: rgba(148, 163, 184, 0.4);
    }
    .conversation-item {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .conversation-item:hover {
        background-color: rgba(255, 255, 255, 0.05);
        transform: translateX(2px);
    }
    .conversation-item.active {
        background: linear-gradient(90deg, rgba(251, 76, 75, 0.15) 0%, rgba(25, 28, 71, 0.2) 100%);
        border-left: 4px solid #FB4C4B;
    }
    .message-bubble-me {
        background: linear-gradient(135deg, #191C47 0%, #2b3070 100%);
        box-shadow: 0 4px 14px rgba(25, 28, 71, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .message-bubble-other {
        background: rgba(30, 41, 59, 0.85);
        border: 1px solid rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(12px);
    }
    .glass-panel {
        background: rgba(15, 23, 42, 0.92);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.08);
    }
</style>
@endpush

@section('content')
<div class="px-2 sm:px-4 lg:px-6 max-w-7xl mx-auto">
    <div class="glass-panel rounded-3xl shadow-2xl overflow-hidden chat-card-height flex flex-col md:flex-row border border-slate-800">
        
        {{-- ===== LEFT PANEL: CONVERSATIONS & CONTACTS ===== --}}
        <div class="w-full md:w-84 lg:w-96 border-b md:border-b-0 md:border-r border-slate-800 flex flex-col h-full bg-slate-950/70 flex-shrink-0">
            
            {{-- Panel Header --}}
            <div class="p-4 border-b border-slate-800/80 bg-slate-900/50">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="h-3 w-3 rounded-full bg-[#191C47] border border-blue-400 shadow-sm"></div>
                        <h2 class="text-base font-bold text-white tracking-wide">Admin Workspace Chat</h2>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20">Admin Access</span>
                </div>

                {{-- Search Box --}}
                <div class="relative">
                    <input type="text" id="contact-search" placeholder="Search contacts..." 
                           class="w-full pl-9 pr-4 py-2 bg-slate-900/90 border border-slate-700/60 rounded-xl text-slate-200 focus:outline-none focus:ring-2 focus:ring-[#191C47] focus:border-blue-500 text-xs transition-all placeholder-slate-500"
                           onkeyup="filterContacts()">
                    <svg class="h-4 w-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            {{-- Conversations List Container --}}
            <div class="flex-1 overflow-y-auto chat-scroll p-3 space-y-4" id="conversations-list-container">
                
                {{-- Active Conversations Section --}}
                <div>
                    <div class="flex items-center justify-between px-2 mb-2">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Active Discussions</span>
                        <span class="text-[10px] text-slate-500 font-medium">{{ count($conversations) }} active</span>
                    </div>

                    <div id="active-conversations-list" class="space-y-1">
                        @forelse($conversations as $convo)
                            <button onclick="loadConversation({{ $convo['id'] }}, this)" 
                                    class="w-full text-left p-3 rounded-2xl flex items-center gap-3 conversation-item group relative"
                                    id="convo-btn-{{ $convo['id'] }}">
                                <div class="relative flex-shrink-0">
                                    @if($convo['avatar'])
                                        <img src="{{ $convo['avatar'] }}" class="h-11 w-11 rounded-xl object-cover ring-2 ring-slate-800" alt="">
                                    @else
                                        <div class="h-11 w-11 rounded-xl flex items-center justify-center text-white font-bold text-xs ring-2 ring-slate-800 shadow-inner" style="background: linear-gradient(135deg, #191C47 0%, #FB4C4B 100%);">
                                            {{ strtoupper(substr($convo['name'], 0, 2)) }}
                                        </div>
                                    @endif
                                    <span class="absolute -bottom-0.5 -right-0.5 h-3 w-3 bg-emerald-500 border-2 border-slate-950 rounded-full"></span>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-xs font-bold text-slate-200 truncate group-hover:text-white transition-colors">{{ $convo['name'] }}</h4>
                                        <span class="text-[10px] text-slate-400 font-medium">{{ $convo['last_message_time'] }}</span>
                                    </div>
                                    <p class="text-[11px] text-slate-400 truncate mt-1" id="convo-last-msg-{{ $convo['id'] }}">{{ $convo['last_message'] }}</p>
                                </div>

                                <span class="h-5 min-w-[20px] px-1.5 bg-[#FB4C4B] text-white rounded-full text-[10px] font-bold flex items-center justify-center shadow-lg shadow-red-500/30 {{ $convo['unread_count'] > 0 ? '' : 'hidden' }}" 
                                      id="convo-badge-{{ $convo['id'] }}">{{ $convo['unread_count'] }}</span>
                            </button>
                        @empty
                            <div class="text-center py-6 px-4 rounded-2xl border border-dashed border-slate-800 bg-slate-900/30" id="no-convos-msg">
                                <p class="text-xs text-slate-400 font-medium">No open conversations</p>
                                <p class="text-[11px] text-slate-500 mt-0.5">Click a team member below to start</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- All Contacts Section --}}
                <div class="pt-2 border-t border-slate-800/80">
                    <div class="flex items-center justify-between px-2 mb-2">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Start New Chat</span>
                        <span class="text-[10px] text-slate-500 font-medium">{{ count($contacts) }} contacts</span>
                    </div>

                    <div id="all-contacts-list" class="space-y-1">
                        @foreach($contacts as $contact)
                            <button onclick="startChat({{ $contact->id }}, '{{ addslashes($contact->morph_class) }}', this)" 
                                    class="w-full text-left p-2.5 rounded-xl flex items-center gap-3 hover:bg-slate-800/60 transition-all contact-row group"
                                    data-name="{{ strtolower($contact->name) }}">
                                <div class="relative flex-shrink-0">
                                    @if(method_exists($contact, 'getFirstMediaUrl') && $contact->getFirstMediaUrl('avatar'))
                                        <img src="{{ $contact->getFirstMediaUrl('avatar') }}" class="h-9 w-9 rounded-xl object-cover ring-1 ring-slate-700" alt="">
                                    @else
                                        <div class="h-9 w-9 rounded-xl flex items-center justify-center text-white font-bold text-xs shadow-inner" style="background: linear-gradient(135deg, #191C47 0%, #3B82F6 100%);">
                                            {{ strtoupper(substr($contact->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <span class="absolute -bottom-0.5 -right-0.5 h-2.5 w-2.5 bg-emerald-500 border border-slate-950 rounded-full"></span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-xs font-semibold text-slate-200 truncate group-hover:text-white transition-colors">{{ $contact->name }}</h4>
                                    <span class="inline-flex items-center px-1.5 py-0.2 rounded-md text-[9px] font-medium bg-slate-800 text-slate-400 border border-slate-700/50 mt-0.5">{{ $contact->type_label }}</span>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== RIGHT PANEL: CHAT WINDOW ===== --}}
        <div class="flex-1 flex flex-col h-full bg-slate-900/40 relative overflow-hidden" id="chat-window-wrapper">
            
            {{-- Default Empty Placeholder --}}
            <div id="chat-placeholder" class="flex-1 flex flex-col items-center justify-center p-8 text-center bg-gradient-to-b from-slate-900/30 to-slate-950/60">
                <div class="relative mb-6">
                    <div class="h-20 w-20 rounded-3xl bg-gradient-to-tr from-[#191C47]/40 to-blue-500/20 flex items-center justify-center text-blue-400 border border-blue-500/20 shadow-2xl">
                        <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-.623.02-1.25.02-1.875 0l-4.5 4.5v-4.5C6.442 16.71 4.5 14.582 4.5 12c0-3.314 3.358-6 7.5-6 1.83 0 3.509.52 4.8 1.401M16.5 4.5h.008v.008H16.5V4.5zm-4.5 0h.008v.008H12V4.5z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-base font-bold text-white tracking-wide">Admin Direct Chat</h3>
                <p class="text-xs text-slate-400 mt-1.5 max-w-sm leading-relaxed">Select a conversation or pick a contact from the list to launch a chat session.</p>
            </div>

            {{-- Main Active Chat View --}}
            <div id="chat-active" style="display:none;" class="flex-1 flex flex-col h-full overflow-hidden">
                
                {{-- Chat Header --}}
                <div class="px-6 py-3.5 border-b border-slate-800/80 bg-slate-950/80 backdrop-blur-md flex items-center justify-between z-10 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <div id="active-user-avatar" class="h-10 w-10 rounded-xl flex items-center justify-center text-white font-bold text-xs ring-2 ring-slate-700 shadow-md"></div>
                            <span class="absolute -bottom-0.5 -right-0.5 h-3 w-3 bg-emerald-500 border-2 border-slate-950 rounded-full"></span>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-white" id="active-user-name">...</h3>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                                <span class="text-[11px] text-slate-400 font-medium" id="active-user-status">Active now</span>
                            </div>
                        </div>
                    </div>

                    {{-- Actions Header --}}
                    <div class="flex items-center gap-2">
                        <button onclick="if(currentConversationId) openChatWindow(currentConversationId, currentContactName, currentContactAvatar)" 
                                class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/60 transition-all" title="Refresh messages">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Messages Display Scroll Container --}}
                <div class="flex-1 p-4 md:p-6 overflow-y-auto chat-scroll space-y-4 bg-slate-950/40" id="messages-list">
                    {{-- Dynamically Populated --}}
                </div>

                {{-- Message Input Bar --}}
                <div class="p-3 md:p-4 border-t border-slate-800/80 bg-slate-950/90 backdrop-blur-xl">
                    <form id="message-form" onsubmit="event.preventDefault(); sendChatMessage();" class="flex items-center gap-2">
                        <div class="flex-1 flex items-center gap-2 bg-slate-900/90 border border-slate-800 rounded-2xl px-4 py-1.5 focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500/50 transition-all shadow-inner">
                            
                            {{-- Input field --}}
                            <input type="text" id="message-input" placeholder="Type your message here..." required
                                   class="flex-1 bg-transparent border-0 text-slate-100 text-xs md:text-sm focus:outline-none placeholder-slate-500 py-2"
                                   autocomplete="off">

                            {{-- Quick Emoji Insertion --}}
                            <button type="button" onclick="insertQuickEmoji('👍')" class="text-slate-400 hover:text-yellow-400 transition-colors text-sm px-1">👍</button>
                            <button type="button" onclick="insertQuickEmoji('🚀')" class="text-slate-400 hover:text-slate-200 transition-colors text-sm px-1">🚀</button>
                        </div>

                        {{-- Send Button --}}
                        <button type="submit" 
                                class="h-11 w-11 rounded-2xl bg-gradient-to-r from-[#191C47] to-[#2b3070] hover:scale-105 active:scale-95 text-white shadow-lg shadow-blue-900/30 transition-all duration-200 flex items-center justify-center flex-shrink-0 cursor-pointer border border-blue-400/20">
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

        axios.post("{{ route('admin.chat.conversations.start') }}", {
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

            axios.get("{{ route('admin.chat.conversations') }}")
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

        openChatWindow(convoId, name, avatar);
    }

    function openChatWindow(convoId, name, avatarSrc) {
        currentConversationId = convoId;
        currentContactName = name;
        currentContactAvatar = avatarSrc;

        document.getElementById('chat-placeholder').style.display = 'none';
        const chatActive = document.getElementById('chat-active');
        chatActive.style.display = 'flex';

        const activeAvatar = document.getElementById('active-user-avatar');
        document.getElementById('active-user-name').innerText = name;
        
        if (avatarSrc) {
            activeAvatar.innerHTML = `<img src="${avatarSrc}" class="h-full w-full object-cover rounded-xl">`;
        } else {
            activeAvatar.innerText = name.substring(0, 2).toUpperCase();
            activeAvatar.style.background = 'linear-gradient(135deg, #191C47 0%, #3B82F6 100%)';
        }

        const activeBtn = document.getElementById(`convo-btn-${convoId}`);
        if (activeBtn) {
            document.querySelectorAll('.conversation-item').forEach(item => item.classList.remove('active'));
            activeBtn.classList.add('active');
        }

        axios.get(`/admin/chat/conversations/${convoId}/messages`)
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

        axios.post(`/admin/chat/conversations/${currentConversationId}/messages`, {
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
                avatarHtml = `<img src="${convo.avatar}" class="h-11 w-11 rounded-xl object-cover ring-2 ring-slate-800" alt="">`;
            } else {
                avatarHtml = `<div class="h-11 w-11 rounded-xl flex items-center justify-center text-white font-bold text-xs ring-2 ring-slate-800 shadow-inner" style="background: linear-gradient(135deg, #191C47 0%, #3B82F6 100%);">${convo.name.substring(0, 2).toUpperCase()}</div>`;
            }

            const activeClass = currentConversationId === convo.id ? 'active' : '';
            const badgeClass = convo.unread_count > 0 ? '' : 'hidden';

            html += `
                <button onclick="loadConversation(${convo.id}, this)" 
                        class="w-full text-left p-3 rounded-2xl flex items-center gap-3 conversation-item group relative ${activeClass}"
                        id="convo-btn-${convo.id}">
                    <div class="relative flex-shrink-0">
                        ${avatarHtml}
                        <span class="absolute -bottom-0.5 -right-0.5 h-3 w-3 bg-emerald-500 border-2 border-slate-950 rounded-full"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold text-slate-200 truncate group-hover:text-white transition-colors">${convo.name}</h4>
                            <span class="text-[10px] text-slate-400 font-medium">${convo.last_message_time}</span>
                        </div>
                        <p class="text-[11px] text-slate-400 truncate mt-1" id="convo-last-msg-${convo.id}">${convo.last_message}</p>
                    </div>
                    <span class="h-5 min-w-[20px] px-1.5 bg-[#FB4C4B] text-white rounded-full text-[10px] font-bold flex items-center justify-center shadow-lg shadow-red-500/30 ${badgeClass}" 
                          id="convo-badge-${convo.id}">${convo.unread_count}</span>
                </button>
            `;
        });
        list.innerHTML = html;
    }

    function renderMessages(messages) {
        const list = document.getElementById('messages-list');
        
        if (messages.length === 0) {
            list.innerHTML = `
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <div class="h-12 w-12 rounded-2xl bg-slate-800/80 border border-slate-700/50 flex items-center justify-center text-slate-400 mb-3">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025 10.338 10.338 0 01-1.99-4.774 10.314 10.314 0 011.99-4.774" />
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-slate-300">No messages yet</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Send a message below to kickstart the conversation!</p>
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
            avatarHtml = `<img src="${msg.sender_avatar}" class="h-8 w-8 rounded-xl object-cover ring-1 ring-slate-700">`;
        } else {
            avatarHtml = `<div class="h-8 w-8 rounded-xl flex items-center justify-center text-white font-bold text-[10px] ring-1 ring-slate-700" style="background: linear-gradient(135deg, #191C47 0%, #3B82F6 100%);">${msg.sender_name.substring(0,2).toUpperCase()}</div>`;
        }

        const alignClass = isMe ? 'flex-row-reverse' : '';
        const bubbleClass = isMe ? 'message-bubble-me text-white rounded-2xl rounded-tr-xs' : 'message-bubble-other text-slate-100 rounded-2xl rounded-tl-xs';

        const html = `
            <div class="flex items-end gap-2.5 ${alignClass} group">
                <div class="flex-shrink-0 mb-1">${avatarHtml}</div>
                <div class="max-w-[78%] md:max-w-[70%]">
                    ${!isMe ? `<span class="text-[10px] font-semibold text-slate-400 ml-1 mb-1 block">${msg.sender_name}</span>` : ''}
                    <div class="px-4 py-2.5 text-xs md:text-sm leading-relaxed ${bubbleClass}">
                        <p class="whitespace-pre-line">${msg.content}</p>
                        <div class="text-[9px] opacity-75 mt-1 flex items-center gap-1 ${isMe ? 'justify-end' : ''}">
                            <span>${msg.formatted_time}</span>
                            ${isMe ? '<svg class="h-3 w-3 inline text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>' : ''}
                        </div>
                    </div>
                </div>
            </div>
        `;
        list.insertAdjacentHTML('beforeend', html);
    }

    function appendSentMessage(msg) {
        const list = document.getElementById('messages-list');
        if (list.querySelector('.text-slate-300')) {
            list.innerHTML = '';
        }
        appendMessageHTML(msg);
        scrollToBottom();
    }

    function appendReceivedMessage(e) {
        if (e.conversation_id === currentConversationId) {
            const list = document.getElementById('messages-list');
            if (list.querySelector('.text-slate-300')) {
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
            axios.get(`/admin/chat/conversations/${currentConversationId}/messages`);
        } else {
            const badge = document.getElementById(`convo-badge-${e.conversation_id}`);
            if (badge) {
                badge.classList.remove('hidden');
                let count = parseInt(badge.innerText) || 0;
                badge.innerText = count + 1;
            }
            axios.get("{{ route('admin.chat.conversations') }}").then(res => {
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
