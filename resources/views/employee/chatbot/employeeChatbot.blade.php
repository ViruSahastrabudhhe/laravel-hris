@php
    $employee = \App\Models\Employee::where('user_id', auth()->id())->first();
    $isJobOrder = $employee && $employee->employment_type === \App\Enums\EmploymentType::JobOrder->value;
@endphp

{{-- AI Chatbot FAB --}}
<button class="chat-fab" id="chat-fab" onclick="toggleEmployeeChat()" title="AI Assistant">
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
                <p class="chatbot-name">PRIME HRIS Assistant</p>
                <p class="chatbot-status">● Online</p>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:6px">
            <button class="chatbot-clear" onclick="clearEmployeeChat()" title="Clear conversation">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/>
                </svg>
            </button>
            <button class="chatbot-close" onclick="toggleEmployeeChat()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
    </div>

    <div class="chatbot-quick-actions">
        @if(!$isJobOrder)
        <button class="chatbot-quick-btn" onclick="quickAskEmployee('How do I file a leave request?')">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/></svg>
            Leave
        </button>
        @endif
        <button class="chatbot-quick-btn" onclick="quickAskEmployee('How do I view my payslip?')">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
            Payslip
        </button>
        <button class="chatbot-quick-btn" onclick="quickAskEmployee('How do I check my attendance?')">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            Attendance
        </button>
        <button class="chatbot-quick-btn" onclick="quickAskEmployee('Contact HR')">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            Contact
        </button>
    </div>

    <div class="chatbot-messages" id="chatbot-messages"></div>

    <div class="chatbot-input-row">
        <button class="chatbot-voice-btn" id="chatbot-voice-btn" type="button" onclick="toggleEmployeeVoice()" title="Start voice input">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1.75a3.5 3.5 0 0 1 3.5 3.5v4.5a3.5 3.5 0 0 1-7 0V5.25a3.5 3.5 0 0 1 3.5-3.5z"/><path d="M19 10.25a7 7 0 0 1-14 0"/><path d="M12 19.5v4.25"/><path d="M8.5 23.75h7"/></svg>
        </button>
        <button class="chatbot-stop-btn" id="chatbot-stop-btn" type="button" onclick="stopEmployeeAudio()" title="Stop audio playback">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 5L6 9H2v6h4l5 4V5z"/><line x1="16.5" y1="8.5" x2="21" y2="13"/><line x1="21" y1="8.5" x2="16.5" y2="13"/></svg>
        </button>
        <input type="text" id="chat-input"
               placeholder="{{ $isJobOrder ? 'Ask about payroll, attendance, training...' : 'Ask about leave, payroll, training...' }}"
               onkeydown="if(event.key==='Enter') sendEmployeeMessage()">
        <button class="chatbot-send" onclick="sendEmployeeMessage()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/>
            </svg>
        </button>
    </div>
</div>

<script>
const EMP_BOT_AVATAR = '<img src="{{ asset('images/municipal-of-pagsanjan-logo.jpg') }}" alt="Logo" onerror="this.style.display=\'none\'" style="width:100%;height:100%;object-fit:cover;border-radius:50%">';
const IS_JOB_ORDER   = {{ $isJobOrder ? 'true' : 'false' }};
const EMP_STORAGE_KEY = 'employee_chat_history';

const EMP_WELCOME = IS_JOB_ORDER
    ? "Hello! I'm your PRIME HRIS assistant. I can help you with payslip inquiries, attendance records, training programs, performance, and system navigation. How can I assist you today?"
    : "Hello! I'm your PRIME HRIS assistant. I can help you with leave requests, payslip inquiries, attendance records, training programs, performance evaluations, and system navigation. How can I assist you today?";

// ── Speech ──────────────────────────────────────────────────────────
const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
let empRecognition = null;
let empListening   = false;
let empUtterance   = null;

function initEmpSpeech() {
    if (!SpeechRecognition) return;
    empRecognition = new SpeechRecognition();
    empRecognition.lang = 'en-US';
    empRecognition.interimResults = false;
    empRecognition.maxAlternatives = 1;
    empRecognition.continuous = false;
    empRecognition.onresult = e => {
        const t = Array.from(e.results).map(r => r[0].transcript).join('').trim();
        if (t) { document.getElementById('chat-input').value = t; sendEmployeeMessage(); }
    };
    empRecognition.onend  = () => { empListening = false; updateEmpVoiceBtn(); };
    empRecognition.onerror = () => { empListening = false; updateEmpVoiceBtn(); };
}

