@php $isLanding = false; @endphp

{{-- AI Chatbot FAB --}}
<button class="chat-fab" id="chat-fab" onclick="{{ $isLanding ? 'toggleChat()' : 'toggleAdminChat()' }}" title="AI Assistant">
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
                <img src="{{ asset('images/municipal-of-pagsanjan-logo.jpg') }}" alt="Logo"
                     onerror="this.style.display='none'"
                     style="width:100%;height:100%;object-fit:cover;border-radius:50%">
            </div>
            <div>
                <p class="chatbot-name">{{ $isLanding ? 'Pagsanjan LGU Assistant' : 'PRIME HRIS Assistant' }}</p>
                <p class="chatbot-status">● Online</p>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:6px">
            <button class="chatbot-clear" onclick="{{ $isLanding ? 'clearChat()' : 'clearAdminChat()' }}" title="Clear conversation">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/>
                </svg>
            </button>
            <button class="chatbot-close" onclick="{{ $isLanding ? 'toggleChat()' : 'toggleAdminChat()' }}">
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

    <div class="chatbot-messages" id="chatbot-messages"></div>

    <div class="chatbot-input-row">
        <button class="chatbot-voice-btn" id="chatbot-voice-btn" type="button" onclick="toggleVoiceInput()" title="Start voice input">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1.75a3.5 3.5 0 0 1 3.5 3.5v4.5a3.5 3.5 0 0 1-7 0V5.25a3.5 3.5 0 0 1 3.5-3.5z"/><path d="M19 10.25a7 7 0 0 1-14 0"/><path d="M12 19.5v4.25"/><path d="M8.5 23.75h7"/></svg>
        </button>
        <button class="chatbot-stop-btn" id="chatbot-stop-btn" type="button" onclick="stopAudioPlayback()" title="Stop audio playback">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 5L6 9H2v6h4l5 4V5z"/><line x1="16.5" y1="8.5" x2="21" y2="13"/><line x1="21" y1="8.5" x2="16.5" y2="13"/></svg>
        </button>
        <input type="text" id="chat-input"
               placeholder="Ask about HRIS features..."
               onkeydown="if(event.key==='Enter') sendAdminMessage()">
        <button class="chatbot-send" onclick="sendAdminMessage()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/>
            </svg>
        </button>
    </div>
</div>

<script>
const BOT_AVATAR_HTML = '<img src="{{ asset('images/municipal-of-pagsanjan-logo.jpg') }}" alt="Logo" onerror="this.style.display=\'none\'" style="width:100%;height:100%;object-fit:cover;border-radius:50%">';

const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
let recognition = null;
let isListening = false;
let currentUtterance = null;
const STORAGE_KEY = 'admin_chat_history';

function initializeSpeechRecognition() {
    if (!SpeechRecognition) return;
    recognition = new SpeechRecognition();
    recognition.lang = 'en-US';
    recognition.interimResults = false;
    recognition.maxAlternatives = 1;
    recognition.continuous = false;

    recognition.onresult = function(event) {
        const transcript = Array.from(event.results)
            .map(result => result[0].transcript)
            .join('')
            .trim();

        if (!transcript) return;
        const input = document.getElementById('chat-input');
        input.value = transcript;
        sendAdminMessage();
    };

    recognition.onend = function() {
        isListening = false;
        updateVoiceButtonState();
    };

    recognition.onerror = function(event) {
        console.error('Speech recognition error:', event.error);
        isListening = false;
        updateVoiceButtonState();
    };
}

function updateVoiceButtonState() {
    const btn = document.getElementById('chatbot-voice-btn');
    if (!btn) return;
    btn.classList.toggle('listening', isListening);
    btn.title = isListening ? 'Stop voice input' : (SpeechRecognition ? 'Start voice input' : 'Voice input not supported');
}

function toggleVoiceInput() {
    if (!SpeechRecognition) {
        alert('Voice input is not supported in this browser.');
        return;
    }

    if (!recognition) {
        initializeSpeechRecognition();
    }

    if (isListening) {
        recognition.stop();
    } else {
        try {
            recognition.start();
            isListening = true;
            updateVoiceButtonState();
        } catch (error) {
            console.error('Unable to start speech recognition:', error);
        }
    }
}

function stopVoiceInput() {
    if (recognition && isListening) {
        recognition.stop();
    }
    isListening = false;
    updateVoiceButtonState();
}

function stopAudioPlayback() {
    if ('speechSynthesis' in window) {
        window.speechSynthesis.cancel();
        currentUtterance = null;
    }
}

function speakText(text) {
    stopAudioPlayback();

    if (!('speechSynthesis' in window)) return;
    if (!text || text.trim().length === 0) return;

    currentUtterance = new SpeechSynthesisUtterance(text);
    currentUtterance.lang = 'en-US';
    currentUtterance.rate = 1;
    currentUtterance.pitch = 1;
    window.speechSynthesis.speak(currentUtterance);
}

