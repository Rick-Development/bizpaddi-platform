@extends('admin::layouts.app')

@section('title', 'Admin Chat')

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
                <span>Admin Chat Workspace</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#191C47] text-white">
                    Administrator
                </span>
            </h1>
            <p class="text-xs text-gray-500 mt-1">Real-time direct messaging with team members & project managers across all departments.</p>
        </div>
    </div>

    {{-- Main Container Card --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden chat-card-height flex flex-col md:flex-row">
        
        {{-- ===== LEFT PANEL: CONVERSATIONS & CONTACTS ===== --}}
        <div id="chat-sidebar" class="w-full md:w-80 lg:w-96 border-r border-gray-200 flex flex-col h-full bg-slate-50/60 flex-shrink-0">
            
            {{-- Panel Header & Search --}}
            <div class="p-4 border-b border-gray-200 bg-white">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="h-2.5 w-2.5 rounded-full bg-[#FB4C4B]"></div>
                        <h2 class="text-sm font-bold text-gray-900 tracking-wide uppercase">Conversations</h2>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <button onclick="openCreateGroupModal()" class="h-7 w-7 rounded-lg bg-gray-100 text-gray-500 hover:bg-[#FB4C4B] hover:text-white transition-all flex items-center justify-center shadow-xs" title="Create Group Chat">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                            </svg>
                        </button>
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-gray-100 text-gray-600">
                            {{ count($conversations) }} Active
                        </span>
                    </div>
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
                                        <span class="text-[10px] text-gray-400 font-medium convo-last-message-time">
                                            {{ $convo['last_message_time'] ? \Carbon\Carbon::parse($convo['last_message_time'])->diffForHumans() : '' }}
                                        </span>
                                    </div>
                                    
                                    <div class="flex items-center justify-between">
                                        <p class="text-xs text-gray-500 truncate max-w-[180px] convo-last-message-text">
                                            {{ $convo['last_message'] ?? 'No messages yet' }}
                                        </p>
                                        <div class="convo-unread-badge-container">
                                            @if(($convo['unread_count'] ?? 0) > 0)
                                                <span class="h-5 min-w-[20px] px-1.5 rounded-full bg-[#FB4C4B] text-white text-[10px] font-bold flex items-center justify-center shadow-xs convo-unread-badge">
                                                    {{ $convo['unread_count'] }}
                                                </span>
                                            @endif
                                        </div>
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
        <div id="chat-canvas" class="hidden md:flex flex-1 flex-col h-full bg-white relative">
            
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
                        <button onclick="goBackToSidebar()" class="md:hidden mr-1 p-1 text-gray-500 hover:text-gray-900 focus:outline-none" title="Back to chats">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
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
                    
                    <div id="group-actions-container" class="hidden">
                        <button onclick="openManageMembersModal()" class="h-9 w-9 rounded-xl border border-gray-200 text-gray-500 hover:bg-[#FB4C4B] hover:text-white hover:border-[#FB4C4B] transition-all flex items-center justify-center shadow-xs" title="Manage Group Members">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Messages Canvas --}}
                <div class="flex-1 overflow-y-auto chat-scroll p-6 space-y-4 bg-slate-50/50" id="messages-container">
                    {{-- Dynamically populated --}}
                </div>

                {{-- Input Composer --}}
                <div class="p-4 border-t border-gray-200 bg-white flex flex-col gap-2">
                    
                    {{-- Upload Preview Tray --}}
                    <div id="upload-preview-container" class="hidden flex flex-wrap gap-2 p-2 bg-slate-55 rounded-2xl border border-gray-100">
                    </div>

                    <form onsubmit="sendMessage(event)" class="flex items-center gap-3" enctype="multipart/form-data">
                        <button type="button" onclick="document.getElementById('attachment-input').click()" 
                                class="h-11 w-11 flex items-center justify-center rounded-2xl bg-gray-100 text-gray-500 hover:bg-[#FB4C4B] hover:text-white transition-all shadow-xs flex-shrink-0" 
                                title="Attach files/images/videos">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.75m0-12.75a6 6 0 11-8.485 8.485L5.636 10.25a3.75 3.75 0 115.303 5.304L6.182 20.3a1.5 1.5 0 11-2.122-2.12L8.82 13.4" />
                            </svg>
                        </button>
                        <input type="file" id="attachment-input" class="hidden" multiple onchange="handleFileSelect(event)">

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

    <!-- Create Group Chat Modal -->
    <div id="create-group-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 w-full max-w-md overflow-hidden transform transition-all flex flex-col max-h-[90vh]">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900 font-display">Create New Group Chat</h3>
                <button onclick="closeCreateGroupModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form onsubmit="submitCreateGroup(event)" class="flex flex-col flex-1 overflow-hidden">
                <div class="p-6 space-y-4 flex-1 overflow-y-auto chat-scroll">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Group Name</label>
                        <input type="text" id="group-name" required placeholder="e.g. Design Team Sync" 
                               class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#FB4C4B] transition-all">
                    </div>
                    
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Link to Project (Optional)</label>
                        <select id="group-project" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#FB4C4B] transition-all font-medium">
                            <option value="">-- No Project Link --</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Select Members</label>
                        <input type="text" placeholder="Search members..." onkeyup="filterGroupModalMembers(this.value)"
                               class="w-full px-3.5 py-2 mb-3 bg-gray-50 border border-gray-155 rounded-lg text-[11px] focus:outline-none focus:ring-1 focus:ring-[#FB4C4B]">
                        
                        <div class="space-y-2 max-h-48 overflow-y-auto chat-scroll pr-1" id="group-members-checklist">
                            @foreach($allContacts as $c)
                                <label class="flex items-center justify-between p-2 hover:bg-slate-50 rounded-xl cursor-pointer group-modal-member-item transition-all" data-name="{{ strtolower($c['name']) }}">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-full flex items-center justify-center text-white text-[10px] font-bold shadow-xs" style="background: linear-gradient(135deg, #191C47 0%, #FB4C4B 100%);">
                                            {{ strtoupper(substr($c['name'], 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="text-xs font-semibold text-gray-800 block leading-tight">{{ $c['name'] }}</span>
                                            <span class="text-[9px] text-gray-400 font-medium">{{ $c['type_label'] }}</span>
                                        </div>
                                    </div>
                                    <input type="checkbox" name="group_participants[]" value="{{ $c['key'] }}"
                                           class="rounded border-gray-300 text-[#FB4C4B] focus:ring-[#FB4C4B] h-4 w-4">
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-100 bg-slate-50/50 flex items-center justify-end gap-3 flex-shrink-0">
                    <button type="button" onclick="closeCreateGroupModal()" class="px-4 py-2 border border-gray-200 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-100 active:scale-95 transition-all">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-gradient-to-r from-[#191C47] to-[#FB4C4B] text-white text-xs font-bold shadow-md hover:shadow-lg active:scale-95 transition-all">Create Group</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Manage Group Members Modal -->
    <div id="manage-members-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 w-full max-w-md overflow-hidden transform transition-all flex flex-col max-h-[90vh]">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-900" id="manage-group-name">Manage Members</h3>
                    <p class="text-[10px] text-gray-400 mt-0.5" id="manage-group-project">Project Group Chat</p>
                </div>
                <button onclick="closeManageMembersModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="p-6 space-y-5 flex-1 overflow-y-auto chat-scroll flex flex-col">
                <div class="border-b border-gray-100 pb-4">
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Add New Member</label>
                    <div class="flex gap-2">
                        <select id="add-member-select" class="flex-1 px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#FB4C4B] transition-all font-semibold text-gray-800">
                            <option value="">-- Choose Member to Add --</option>
                            @foreach($allContacts as $c)
                                <option value="{{ $c['key'] }}">{{ $c['name'] }} ({{ $c['type_label'] }})</option>
                            @endforeach
                        </select>
                        <button onclick="submitAddMember()" class="px-4 py-2 rounded-xl bg-gradient-to-r from-[#191C47] to-[#FB4C4B] text-white text-xs font-bold shadow-xs hover:shadow-md active:scale-95 transition-all">Add</button>
                    </div>
                </div>
                
                <div class="flex-1 flex flex-col min-h-0">
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Current Members</label>
                    <div class="space-y-2 overflow-y-auto chat-scroll flex-1 pr-1" id="current-members-list">
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-3 border-t border-gray-100 bg-slate-50/50 flex items-center justify-end flex-shrink-0">
                <button type="button" onclick="closeManageMembersModal()" class="px-4 py-2 border border-gray-200 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-100 active:scale-95 transition-all">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    let activeConversationId = null;
    let activeEchoChannel = null;
    window.onlineUsersSet = new Set();
    window.activePartnerKey = null;
    window.activePartnerIsOnline = false;

    // Pagination states
    let oldestMessageId = null;
    let isLoadingMore = false;
    let hasMoreMessages = true;

    const currentUserId = {{ auth('admin')->id() }};
    const currentUserType = "{{ addslashes(get_class(auth('admin')->user())) }}";
    const initialSelectedConvoId = "{{ $selectedConversationId ?? '' }}";

    document.addEventListener("DOMContentLoaded", () => {
        // WebSockets disabled - relying entirely on robust 2s AJAX polling

        // Chat scroll pagination
        const msgsContainer = document.getElementById('messages-container');
        if (msgsContainer) {
            msgsContainer.addEventListener('scroll', function() {
                if (this.scrollTop === 0 && hasMoreMessages && !isLoadingMore && activeConversationId) {
                    loadMoreMessages();
                }
            });
        }

        // Auto load conversation if URL contains ID
        if (initialSelectedConvoId) {
            const btn = document.getElementById(`convo-btn-${initialSelectedConvoId}`);
            loadConversation(initialSelectedConvoId, btn);
        } else {
            showSidebarView();
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
        showChatView();

        // Update URL to /admin/chat/{id} seamlessly
        if (history.pushState) {
            history.pushState(null, null, `/admin/chat/${conversationId}`);
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
        axios.get(`/admin/chat-api/conversations/${conversationId}/messages?limit=10`)
            .then(res => {
                const data = res.data;
                
                // Update partner header
                window.activePartnerKey = data.partner_key;
                window.activePartnerIsOnline = window.onlineUsersSet.has(data.partner_key);

                document.getElementById('active-partner-name').innerText = data.partner_name;
                document.getElementById('active-partner-role').innerText = data.partner_role;

                const isGroup = data.is_group || false;
                const groupActions = document.getElementById('group-actions-container');
                if (isGroup) {
                    groupActions.classList.remove('hidden');
                    window.activeMembersList = data.members || [];
                    document.getElementById('active-partner-status').innerText = `${window.activeMembersList.length} Members`;
                    document.getElementById('active-partner-dot').className = 'absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-white status-dot-online';
                } else {
                    groupActions.classList.add('hidden');
                    window.activeMembersList = [];
                    document.getElementById('active-partner-status').innerText = window.activePartnerIsOnline ? 'Online' : 'Offline';
                    document.getElementById('active-partner-dot').className = `absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-white ${window.activePartnerIsOnline ? 'status-dot-online' : 'status-dot-offline'}`;
                }

                const avatarBox = document.getElementById('active-partner-avatar');
                if (data.partner_avatar) {
                    avatarBox.innerHTML = `<img src="${data.partner_avatar}" class="h-11 w-11 rounded-full object-cover border-2 border-white shadow-xs" alt="">`;
                } else {
                    avatarBox.innerHTML = `<div class="h-11 w-11 rounded-full flex items-center justify-center text-white font-bold text-xs shadow-xs" style="background: linear-gradient(135deg, #191C47 0%, #FB4C4B 100%);">${data.partner_name.substring(0, 2).toUpperCase()}</div>`;
                }

                if (!isGroup) {
                    updatePresenceStatus(data.partner_key, window.activePartnerIsOnline);
                }

                // Render Messages
                renderMessages(data.messages);
                
                // Set pagination variables
                if (data.messages && data.messages.length > 0) {
                    oldestMessageId = data.messages[0].id;
                } else {
                    oldestMessageId = null;
                }
                
                if (data.messages && data.messages.length < 10) {
                    hasMoreMessages = false;
                } else {
                    hasMoreMessages = true;
                }
                isLoadingMore = false;
            })
            .catch(err => {
                console.error('Failed to load conversation messages:', err);
            });
    }

    function loadMoreMessages() {
        isLoadingMore = true;
        const container = document.getElementById('messages-container');
        const previousScrollHeight = container.scrollHeight;

        // Show a small loader at top
        const loaderId = 'loading-more-msgs';
        container.insertAdjacentHTML('afterbegin', `<div id="${loaderId}" class="text-center py-2"><span class="text-xs text-gray-400">Loading...</span></div>`);

        axios.get(`/admin/chat-api/conversations/${activeConversationId}/messages?limit=10&before_id=${oldestMessageId}`)
            .then(res => {
                // Remove loader
                const loader = document.getElementById(loaderId);
                if (loader) loader.remove();

                const newMessages = res.data.messages || [];
                if (newMessages.length > 0) {
                    oldestMessageId = newMessages[0].id;
                    
                    let newHtml = '';
                    newMessages.forEach(msg => {
                        const isMe = (parseInt(msg.sender_id) === currentUserId) && 
                                     (msg.sender_type.includes('Admin') === currentUserType.includes('Admin'));
                        newHtml += buildMessageBubble(msg, isMe);
                    });

                    container.insertAdjacentHTML('afterbegin', newHtml);

                    // Maintain scroll position so it doesn't jump
                    container.scrollTop = container.scrollHeight - previousScrollHeight;
                }

                if (newMessages.length < 10) {
                    hasMoreMessages = false;
                }
                
                isLoadingMore = false;
            })
            .catch(err => {
                console.error('Failed to load more messages:', err);
                const loader = document.getElementById(loaderId);
                if (loader) loader.remove();
                isLoadingMore = false;
            });
    }

    // 2-second background poll to sync new messages, read status (ticks), and active partner online state
    setInterval(() => {
        if (activeConversationId) {
            axios.get(`/admin/chat-api/conversations/${activeConversationId}/messages`)
                .then(res => {
                    const messages = res.data.messages || [];
                    const partnerIsOnline = res.data.partner_is_online || false;
                    const isGroup = res.data.is_group || false;
                    
                    if (isGroup) {
                        window.activeMembersList = res.data.members || [];
                        document.getElementById('active-partner-status').innerText = `${window.activeMembersList.length} Members`;
                    } else {
                        // Update partner online header indicator
                        window.activePartnerIsOnline = partnerIsOnline;
                        if (window.activePartnerKey) {
                            updatePresenceStatus(window.activePartnerKey, partnerIsOnline);
                        }
                    }

                    messages.forEach(msg => {
                        const existingBubble = document.getElementById(`msg-${msg.id}`);
                        if (!existingBubble) {
                            appendRealtimeMessage(msg);
                        } else {
                            // Update read status ticks dynamically if they changed
                            const isMe = (parseInt(msg.sender_id) === currentUserId) && 
                                         (msg.sender_type.includes('Admin') === currentUserType.includes('Admin'));
                            if (isMe) {
                                const tick = existingBubble.querySelector('.tick-status');
                                if (tick) {
                                    const wasRead = tick.getAttribute('data-read') === 'true';
                                    if (msg.is_read !== wasRead) {
                                        tick.outerHTML = getTickHtml(msg.is_read);
                                    }
                                }
                            }
                        }
                    });
                })
                .catch(() => {});
        }
    }, 2000);

    // 4-second background poll to sync sidebar conversations list (unread badges, last message, and online indicators)
    setInterval(() => {
        axios.get('/admin/chat-api/conversations')
            .then(res => {
                const conversations = res.data.conversations || [];
                conversations.forEach(convo => {
                    const convoBtn = document.getElementById(`convo-btn-${convo.id}`);
                    if (convoBtn) {
                        // Update last message time & text
                        const lastMsgEl = convoBtn.querySelector('.convo-last-message-text');
                        if (lastMsgEl) lastMsgEl.innerText = convo.last_message;
                        
                        const timeEl = convoBtn.querySelector('.convo-last-message-time');
                        if (timeEl) timeEl.innerText = convo.last_message_time;

                        // Update unread badge
                        const badgeContainer = convoBtn.querySelector('.convo-unread-badge-container');
                        if (badgeContainer) {
                            let badge = badgeContainer.querySelector('.convo-unread-badge');
                            if (convo.unread_count > 0) {
                                if (!badge) {
                                    badgeContainer.innerHTML = `<span class="h-5 min-w-[20px] px-1.5 rounded-full bg-[#FB4C4B] text-white text-[10px] font-bold flex items-center justify-center shadow-xs convo-unread-badge">${convo.unread_count}</span>`;
                                } else {
                                    badge.innerText = convo.unread_count;
                                }
                            } else {
                                badgeContainer.innerHTML = '';
                            }
                        }

                        // Update presence dot on conversations list
                        if (convo.partner_key) {
                            updatePresenceStatus(convo.partner_key, convo.is_online);
                        }
                    }
                });
            })
            .catch(() => {});
    }, 4000);

    function startDirectChat(userId, userType) {
        axios.post('/admin/chat-api/conversations/start', {
            contact_id: userId,
            contact_type: userType
        })
        .then(res => {
            if (res.data.conversation_id) {
                window.location.href = `/admin/chat/${res.data.conversation_id}`;
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

        // Generate attachments html
        let attachmentsHtml = '';
        if (msg.attachments && msg.attachments.length > 0) {
            attachmentsHtml = `<div class="flex flex-col gap-2 mt-2 w-full">`;
            msg.attachments.forEach(att => {
                if (att.is_image) {
                    attachmentsHtml += `
                        <div class="relative rounded-2xl overflow-hidden border border-gray-100 max-w-[240px] shadow-xs hover:scale-[1.02] transition-transform">
                            <a href="${att.url}" target="_blank">
                                <img src="${att.url}" class="w-full h-auto object-cover max-h-48" alt="${att.name}">
                            </a>
                        </div>`;
                } else if (att.is_video) {
                    attachmentsHtml += `
                        <div class="relative rounded-2xl overflow-hidden border border-gray-100 max-w-[320px] shadow-xs">
                            <video src="${att.url}" controls class="w-full h-auto max-h-48 bg-black"></video>
                        </div>`;
                } else if (att.is_audio) {
                    attachmentsHtml += `
                        <div class="relative rounded-2xl overflow-hidden border border-gray-150 max-w-[280px] p-2 bg-slate-50 shadow-xs flex items-center justify-center">
                            <audio src="${att.url}" controls class="w-full h-8 bg-transparent"></audio>
                        </div>`;
                } else {
                    attachmentsHtml += `
                        <a href="${att.url}" target="_blank" 
                           class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50 border border-gray-200 text-slate-800 hover:bg-slate-100 transition-all max-w-[280px] shadow-xs">
                            <div class="h-9 w-9 rounded-xl bg-gray-200 flex items-center justify-center text-gray-600 flex-shrink-0">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <span class="text-xs font-semibold truncate block">${att.name}</span>
                                <span class="text-[9px] text-gray-400 block">${att.size}</span>
                            </div>
                        </a>`;
                }
            });
            attachmentsHtml += '</div>';
        }

        // Render system messages
        const isSystem = msg.sender_name && (msg.content.includes("created the group chat.") || msg.content.includes("added ") || msg.content.includes("removed "));
        if (isSystem) {
            return `
                <div class="flex justify-center my-2.5 msg-row" id="msg-${msg.id}">
                    <div class="px-4 py-1.5 rounded-full bg-slate-100 border border-gray-200 text-gray-500 text-[10px] font-medium shadow-xs">
                        <strong>${escapeHtml(msg.sender_name)}</strong> ${escapeHtml(msg.content)}
                    </div>
                </div>
            `;
        }

        return `
            <div class="flex ${alignClass} mb-3 msg-row" id="msg-${msg.id}">
                <div class="max-w-[75%] md:max-w-[65%]">
                    <div class="px-4 py-3 rounded-2xl text-xs md:text-sm leading-relaxed ${bubbleClass}">
                        ${escapeHtml(msg.content)}
                        ${attachmentsHtml}
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
                axios.post(`/admin/chat-api/conversations/${activeConversationId}/read`).catch(() => {});
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

    let selectedFiles = [];

    function handleFileSelect(e) {
        const files = Array.from(e.target.files);
        selectedFiles = [...selectedFiles, ...files];
        renderUploadPreview();
    }

    function renderUploadPreview() {
        const container = document.getElementById('upload-preview-container');
        if (selectedFiles.length === 0) {
            container.classList.add('hidden');
            container.innerHTML = '';
            return;
        }

        container.classList.remove('hidden');
        container.innerHTML = '';

        selectedFiles.forEach((file, index) => {
            const isImage = file.type.startsWith('image/');
            let previewHtml = '';

            if (isImage) {
                const url = URL.createObjectURL(file);
                previewHtml = `
                    <div class="relative h-14 w-14 rounded-xl overflow-hidden border border-gray-200 flex-shrink-0 group">
                        <img src="${url}" class="h-full w-full object-cover" alt="preview">
                        <button type="button" onclick="removeSelectedFile(${index})" 
                                class="absolute top-1 right-1 h-5 w-5 rounded-full bg-slate-900/60 hover:bg-slate-900 text-white flex items-center justify-center focus:outline-none">
                            <span class="text-[10px] font-bold">×</span>
                        </button>
                    </div>`;
            } else {
                previewHtml = `
                    <div class="relative h-14 w-32 p-2 bg-white rounded-xl border border-gray-200 flex items-center gap-2 flex-shrink-0 group">
                        <div class="h-8 w-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 flex-shrink-0">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="text-[10px] font-semibold truncate block text-gray-800 leading-tight">${file.name}</span>
                            <span class="text-[8px] text-gray-400 block">${(file.size / 1024 / 1024).toFixed(2)} MB</span>
                        </div>
                        <button type="button" onclick="removeSelectedFile(${index})" 
                                class="absolute top-1 right-1 h-5 w-5 rounded-full bg-slate-900/60 hover:bg-slate-900 text-white flex items-center justify-center focus:outline-none">
                            <span class="text-[10px] font-bold">×</span>
                        </button>
                    </div>`;
            }

            container.insertAdjacentHTML('beforeend', previewHtml);
        });
    }

    function removeSelectedFile(index) {
        selectedFiles.splice(index, 1);
        renderUploadPreview();
    }

    function sendMessage(e) {
        e.preventDefault();
        const input = document.getElementById('message-input');
        const content = input.value.trim();

        if (!content && selectedFiles.length === 0) return;
        if (!activeConversationId) return;

        const formData = new FormData();
        formData.append('content', content);
        
        selectedFiles.forEach((file, index) => {
            formData.append(`attachments[${index}]`, file);
        });

        input.value = '';
        selectedFiles = [];
        renderUploadPreview();

        axios.post(`/admin/chat-api/conversations/${activeConversationId}/messages`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
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

    function showChatView() {
        if (window.innerWidth < 768) {
            document.getElementById('chat-sidebar').classList.add('hidden');
            const canvas = document.getElementById('chat-canvas');
            canvas.classList.remove('hidden');
            canvas.classList.add('flex');
        }
    }

    function showSidebarView() {
        if (window.innerWidth < 768) {
            document.getElementById('chat-sidebar').classList.remove('hidden');
            const canvas = document.getElementById('chat-canvas');
            canvas.classList.add('hidden');
            canvas.classList.remove('flex');
        }
    }

    function goBackToSidebar() {
        activeConversationId = null;
        showSidebarView();
        document.querySelectorAll('.conversation-item').forEach(el => el.classList.remove('active'));
        if (history.pushState) {
            const path = window.location.pathname.includes('/admin/') ? '/admin/chat' : '/team/chat';
            history.pushState(null, null, path);
        }
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.innerText = text;
        return div.innerHTML;
    }

    // Modal state handlers
    function openCreateGroupModal() {
        document.getElementById('create-group-modal').classList.remove('hidden');
    }

    function closeCreateGroupModal() {
        document.getElementById('create-group-modal').classList.add('hidden');
        document.getElementById('group-name').value = '';
        document.getElementById('group-project').value = '';
        document.querySelectorAll('#group-members-checklist input[type="checkbox"]').forEach(c => c.checked = false);
    }

    function filterGroupModalMembers(q) {
        const query = q.toLowerCase();
        document.querySelectorAll('.group-modal-member-item').forEach(el => {
            const name = el.getAttribute('data-name');
            if (name.includes(query)) {
                el.classList.remove('hidden');
            } else {
                el.classList.add('hidden');
            }
        });
    }

    function submitCreateGroup(e) {
        e.preventDefault();
        const name = document.getElementById('group-name').value.trim();
        const projectId = document.getElementById('group-project').value;
        const participantKeys = Array.from(document.querySelectorAll('#group-members-checklist input[type="checkbox"]:checked')).map(c => c.value);

        if (!name) return;

        axios.post('/admin/chat-api/groups', {
            name: name,
            project_id: projectId,
            participant_keys: participantKeys
        })
        .then(res => {
            if (res.data.conversation_id) {
                closeCreateGroupModal();
                window.location.href = `/admin/chat/${res.data.conversation_id}`;
            }
        })
        .catch(err => {
            console.error('Failed to create group:', err);
        });
    }

    window.activeMembersList = [];

    function openManageMembersModal() {
        document.getElementById('manage-members-modal').classList.remove('hidden');
        renderManageMembersList();
    }

    function closeManageMembersModal() {
        document.getElementById('manage-members-modal').classList.add('hidden');
    }

    function renderManageMembersList() {
        const container = document.getElementById('current-members-list');
        container.innerHTML = '';

        window.activeMembersList.forEach(m => {
            const isMe = m.id === currentUserId && m.type_label.includes('Admin') === currentUserType.includes('Admin');
            const deleteBtn = isMe ? '' : `
                <button onclick="removeGroupMember('${m.key}')" class="text-xs text-red-500 hover:text-red-700 font-bold focus:outline-none">
                    Remove
                </button>`;

            const html = `
                <div class="flex items-center justify-between p-2 hover:bg-slate-50 rounded-xl transition-all">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 rounded-full flex items-center justify-center text-white text-[10px] font-bold shadow-xs" style="background: linear-gradient(135deg, #191C47 0%, #FB4C4B 100%);">
                            ${m.name.substring(0, 2).toUpperCase()}
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-gray-800 block">${m.name} ${isMe ? '(You)' : ''}</span>
                            <span class="text-[9px] text-gray-400 font-medium">${m.type_label}</span>
                        </div>
                    </div>
                    ${deleteBtn}
                </div>`;
            
            container.insertAdjacentHTML('beforeend', html);
        });
    }

    function removeGroupMember(key) {
        if (!activeConversationId) return;

        axios.post(`/admin/chat-api/conversations/${activeConversationId}/members/remove`, {
            contact_key: key
        })
        .then(() => {
            window.activeMembersList = window.activeMembersList.filter(m => m.key !== key);
            renderManageMembersList();
            loadConversation(activeConversationId);
        })
        .catch(err => {
            if (err.response && err.response.data && err.response.data.error) {
                alert(err.response.data.error);
            } else {
                console.error('Failed to remove member:', err);
            }
        });
    }

    function submitAddMember() {
        const select = document.getElementById('add-member-select');
        const key = select.value;
        if (!key || !activeConversationId) return;

        axios.post(`/admin/chat-api/conversations/${activeConversationId}/members/add`, {
            contact_key: key
        })
        .then(() => {
            select.value = '';
            loadConversation(activeConversationId);
            closeManageMembersModal();
        })
        .catch(err => {
            console.error('Failed to add member:', err);
        });
    }
</script>
@endpush