function updateEmpVoiceBtn() {
    const btn = document.getElementById('chatbot-voice-btn');
    if (!btn) return;
    btn.classList.toggle('listening', empListening);
    btn.title = empListening ? 'Stop voice input' : (SpeechRecognition ? 'Start voice input' : 'Voice input not supported');
}

function toggleEmployeeVoice() {
    if (!SpeechRecognition) { alert('Voice input is not supported in this browser.'); return; }
    if (!empRecognition) initEmpSpeech();
    if (empListening) { empRecognition.stop(); }
    else { try { empRecognition.start(); empListening = true; updateEmpVoiceBtn(); } catch(e) {} }
}

function stopEmployeeAudio() {
    if ('speechSynthesis' in window) { window.speechSynthesis.cancel(); empUtterance = null; }
}

function speakEmployee(text) {
    stopEmployeeAudio();
    if (!('speechSynthesis' in window) || !text.trim()) return;
    empUtterance = new SpeechSynthesisUtterance(text);
    empUtterance.lang = 'en-US';
    window.speechSynthesis.speak(empUtterance);
}

// ── History ─────────────────────────────────────────────────────────
function loadEmpHistory() {
    const history = JSON.parse(localStorage.getItem(EMP_STORAGE_KEY) || '[]');
    const container = document.getElementById('chatbot-messages');
    container.innerHTML = '';
    if (history.length === 0) {
        addEmpMessage(EMP_WELCOME, false, true, [], null, false);
    } else {
        history.forEach(m => addEmpMessage(m.text, m.isUser, false, [], null, false));
    }
}

function saveEmpHistory() {
    const messages = [];
    document.querySelectorAll('.chat-msg').forEach(msg => {
        const isUser = msg.classList.contains('user');
        const bubble = msg.querySelector('.chat-msg-bubble');
        if (bubble) {
            const clone = bubble.cloneNode(true);
            clone.querySelector('.chat-ts')?.remove();
            messages.push({ text: clone.innerHTML.trim(), isUser });
        }
    });
    localStorage.setItem(EMP_STORAGE_KEY, JSON.stringify(messages));
}

// ── Core UI ─────────────────────────────────────────────────────────
function getEmpTimestamp() {
    return new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
}

function toggleEmployeeChat() {
    const win   = document.getElementById('chatbot-window');
    const badge = document.getElementById('chat-fab-badge');
    const isOpen = win.style.display === 'flex';
    win.style.display = isOpen ? 'none' : 'flex';
    badge.style.display = isOpen ? 'block' : 'none';
    if (!isOpen) {
        const c = document.getElementById('chatbot-messages');
        setTimeout(() => c.scrollTop = c.scrollHeight, 50);
    }
}

function escapeEmpHtml(text) {
    const d = document.createElement('div');
    d.textContent = text;
    return d.innerHTML;
}

function addEmpMessage(text, isUser, save = true, followUps = [], fullResponse = null, shouldSpeak = false) {
    const container = document.getElementById('chatbot-messages');
    const wrapper   = document.createElement('div');
    wrapper.className = 'chat-msg ' + (isUser ? 'user' : 'bot');

    if (!isUser) {
        const avatar = document.createElement('div');
        avatar.className = 'chat-msg-avatar';
        avatar.innerHTML = EMP_BOT_AVATAR;
        wrapper.appendChild(avatar);
    }

    const bubble = document.createElement('div');
    bubble.className = 'chat-msg-bubble';
    const ts = document.createElement('span');
    ts.className = 'chat-ts';
    ts.textContent = getEmpTimestamp();

    let html = isUser
        ? escapeEmpHtml(text).replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>').replace(/\n/g, '<br>')
        : (text.includes('<br>') || text.includes('<strong>')
            ? text
            : escapeEmpHtml(text).replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>').replace(/\n/g, '<br>'));

    bubble.innerHTML = html;
    bubble.appendChild(ts);
    wrapper.appendChild(bubble);
    container.appendChild(wrapper);

    if (shouldSpeak && !isUser) speakEmployee(text);

    // See More / See Less
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

    // Follow-up suggestions
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
            btn.onclick = () => { document.getElementById('chat-input').value = q; sendEmployeeMessage(); };
            fuWrap.appendChild(btn);
        });
        container.appendChild(fuWrap);
    }

    container.scrollTop = container.scrollHeight;
    if (save) saveEmpHistory();
}