function loadChatHistory() {
    const history = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
    const container = document.getElementById('chatbot-messages');
    container.innerHTML = '';
    if (history.length === 0) {
        addAdminMessage("Hello! I'm your PRIME HRIS assistant. I can help you with leave applications, payroll inquiries, DTR records, and HR procedures. How can I assist you today?", false, true, [], null, false);
    } else {
        history.forEach(msg => addAdminMessage(msg.text, msg.isUser, false, [], null, false));
    }
}

function saveChatHistory() {
    const messages = [];
    document.querySelectorAll('.chat-msg').forEach(msg => {
        const isUser = msg.classList.contains('user');
        const bubble = msg.querySelector('.chat-msg-bubble');
        if (bubble) {
            const clone = bubble.cloneNode(true);
            const ts = clone.querySelector('.chat-ts');
            if (ts) ts.remove();
            messages.push({ text: clone.innerHTML.trim(), isUser });
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
    if (!isOpen) {
        const container = document.getElementById('chatbot-messages');
        setTimeout(() => container.scrollTop = container.scrollHeight, 50);
    }
}

function addAdminMessage(text, isUser, save = true, followUps = [], fullResponse = null, shouldSpeak = false) {
    const container = document.getElementById('chatbot-messages');
    const wrapper = document.createElement('div');
    wrapper.className = 'chat-msg ' + (isUser ? 'user' : 'bot');

    if (!isUser) {
        const avatar = document.createElement('div');
        avatar.className = 'chat-msg-avatar';
        avatar.innerHTML = BOT_AVATAR_HTML;
        wrapper.appendChild(avatar);
    }

    const bubble = document.createElement('div');
    bubble.className = 'chat-msg-bubble';
    const ts = document.createElement('span');
    ts.className = 'chat-ts';
    ts.textContent = getAdminTimestamp();

    let html;
    if (isUser) {
        html = escapeHtml(text).replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>').replace(/\n/g, '<br>');
    } else {
        html = (text.includes('<br>') || text.includes('<strong>'))
            ? text
            : escapeHtml(text).replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>').replace(/\n/g, '<br>');
    }

    bubble.innerHTML = html;
    bubble.appendChild(ts);
    wrapper.appendChild(bubble);
    container.appendChild(wrapper);
    if (!isUser && shouldSpeak) {
        speakText(text);
    }

    if (!isUser && fullResponse && fullResponse !== text) {
        const toggleWrap = document.createElement('div');
        toggleWrap.className = 'chat-toggle-wrap';
        const toggleBtn = document.createElement('button');
        toggleBtn.className = 'chat-toggle-btn';
        toggleBtn.innerHTML = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg> See More';
        toggleBtn.onclick = () => {
            if (toggleBtn.dataset.open !== 'true') {
                bubble.innerHTML = fullResponse.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>').replace(/\n/g, '<br>');
                bubble.appendChild(ts);
                toggleBtn.innerHTML = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg> See Less';
                toggleBtn.dataset.open = 'true';
            } else {
                bubble.innerHTML = html;
                bubble.appendChild(ts);
                toggleBtn.innerHTML = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg> See More';
                toggleBtn.dataset.open = 'false';
            }
        };
        toggleWrap.appendChild(toggleBtn);
        container.appendChild(toggleWrap);
    }

    if (!isUser && followUps.length > 0) {
        const fuWrap = document.createElement('div');
        fuWrap.className = 'chat-followups';
        const label = document.createElement('p');
        label.className = 'chat-followup-label';
        label.innerHTML = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:5px;vertical-align:middle"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>You might also want to ask:';
        fuWrap.appendChild(label);
        followUps.forEach(q => {
            const btn = document.createElement('button');
            btn.className = 'chat-followup-btn';
            btn.textContent = q;
            btn.onclick = () => { document.getElementById('chat-input').value = q; sendAdminMessage(); };
            fuWrap.appendChild(btn);
        });
        container.appendChild(fuWrap);
    }

    container.scrollTop = container.scrollHeight;
    if (save) saveChatHistory();
}

function showAdminTyping() {
    const container = document.getElementById('chatbot-messages');
    const wrapper = document.createElement('div');
    wrapper.className = 'chat-msg bot';
    wrapper.id = 'chat-typing';
    wrapper.innerHTML = `<div class="chat-msg-avatar">${BOT_AVATAR_HTML}</div><div class="chat-typing-indicator"><span></span><span></span><span></span></div>`;
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
        addAdminMessage(
            data.response || 'Sorry, I could not process your request.',
            false, true,
            data.follow_up_questions || [],
            data.full_response || null,
            true
        );
    })
    .catch(error => {
        removeAdminTyping();
        addAdminMessage('Sorry, an error occurred. Please try again.', false);
        console.error('Error:', error);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    loadChatHistory();
    updateVoiceButtonState();
    if (!SpeechRecognition) {
        const mic = document.getElementById('chatbot-voice-btn');
        if (mic) {
            mic.disabled = true;
        }
    }
});
</script>
