<style>
    .chat-message-content ul { list-style-type: disc; margin-left: 1.5rem; margin-top: 0.5rem; margin-bottom: 0.5rem; }
    .chat-message-content ol { list-style-type: decimal; margin-left: 1.5rem; margin-top: 0.5rem; margin-bottom: 0.5rem; }
    .chat-message-content li { margin-bottom: 0.25rem; }
    .chat-message-content p { margin-bottom: 0.5rem; }
    .chat-message-content p:last-child { margin-bottom: 0; }
    .chat-message-content strong { font-weight: 600; }
    .chat-message-content a { text-decoration: underline; }
    .chat-message-content h1, .chat-message-content h2, .chat-message-content h3 { font-weight: 700; margin-top: 0.5rem; margin-bottom: 0.25rem; }
</style>
<script>
    function chatWidget() {
        return {
            isOpen: false,
            messages: [],
            userInput: '',
            isTyping: false,
            sessionId: localStorage.getItem('chat_session_id'),

            initChat() {
                if (this.sessionId) {
                    this.fetchHistory();
                }
            },

            toggleChat() {
                this.isOpen = !this.isOpen;
                if (this.isOpen && !this.sessionId && this.messages.length === 0) {
                    this.startSession();
                }
                this.$nextTick(() => this.scrollToBottom());
            },

            async startSession() {
                try {
                    const response = await fetch('{{ route("ai.chat.start") }}', { 
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    const data = await response.json();
                    if (data.status === 'success') {
                        this.sessionId = data.session_id;
                        localStorage.setItem('chat_session_id', this.sessionId);
                        // Add welcome message if new session
                        if (this.messages.length === 0) {
                            this.messages.push({
                                role: 'assistant',
                                message: 'Hello! How can I help you today?'
                            });
                        }
                    }
                } catch (error) {
                    console.error('Failed to start chat session:', error);
                }
            },

            async fetchHistory() {
                try {
                    const response = await fetch('{{ route("ai.chat.start") }}', { // Re-using start to get history/resume
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ session_id: this.sessionId })
                    });
                    const data = await response.json();
                    if (data.status === 'success' && data.history) {
                        this.messages = data.history;
                        this.$nextTick(() => this.scrollToBottom());
                    } else if (!data.session_id) {
                        // Session invalid, clear it
                        localStorage.removeItem('chat_session_id');
                        this.sessionId = null;
                        this.messages = [];
                    }
                } catch (error) {
                    console.error('Failed to fetch history:', error);
                }
            },

            async sendMessage() {
                if (!this.userInput.trim()) return;

                if (!this.sessionId) {
                    await this.startSession();
                    if (!this.sessionId) {
                        this.messages.push({ role: 'assistant', message: 'Unable to start chat session. Please refresh the page and try again.' });
                        this.$nextTick(() => this.scrollToBottom());
                        return;
                    }
                }

                const userMsg = this.userInput;
                this.messages.push({ role: 'user', message: userMsg });
                this.userInput = '';
                this.isTyping = true;
                this.$nextTick(() => this.scrollToBottom());

                try {
                    const response = await fetch('{{ route("ai.chat.send") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            session_id: this.sessionId,
                            message: userMsg
                        })
                    });
                    const data = await response.json();
                    
                    this.isTyping = false;
                    
                    if (data.status === 'success') {
                        this.messages.push({ role: 'assistant', message: data.message });
                    } else {
                        this.messages.push({ role: 'assistant', message: 'Something went wrong. Please try again.' });
                    }
                    this.$nextTick(() => this.scrollToBottom());

                } catch (error) {
                    console.error('Message send failed:', error);
                    this.isTyping = false;
                    this.messages.push({ role: 'assistant', message: 'Error connecting to server.' });
                }
            },

            scrollToBottom() {
                const container = this.$refs.messagesContainer;
                container.scrollTop = container.scrollHeight;
            },

            renderMessage(content) {
                if (typeof window.marked !== 'undefined') {
                    return window.marked.parse(content);
                }
                return content;
            }
        }
    }
</script>

<div x-data="chatWidget()" x-init="initChat()" class="fixed bottom-6 right-6 z-50 flex flex-col items-end space-y-4 print:hidden" style="display: block;">
    <!-- Chat Window -->
    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-95"
         class="w-full max-w-sm sm:w-96 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden flex flex-col"
         style="height: 500px; max-height: 80vh; display: none;">
        
        <!-- Header -->
        <div class="bg-brand-primary p-4 flex items-center justify-between shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                </div>
                <div>
                    <h3 class="text-white font-bold text-sm">Rick Development Support</h3>
                    <p class="text-white/80 text-xs">AI Assistant • Online</p>
                </div>
            </div>
            <button @click="isOpen = false" class="text-white/80 hover:text-white focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Messages Area -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50 scroll-smooth" x-ref="messagesContainer">
            <template x-for="(msg, index) in messages" :key="index">
                <div class="flex flex-col" :class="msg.role === 'user' ? 'items-end' : 'items-start'">
                    <div class="max-w-[85%] rounded-2xl px-4 py-2.5 text-sm shadow-sm"
                         :class="msg.role === 'user' ? 'bg-brand-primary text-white rounded-br-none' : 'bg-white text-gray-800 border border-gray-100 rounded-bl-none'">
                        <div x-html="renderMessage(msg.message)" class="chat-message-content leading-relaxed"></div>
                    </div>
                    <span class="text-[10px] text-gray-400 mt-1 px-1" x-text="msg.role === 'user' ? 'You' : 'Assistant'"></span>
                </div>
            </template>
            
            <!-- Typing Indicator -->
            <div x-show="isTyping" class="flex flex-col items-start">
                <div class="bg-white border border-gray-100 rounded-2xl rounded-bl-none px-4 py-3 shadow-sm">
                    <div class="flex space-x-1">
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce delay-75"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce delay-150"></div>
                    </div>
                </div>
                <span class="text-[10px] text-gray-400 mt-1 px-1">Assistant is typing...</span>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-4 bg-white border-t border-gray-100 shrink-0">
            <form @submit.prevent="sendMessage" class="flex items-center space-x-2">
                <input type="text" x-model="userInput" 
                       :disabled="isTyping"
                       placeholder="Type your message..." 
                       class="flex-1 border-gray-300 rounded-full focus:ring-brand-primary focus:border-brand-primary text-sm py-2 px-4 shadow-sm disabled:bg-gray-50 disabled:text-gray-400 text-gray-900 caret-gray-900">
                <button type="submit" 
                        :disabled="!userInput.trim() || isTyping"
                        class="bg-brand-primary text-white p-2 rounded-full hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary disabled:opacity-50 disabled:cursor-not-allowed shadow-sm">
                    <svg class="w-5 h-5 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Toggle Button -->
    <button @click="toggleChat" 
            class="group flex items-center justify-center w-14 h-14 bg-brand-primary rounded-full shadow-lg hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary transition-all duration-300 transform hover:scale-105">
        <!-- Icon when closed -->
        <svg x-show="!isOpen" class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
        </svg>
        <!-- Icon when open -->
        <svg x-show="isOpen" class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>
</div>
