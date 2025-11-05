<!-- Chatbot Button -->
<button id="chatbot-button" class="chat-button" onclick="toggleChat()">
    <div class="pulse-ring"></div>
    <div class="notification-dot">3</div>
    <svg class="chat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
    </svg>
</button>

<!-- Chat Widget -->
<div id="chat-widget" class="chat-widget">
    <!-- Chat Header -->
    <div class="chat-header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold">AI Assistant</h3>
                    <p class="text-sm opacity-90">Online now</p>
                </div>
            </div>
            <button onclick="toggleChat()" class="text-white hover:bg-white hover:bg-opacity-20 p-2 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Chat Messages -->
    <div id="chat-messages" class="chat-messages">
        <div class="message bot">
            <div class="bot-avatar">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
            </div>
            <div>
                <div class="message-bubble">
                    👋 Hello! I'm your AI assistant. How can I help you today?
                </div>
                <div class="quick-replies">
                    <div class="quick-reply" onclick="sendQuickReply('Get started')">Get started</div>
                    <div class="quick-reply" onclick="sendQuickReply('Pricing info')">Pricing info</div>
                    <div class="quick-reply" onclick="sendQuickReply('Contact support')">Contact support</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Input -->
    <div class="chat-input-area">
        <div class="relative">
            <input
                type="text"
                id="chat-input"
                class="chat-input pr-12"
                placeholder="Type your message..."
                onkeypress="handleKeyPress(event)"
            >
            <button id="send-button" class="send-button" onclick="sendMessage()">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </button>
        </div>
    </div>
</div>
