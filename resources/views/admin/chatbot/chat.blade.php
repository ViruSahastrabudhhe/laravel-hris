<style>
.chat-fab {
    position: fixed;
    bottom: 24px;
    right: 24px;
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    transition: all 0.3s ease;
    z-index: 999;
}
.chat-fab:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
}
.chat-fab-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    background: #ef4444;
    color: white;
    font-size: 10px;
    font-weight: 600;
    padding: 2px 6px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}
.chatbot-window {
    position: fixed;
    bottom: 90px;
    right: 24px;
    width: 380px;
    height: 600px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.12);
    display: flex;
    flex-direction: column;
    z-index: 1000;
    overflow: hidden;
}
.chatbot-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.chatbot-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
}
.chatbot-avatar {
    width: 36px;
    height: 36px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.chatbot-name {
    font-weight: 600;
    font-size: 14px;
    margin: 0;
}
.chatbot-status {
    font-size: 11px;
    opacity: 0.9;
    margin: 2px 0 0 0;
}
.chatbot-close, .chatbot-clear {
    background: rgba(255,255,255,0.2);
    border: none;
    color: white;
    width: 28px;
    height: 28px;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
}
.chatbot-close:hover, .chatbot-clear:hover {
    background: rgba(255,255,255,0.3);
}
.chatbot-quick-actions {
    padding: 12px;
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
}
.chatbot-quick-btn {
    background: white;
    border: 1px solid #e5e7eb;
    padding: 6px 12px;
    border-radius: 16px;
    font-size: 12px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s;
}
.chatbot-quick-btn:hover {
    background: #667eea;
    color: white;
    border-color: #667eea;
}
.chatbot-messages {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    background: #f9fafb;
}
.chat-msg {
    display: flex;
    gap: 10px;
    margin-bottom: 16px;
}
.chat-msg.user {
    flex-direction: row-reverse;
}
.chat-msg-avatar {
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
}
.chat-msg-bubble {
    background: white;
    padding: 10px 14px;
    border-radius: 12px;
    max-width: 70%;
    font-size: 14px;
    line-height: 1.5;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    position: relative;
}
.chat-msg.user .chat-msg-bubble {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}
.chat-ts {
    display: block;
    font-size: 10px;
    opacity: 0.6;
    margin-top: 4px;
}
.chat-typing-indicator {
    display: flex;
    gap: 4px;
    padding: 10px 14px;
}
.chat-typing-indicator span {
    width: 8px;
    height: 8px;
    background: #667eea;
    border-radius: 50%;
    animation: typing 1.4s infinite;
}
.chat-typing-indicator span:nth-child(2) {
    animation-delay: 0.2s;
}
.chat-typing-indicator span:nth-child(3) {
    animation-delay: 0.4s;
}
@keyframes typing {
    0%, 60%, 100% { transform: translateY(0); }
    30% { transform: translateY(-10px); }
}
.chatbot-input-row {
    padding: 12px;
    background: white;
    border-top: 1px solid #e5e7eb;
    display: flex;
    gap: 8px;
}
.chatbot-input-row input {
    flex: 1;
    border: 1px solid #e5e7eb;
    border-radius: 20px;
    padding: 10px 16px;
    font-size: 14px;
    outline: none;
}
.chatbot-input-row input:focus {
    border-color: #667eea;
}
.chatbot-send {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 50%;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s;
}
.chatbot-send:hover {
    transform: scale(1.1);
}
</style>

{{-- AI Chatbot FAB --}}
<button class="chat-fab" id="chat-fab" onclick="toggleAdminChat()" title="AI Assistant">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
    </svg>
    <span class="chat-fab-badge" id="chat-fab-badge">AI</span>
</button>

{{-- Chatbot Window --}}
<div class="chatbot-window" id="chatbot-window" style="display:none">
    <div class="chatbot-header">
        <div class="chatbot-header-left">
            <div class="chatbot-avatar">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
            </div>
            <div>
                <p class="chatbot-name">PRIME HRIS Assistant</p>
                <p class="chatbot-status">● Online</p>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:6px">
            <button class="chatbot-clear" onclick="clearAdminChat()" title="Clear conversation">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/>
                </svg>
            </button>
            <button class="chatbot-close" onclick="toggleAdminChat()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
    </div>

    <div class="chatbot-quick-actions">
        <button class="chatbot-quick-btn" onclick="quickAskAdmin('How do I file a leave?')">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/></svg>
            Leave
        </button>
        <button class="chatbot-quick-btn" onclick="quickAskAdmin('Check my payroll')">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Payroll
        </button>
        <button class="chatbot-quick-btn" onclick="quickAskAdmin('View my DTR')">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            DTR
        </button>
        <button class="chatbot-quick-btn" onclick="quickAskAdmin('Contact HR')">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            Contact
        </button>
    </div>

    <div class="chatbot-messages" id="chatbot-messages">
        <div class="chat-msg bot">
            <div class="chat-msg-avatar">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
            </div>
            <div class="chat-msg-bubble">Hello! I'm your PRIME HRIS assistant. I can help you with leave applications, payroll inquiries, DTR records, and HR procedures. How can I assist you today?<span class="chat-ts"></span></div>
        </div>
    </div>

    <div class="chatbot-input-row">
        <input type="text" id="chat-input" placeholder="Ask about HRIS features..." onkeydown="if(event.key==='Enter') sendAdminMessage()">
        <button class="chatbot-send" onclick="sendAdminMessage()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/>
            </svg>
        </button>
    </div>
</div>

<script>
    const STORAGE_KEY = 'admin_chat_history';

    function loadChatHistory() {
        const history = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
        const container = document.getElementById('chatbot-messages');
        container.innerHTML = '';

        if (history.length === 0) {
            addAdminMessage("Hello! I'm your PRIME HRIS assistant. I can help you with leave applications, payroll inquiries, DTR records, and HR procedures. How can I assist you today?", false);
        } else {
            history.forEach(msg => {
                addAdminMessage(msg.text, msg.isUser, false);
            });
        }
    }

    function saveChatHistory() {
        const messages = [];
        document.querySelectorAll('.chat-msg').forEach(msg => {
            const isUser = msg.classList.contains('user');
            const bubble = msg.querySelector('.chat-msg-bubble');
            if (bubble) {
                // Clone and remove timestamp to get clean text
                const clone = bubble.cloneNode(true);
                const ts = clone.querySelector('.chat-ts');
                if (ts) ts.remove();
                const text = clone.innerHTML.trim();
                messages.push({ text, isUser });
            }
        });
        localStorage.setItem(STORAGE_KEY, JSON.stringify(messages));
    }

    function getAdminTimestamp() {
        return new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
    }

    function toggleAdminChat() {
        const win = document.getElementById('chatbot-window');
        const badge = document.getElementById('chat-fab-badge');
        const isOpen = win.style.display === 'flex';
        win.style.display = isOpen ? 'none' : 'flex';
        badge.style.display = isOpen ? 'block' : 'none';

        // Scroll to bottom when opening
        if (!isOpen) {
            const container = document.getElementById('chatbot-messages');
            setTimeout(() => {
                container.scrollTop = container.scrollHeight;
            }, 50);
        }
    }

    function addAdminMessage(text, isUser, save = true) {
        const container = document.getElementById('chatbot-messages');
        const wrapper = document.createElement('div');
        wrapper.className = 'chat-msg ' + (isUser ? 'user' : 'bot');

        if (!isUser) {
            const avatar = document.createElement('div');
            avatar.className = 'chat-msg-avatar';
            avatar.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>';
            wrapper.appendChild(avatar);
        }

        const bubble = document.createElement('div');
        bubble.className = 'chat-msg-bubble';
        const ts = document.createElement('span');
        ts.className = 'chat-ts';
        ts.textContent = getAdminTimestamp();

        // For user messages, escape HTML. For bot messages, allow HTML if already formatted
        if (isUser) {
            bubble.innerHTML = escapeHtml(text).replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>').replace(/\n/g, '<br>');
        } else {
            // Check if text already contains HTML tags
            if (text.includes('<br>') || text.includes('<strong>')) {
                bubble.innerHTML = text;
            } else {
                bubble.innerHTML = escapeHtml(text).replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>').replace(/\n/g, '<br>');
            }
        }

        bubble.appendChild(ts);
        wrapper.appendChild(bubble);
        container.appendChild(wrapper);
        container.scrollTop = container.scrollHeight;

        if (save) saveChatHistory();
    }

    function showAdminTyping() {
        const container = document.getElementById('chatbot-messages');
        const wrapper = document.createElement('div');
        wrapper.className = 'chat-msg bot';
        wrapper.id = 'chat-typing';
        wrapper.innerHTML = '<div class="chat-msg-avatar"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></div><div class="chat-typing-indicator"><span></span><span></span><span></span></div>';
        container.appendChild(wrapper);
        container.scrollTop = container.scrollHeight;
    }

    function removeAdminTyping() {
        const el = document.getElementById('chat-typing');
        if (el) el.remove();
    }

    function clearAdminChat() {
        if (!confirm('Clear the conversation?')) return;
        localStorage.removeItem(STORAGE_KEY);
        loadChatHistory();
    }

    function quickAskAdmin(question) {
        document.getElementById('chat-input').value = question;
        sendAdminMessage();
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function sendAdminMessage() {
        const input = document.getElementById('chat-input');
        const text = input.value.trim();
        if (!text) return;

        addAdminMessage(text, true);
        input.value = '';
        showAdminTyping();

        fetch('/chatbot/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ message: text })
        })
            .then(response => response.json())
            .then(data => {
                removeAdminTyping();
                addAdminMessage(data.response || 'Sorry, I could not process your request.', false);
            })
            .catch(error => {
                removeAdminTyping();
                addAdminMessage('Sorry, an error occurred. Please try again.', false);
                console.error('Error:', error);
            });
    }

    // Load chat history on page load
    document.addEventListener('DOMContentLoaded', loadChatHistory);
</script>
