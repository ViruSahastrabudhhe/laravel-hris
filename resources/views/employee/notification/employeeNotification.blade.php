@php
    use App\Enums\EmploymentType;
    $authEmployee = \App\Models\Employee::where('user_id', auth()->id())->first();
    $isJobOrder   = $authEmployee && $authEmployee->employment_type === EmploymentType::JobOrder;
@endphp

<div class="notif-wrap">
    <button class="notif-btn" id="notifBtn" onclick="toggleNotif()">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        <span class="notif-dot" id="notifDot"></span>
    </button>
    <div class="notif-panel" id="notifPanel">
        <div class="notif-head">
            <div>
                <h3>Notifications</h3>
                <p>You have <span id="unreadCount">0</span> unread message</p>
            </div>
            <button class="notif-clear" onclick="clearAll()">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            </button>
        </div>
        <div class="notif-body" id="notifBody">

            @if($isJobOrder)
                {{-- JOB ORDER NOTIFICATIONS --}}
                <div class="notif-card new" onclick="goToPage('{{ route('my_attendances.index') }}')">
                    <div class="notif-left">
                        <div class="notif-avatar" style="background:linear-gradient(135deg,#d9bb00,#f59e0b)">
                            <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        </div>
                    </div>
                    <div class="notif-right">
                        <h4>DTR Reminder</h4>
                        <p class="notif-msg">Please submit your daily time record for {{ now()->format('F d, Y') }}</p>
                        <span class="notif-time">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            2 hours ago
                        </span>
                    </div>
                </div>
                <div class="notif-card new" onclick="goToPage('{{ route('my_payslips.index') }}')">
                    <div class="notif-left">
                        <div class="notif-avatar" style="background:linear-gradient(135deg,#15803d,#22c55e)">
                            <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                        </div>
                    </div>
                    <div class="notif-right">
                        <h4>Payslip Available</h4>
                        <p class="notif-msg">Your payslip for {{ now()->format('M') }} 1–15, {{ now()->year }} is now available</p>
                        <span class="notif-time">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            1 day ago
                        </span>
                    </div>
                </div>
                <div class="notif-card" onclick="goToPage('{{ route('home') }}')">
                    <div class="notif-left">
                        <div class="notif-avatar" style="background:linear-gradient(135deg,#0369a1,#0ea5e9)">
                            <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        </div>
                    </div>
                    <div class="notif-right">
                        <h4>Contract Reminder</h4>
                        <p class="notif-msg">Your contract will expire on Dec 31, {{ now()->year }}. Please coordinate with HR</p>
                        <span class="notif-time">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            2 days ago
                        </span>
                    </div>
                </div>
                <div class="notif-card" onclick="goToPage('{{ route('my_trainings.index') }}')">
                    <div class="notif-left">
                        <div class="notif-avatar" style="background:linear-gradient(135deg,#ea580c,#fb923c)">
                            <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                        </div>
                    </div>
                    <div class="notif-right">
                        <h4>New Training Available</h4>
                        <p class="notif-msg">Cybersecurity Awareness training has been assigned to you</p>
                        <span class="notif-time">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            3 days ago
                        </span>
                    </div>
                </div>
                <div class="notif-card" onclick="goToPage('{{ route('home') }}')">
                    <div class="notif-left">
                        <div class="notif-avatar" style="background:linear-gradient(135deg,#ea580c,#fb923c)">
                            <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        </div>
                    </div>
                    <div class="notif-right">
                        <h4>System Update</h4>
                        <p class="notif-msg">PRIME HRIS will undergo maintenance on Dec 25, {{ now()->year }} from 2:00 AM – 4:00 AM</p>
                        <span class="notif-time">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            Yesterday
                        </span>
                    </div>
                </div>

            @else
                {{-- REGULAR / PERMANENT NOTIFICATIONS --}}
                <div class="notif-card new" onclick="goToPage('{{ route('my_leaves.index') }}')">
                    <div class="notif-left">
                        <div class="notif-avatar" style="background:linear-gradient(135deg,#15803d,#22c55e)">
                            <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                    </div>
                    <div class="notif-right">
                        <h4>Leave Request Approved</h4>
                        <p class="notif-msg">Your vacation leave request for 3 days has been approved by HR</p>
                        <span class="notif-time">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            5 minutes ago
                        </span>
                    </div>
                </div>
                <div class="notif-card new" onclick="goToPage('{{ route('my_payslips.index') }}')">
                    <div class="notif-left">
                        <div class="notif-avatar" style="background:linear-gradient(135deg,#0369a1,#0ea5e9)">
                            <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                        </div>
                    </div>
                    <div class="notif-right">
                        <h4>Payslip Available</h4>
                        <p class="notif-msg">Your payslip for {{ now()->format('F Y') }} is now available for download</p>
                        <span class="notif-time">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            15 minutes ago
                        </span>
                    </div>
                </div>
                <div class="notif-card new" onclick="goToPage('{{ route('my_performance.index') }}')">
                    <div class="notif-left">
                        <div class="notif-avatar" style="background:linear-gradient(135deg,#7c3aed,#a78bfa)">
                            <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        </div>
                    </div>
                    <div class="notif-right">
                        <h4>Performance Review Due</h4>
                        <p class="notif-msg">Your quarterly performance review is due by Dec 31, {{ now()->year }}</p>
                        <span class="notif-time">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            1 hour ago
                        </span>
                    </div>
                </div>
                <div class="notif-card" onclick="goToPage('{{ route('my_trainings.index') }}')">
                    <div class="notif-left">
                        <div class="notif-avatar" style="background:linear-gradient(135deg,#ea580c,#fb923c)">
                            <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                        </div>
                    </div>
                    <div class="notif-right">
                        <h4>New Training Available</h4>
                        <p class="notif-msg">Cybersecurity Awareness training has been assigned to you</p>
                        <span class="notif-time">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            2 hours ago
                        </span>
                    </div>
                </div>
                <div class="notif-card" onclick="goToPage('{{ route('home') }}')">
                    <div class="notif-left">
                        <div class="notif-avatar" style="background:linear-gradient(135deg,#ea580c,#fb923c)">
                            <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        </div>
                    </div>
                    <div class="notif-right">
                        <h4>System Update</h4>
                        <p class="notif-msg">PRIME HRIS will undergo maintenance on Dec 25, {{ now()->year }} from 2:00 AM – 4:00 AM</p>
                        <span class="notif-time">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            3 hours ago
                        </span>
                    </div>
                </div>
                <div class="notif-card" onclick="goToPage('{{ route('my_attendances.index') }}')">
                    <div class="notif-left">
                        <div class="notif-avatar" style="background:linear-gradient(135deg,#b91c1c,#ef4444)">
                            <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        </div>
                    </div>
                    <div class="notif-right">
                        <h4>Attendance Reminder</h4>
                        <p class="notif-msg">Don't forget to log your time out before leaving today</p>
                        <span class="notif-time">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            Yesterday
                        </span>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>


<script>
function toggleNotif() {
    document.getElementById('notifPanel').classList.toggle('open');
}

function goToPage(url) {
    window.location.href = url;
}

function clearAll() {
    document.getElementById('notifBody').innerHTML = '<div class="notif-empty"><svg width="40" height="40" fill="none" stroke="#d9d9ee" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg><p>No notifications</p></div>';
    updateCount();
}

function updateCount() {
    const newCount = document.querySelectorAll('.notif-card.new').length;
    const dot = document.getElementById('notifDot');
    document.getElementById('unreadCount').textContent = newCount;
    dot.classList.toggle('active', newCount > 0);
}

document.addEventListener('click', (e) => {
    const wrap = document.querySelector('.notif-wrap');
    const panel = document.getElementById('notifPanel');
    if (wrap && !wrap.contains(e.target)) panel.classList.remove('open');
});

window.addEventListener('load', updateCount);
</script>