function showEmpTyping() {
    const container = document.getElementById('chatbot-messages');
    const wrapper   = document.createElement('div');
    wrapper.className = 'chat-msg bot';
    wrapper.id = 'chat-typing';
    wrapper.innerHTML = `<div class="chat-msg-avatar">${EMP_BOT_AVATAR}</div><div class="chat-typing-indicator"><span></span><span></span><span></span></div>`;
    container.appendChild(wrapper);
    container.scrollTop = container.scrollHeight;
}

function removeEmpTyping() {
    document.getElementById('chat-typing')?.remove();
}

function clearEmployeeChat() {
    if (!confirm('Clear the conversation?')) return;
    localStorage.removeItem(EMP_STORAGE_KEY);
    loadEmpHistory();
}

function quickAskEmployee(question) {
    document.getElementById('chat-input').value = question;
    sendEmployeeMessage();
}

// ── Send & Response ─────────────────────────────────────────────────
function sendEmployeeMessage() {
    const input = document.getElementById('chat-input');
    const text  = input.value.trim();
    if (!text) return;
    addEmpMessage(text, true);
    input.value = '';
    showEmpTyping();

    fetch('/chatbot/chat', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ message: text, is_job_order: IS_JOB_ORDER })
    })
    .then(r => r.json())
    .then(data => {
        removeEmpTyping();
        addEmpMessage(
            data.response || 'Sorry, I could not process your request.',
            false, true,
            data.follow_up_questions || [],
            data.full_response || null,
            true
        );
    })
    .catch(() => {
        removeEmpTyping();
        // Fallback to local response if API fails
        addEmpMessage(getEmpLocalResponse(text), false, true, getEmpFollowUps(text), null, false);
    });
}

// ── Local Fallback Responses ────────────────────────────────────────
function getEmpFollowUps(question) {
    const q = question.toLowerCase();
    if (!IS_JOB_ORDER && (q.includes('leave') || q.includes('vacation')))
        return ['What is my leave balance?', 'How long does approval take?'];
    if (q.includes('payslip') || q.includes('pay'))
        return ['When is the next pay date?', 'How do I download my payslip?'];
    if (q.includes('attendance') || q.includes('dtr'))
        return ['How do I filter my attendance?', 'What does Late status mean?'];
    if (q.includes('training') || q.includes('enroll'))
        return ['How do I download my certificate?', 'What trainings are available?'];
    return ['How do I navigate the system?', 'Contact HR'];
}

