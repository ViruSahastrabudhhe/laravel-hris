@extends('layouts.employee')

@section('page-content')

{{-- Welcome Banner --}}
<div class="welcome-banner">
    <div class="banner-left">
        <div class="banner-icon">
            <svg width="22" height="22" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        </div>
        <div>
            <h2>Performance Overview</h2>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>
    </div>
    <div class="banner-right">
        <div class="recruit-search-wrap">
            <svg width="13" height="13" fill="none" stroke="#9999bb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" id="perf-search" placeholder="Search evaluations..." class="recruit-search" oninput="filterEvalTable(this.value)">
        </div>
    </div>
</div>

{{-- Stats Grid --}}
<div class="stats-grid stats-grid-4">
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Latest Rating</p>
            <div class="stat-icon-wrap" style="background:#0b044d15">
                <svg width="17" height="17" fill="none" stroke="#0b044d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            </div>
        </div>
        <p class="stat-value">—</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#0b044d"></span>
            <p class="stat-sub">Latest evaluation</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Average Rating</p>
            <div class="stat-icon-wrap" style="background:#15803d15">
                <svg width="17" height="17" fill="none" stroke="#15803d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 18"/></svg>
            </div>
        </div>
        <p class="stat-value">—</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#15803d"></span>
            <p class="stat-sub">All evaluations</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Total Evaluations</p>
            <div class="stat-icon-wrap" style="background:#d9bb0015">
                <svg width="17" height="17" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
            </div>
        </div>
        <p class="stat-value">0</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#d9bb00"></span>
            <p class="stat-sub">Completed reviews</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Goals Achieved</p>
            <div class="stat-icon-wrap" style="background:#8e1e1815">
                <svg width="17" height="17" fill="none" stroke="#8e1e18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
            </div>
        </div>
        <p class="stat-value">0</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#8e1e18"></span>
            <p class="stat-sub">Total goals</p>
        </div>
    </div>
</div>

{{-- Evaluation History Table --}}
<div class="table-section">
    <div class="table-header">
        <div>
            <p class="table-title">Evaluation History</p>
            <p class="table-sub">Your complete performance evaluation records</p>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="payroll-table" id="eval-table">
            <thead>
                <tr>
                    <th>Evaluation ID</th>
                    <th>Period</th>
                    <th>Evaluator</th>
                    <th>Completed Date</th>
                    <th>Rating</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="eval-table-body">
                <tr>
                    <td colspan="7" style="text-align:center;padding:40px;color:#9999bb;font-size:13px">
                        No performance evaluations found.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="table-footer" style="display:flex;justify-content:space-between;align-items:center;padding:12px 20px;border-top:1px solid #f0effe;">
        <span style="font-size:12px;color:#6b6a8a">Showing <strong id="eval-count">0</strong> evaluation records</span>
    </div>
</div>

{{-- Evaluation View Modal --}}
<div class="perf-modal-overlay" id="evalModal" onclick="closeEvalModal()">
    <div class="perf-modal-box" onclick="event.stopPropagation()">
        <div class="perf-modal-header">
            <div class="pmodal-hero">
                <div class="pmodal-hero-icon" id="evalIcon" style="background:linear-gradient(135deg,#0b044d,#1a0f6e)">
                    <svg width="24" height="24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                </div>
                <div>
                    <span class="modal-eyebrow" id="evalId">PERFORMANCE EVALUATION</span>
                    <h3 class="modal-title">Evaluation Report</h3>
                    <p class="modal-sub" id="evalSub">Period · Completed on Date</p>
                </div>
            </div>
            <button class="modal-close" onclick="closeEvalModal()">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="perf-modal-body" id="evalBody"></div>
        <div class="perf-modal-footer">
            <button class="modal-btn-ghost" onclick="closeEvalModal()">Close</button>
            <button class="modal-btn-primary">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Download Report
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function filterEvalTable(query) {
        const q = query.toLowerCase();
        const rows = document.querySelectorAll('#eval-table-body tr');
        let count = 0;
        rows.forEach(row => {
            const show = row.textContent.toLowerCase().includes(q);
            row.style.display = show ? '' : 'none';
            if (show) count++;
        });
        document.getElementById('eval-count').textContent = count;
    }

    function openEvaluation(id, period, rating, completedDate, evaluator, feedback, strengths, improvements) {
        const ratingColor = rating >= 4.5 ? '#15803d' : rating >= 4.0 ? '#d9bb00' : '#8e1e18';
        document.getElementById('evalId').textContent = 'PERFORMANCE EVALUATION · ' + id;
        document.getElementById('evalSub').textContent = period + ' · Completed on ' + completedDate;
        document.getElementById('evalIcon').style.background = 'linear-gradient(135deg,' + ratingColor + ',' + ratingColor + '99)';

        const strengthsHtml = strengths.map(s => '<span class="perf-tag" style="background:#15803d15;color:#15803d">' + s + '</span>').join('');
        const improvementsHtml = improvements.map(i => '<span class="perf-tag" style="background:#d9bb0015;color:#a16207">' + i + '</span>').join('');

        document.getElementById('evalBody').innerHTML =
            '<div class="perf-rating-box"><div class="perf-rating-icon" style="background:linear-gradient(135deg,' + ratingColor + ',' + ratingColor + '99)"><span>' + rating + '</span></div><div><p class="perf-rating-label">OVERALL RATING</p><p class="perf-rating-value">' + rating + ' out of 5.0</p></div></div>' +
            '<span class="modal-section-label">EVALUATION DETAILS</span>' +
            '<div class="modal-row"><span>Period</span><strong>' + period + '</strong></div>' +
            '<div class="modal-row"><span>Evaluator</span><strong>' + evaluator + '</strong></div>' +
            '<div class="modal-row"><span>Completed</span><strong>' + completedDate + '</strong></div>' +
            '<span class="modal-section-label" style="margin-top:16px">FEEDBACK</span>' +
            '<p style="font-size:13px;color:#6b6a8a;line-height:1.6;margin-bottom:16px">' + feedback + '</p>' +
            '<span class="modal-section-label">STRENGTHS</span>' +
            '<div class="perf-tags">' + strengthsHtml + '</div>' +
            '<span class="modal-section-label">AREAS FOR IMPROVEMENT</span>' +
            '<div class="perf-tags">' + improvementsHtml + '</div>';

        document.getElementById('evalModal').classList.add('show');
    }

    function closeEvalModal() {
        document.getElementById('evalModal').classList.remove('show');
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeEvalModal();
    });
</script>
@endpush
