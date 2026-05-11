@extends('layouts.admin')

@section('page-content')
<div class="notif-wrap">
    <button class="notif-btn" id="notifBtn" onclick="toggleNotif()">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        <span class="notif-dot" id="notifDot"></span>
    </button>
    <div class="notif-panel" id="notifPanel">
        <div class="notif-head">
            <div>
                <h3>Notifications</h3>
                <p>You have <span id="unreadCount">3</span> unread message</p>
            </div>
            <button class="notif-clear" onclick="clearAll()">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            </button>
        </div>
        <div class="notif-body" id="notifBody">
            <div class="notif-card new" onclick="goToPage('/admin/leave-requests')">
                <div class="notif-left">
                    <div class="notif-avatar" style="background:linear-gradient(135deg,#15803d,#22c55e)">
                        <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                </div>
                <div class="notif-right">
                    <h4>New Leave Request</h4>
                    <p class="notif-msg">Juan Dela Cruz submitted a vacation leave request for 3 days</p>
                    <span class="notif-time">
                        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        5 minutes ago
                    </span>
                </div>
            </div>
            <div class="notif-card new" onclick="goToPage('/admin/payroll')">
                <div class="notif-left">
                    <div class="notif-avatar" style="background:linear-gradient(135deg,#0369a1,#0ea5e9)">
                        <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                    </div>
                </div>
                <div class="notif-right">
                    <h4>Payroll Processing</h4>
                    <p class="notif-msg">Monthly payroll for December 2024 is ready for review and approval</p>
                    <span class="notif-time">
                        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        15 minutes ago
                    </span>
                </div>
            </div>
            <div class="notif-card new" onclick="goToPage('/admin/attendance')">
                <div class="notif-left">
                    <div class="notif-avatar" style="background:linear-gradient(135deg,#b91c1c,#ef4444)">
                        <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    </div>
                </div>
                <div class="notif-right">
                    <h4>Late Attendance Alert</h4>
                    <p class="notif-msg">Maria Santos logged in 30 minutes late today without prior notice</p>
                    <span class="notif-time">
                        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        1 hour ago
                    </span>
                </div>
            </div>
            <div class="notif-card" onclick="goToPage('/admin/employees')">
                <div class="notif-left">
                    <div class="notif-avatar" style="background:linear-gradient(135deg,#7c3aed,#a78bfa)">
                        <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                </div>
                <div class="notif-right">
                    <h4>New Employee Onboarding</h4>
                    <p class="notif-msg">Pedro Reyes has been added to the system. Complete onboarding checklist</p>
                    <span class="notif-time">
                        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        2 hours ago
                    </span>
                </div>
            </div>
            <div class="notif-card" onclick="goToPage('/admin/settings')">
                <div class="notif-left">
                    <div class="notif-avatar" style="background:linear-gradient(135deg,#ea580c,#fb923c)">
                        <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                </div>
                <div class="notif-right">
                    <h4>System Update</h4>
                    <p class="notif-msg">PRIME HRIS will undergo maintenance on Dec 25, 2024 from 2:00 AM - 4:00 AM</p>
                    <span class="notif-time">
                        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        3 hours ago
                    </span>
                </div>
            </div>
            <div class="notif-card" onclick="goToPage('/admin/leave-requests')">
                <div class="notif-left">
                    <div class="notif-avatar" style="background:linear-gradient(135deg,#15803d,#22c55e)">
                        <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </div>
                <div class="notif-right">
                    <h4>Leave Request Approved</h4>
                    <p class="notif-msg">Anna Garcia's sick leave request for Dec 20-21 has been approved</p>
                    <span class="notif-time">
                        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Yesterday
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleNotif() {
    const panel = document.getElementById('notifPanel');
    panel.classList.toggle('open');
}

function goToPage(url) {
    window.location.href = url;
}

function clearAll() {
    const body = document.getElementById('notifBody');
    body.innerHTML = '<div class="notif-empty"><svg width="40" height="40" fill="none" stroke="#d9d9ee" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg><p>No notifications</p></div>';
    updateCount();
}

function updateCount() {
    const newCount = document.querySelectorAll('.notif-card.new').length;
    const dot = document.getElementById('notifDot');
    const countSpan = document.getElementById('unreadCount');
    countSpan.textContent = newCount;
    if (newCount > 0) {
        dot.classList.add('active');
    } else {
        dot.classList.remove('active');
    }
}

document.addEventListener('click', (e) => {
    const wrap = document.querySelector('.notif-wrap');
    const panel = document.getElementById('notifPanel');
    if (!wrap.contains(e.target)) {
        panel.classList.remove('open');
    }
});

window.addEventListener('load', updateCount);
</script>
@endpush

@endsection