function getEmpLocalResponse(question) {
    const q = question.toLowerCase();

    if (q.includes('navigate') || q.includes('navigation') || q.includes('where') || q.includes('find') || q.includes('menu') || q.includes('sidebar')) {
        let nav = "**System Navigation Guide:**\n\n";
        nav += "• **Dashboard** — Overview of payroll, attendance, and leave\n";
        nav += "• **Profile** — View and update your personal information\n";
        if (!IS_JOB_ORDER) nav += "• **Leave & Benefits** — File and track leave requests\n";
        nav += "• **Trainings** — Enroll in and track training programs\n";
        nav += "• **Attendances** — View your daily time records\n";
        nav += "• **Performance** — View your evaluation history\n";
        nav += "• **Payslips** — View and download your payslips\n\n";
        nav += "Use the **sidebar** on the left to navigate between sections.";
        return nav;
    }

    if (!IS_JOB_ORDER && (q.includes('leave') || q.includes('vacation') || q.includes('sick') || q.includes('benefit'))) {
        return "To file a leave request:\n\n**1.** Go to **Leave & Benefits** in the sidebar\n**2.** Click **File Leave Request**\n**3.** Select leave type (Vacation, Sick, Emergency)\n**4.** Choose your dates and provide a reason\n**5.** Submit for HRMO approval\n\n**Leave Types Available:**\n• Vacation Leave\n• Sick Leave\n• Emergency Leave";
    }

    if (IS_JOB_ORDER && (q.includes('leave') || q.includes('vacation') || q.includes('benefit'))) {
        return "As a **Job Order** employee, leave and benefits filing is not available in the system.\n\nFor leave-related concerns, please coordinate directly with the **HR Office**.\n\n📧 hr@primehris.gov.ph\n📞 (123) 456-7890";
    }

    if (q.includes('payslip') || q.includes('payroll') || q.includes('salary') || q.includes('pay')) {
        return "To view your payslip:\n\n**1.** Go to **Payslips** in the sidebar\n**2.** Browse your payroll history\n**3.** Click **View** on any record to see the breakdown\n\nPayroll is processed **semi-monthly** (1st–15th and 16th–end of month).";
    }

    if (q.includes('attendance') || q.includes('dtr') || q.includes('time in') || q.includes('time out') || q.includes('absent') || q.includes('late')) {
        return "To view your attendance:\n\n**1.** Go to **Attendances** in the sidebar\n**2.** Your DTR for the current month is shown\n**3.** Use the **date filter** to view a specific range\n\nStatuses: **Present**, **Late**, **Absent**";
    }

    if (q.includes('training') || q.includes('course') || q.includes('enroll') || q.includes('program')) {
        return "To manage training programs:\n\n**1.** Go to **Trainings** in the sidebar\n**2.** View enrolled programs under **My Trainings**\n**3.** Browse **Available Trainings** to enroll\n**4.** Click **Enroll** and confirm\n\nCompleted trainings have a **Download Certificate** button.";
    }

    if (q.includes('performance') || q.includes('evaluation') || q.includes('rating')) {
        return "To view your performance:\n\n**1.** Go to **Performance** in the sidebar\n**2.** View your evaluation history and ratings\n**3.** Use the search bar to find specific evaluations\n\nEvaluations are conducted **semi-annually**.";
    }

    if (q.includes('profile') || q.includes('personal') || q.includes('update') || q.includes('edit')) {
        return "To update your profile:\n\n**1.** Go to **Profile** in the sidebar\n**2.** Click **Edit Profile**\n**3.** Update contact number, email, or address\n**4.** Click **Save Changes**";
    }

    if (IS_JOB_ORDER && (q.includes('contract') || q.includes('expir') || q.includes('renewal'))) {
        return "For contract-related inquiries, please coordinate with the **HR Office** at least **30 days** before expiration.\n\n📧 hr@primehris.gov.ph\n📞 (123) 456-7890\n🏢 Municipal Hall, 2nd Floor";
    }

    if (q.includes('contact') || q.includes('hr') || q.includes('help') || q.includes('support')) {
        return "**HR Contact Information:**\n\n📧 Email: hr@primehris.gov.ph\n📞 Phone: (123) 456-7890\n🏢 Office: Municipal Hall, 2nd Floor\n⏰ Hours: Mon–Fri, 8:00 AM – 5:00 PM";
    }

    if (q.includes('dashboard') || q.includes('home') || q.includes('overview')) {
        return "Your **Dashboard** shows:\n\n• Latest payslip summary\n• Leave credits remaining\n• Attendance rate this month\n• Recent payslip history\n• Quick action buttons\n\nClick **Dashboard** in the sidebar to return home.";
    }

    let topics = "• **Payslip** — View payroll records\n• **Attendance** — Check DTR records\n• **Training** — Enroll in programs\n• **Performance** — View evaluations\n• **Profile** — Update personal info\n• **Navigation** — How to use the system\n• **HR Contact** — Reach the HR office";
    if (!IS_JOB_ORDER) topics = "• **Leave & Benefits** — File leave requests\n" + topics;
    return `I can help you with:\n\n${topics}\n\nPlease ask me anything about these topics!`;
}

// ── Init ────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    loadEmpHistory();
    updateEmpVoiceBtn();
    if (!SpeechRecognition) {
        const mic = document.getElementById('chatbot-voice-btn');
        if (mic) mic.disabled = true;
    }
});
</script>
