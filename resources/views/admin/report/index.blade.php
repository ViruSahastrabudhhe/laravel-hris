@extends('layouts.admin')
@php $hideChat = true; @endphp

@section('page-content')

{{-- Welcome Banner --}}
<div class="welcome-banner">
    <div class="banner-left">
        <div class="banner-icon">
            <svg width="22" height="22" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
        </div>
        <div>
            <h2>Reports & Analytics</h2>
            <p>{{ config('app.carbon_date') }} &nbsp;·&nbsp; {{ $now->format('F Y') }} Overview</p>
        </div>
    </div>
    <div class="banner-right">
        <div class="recruit-search-wrap">
            <svg width="15" height="15" fill="none" stroke="rgba(255,255,255,0.5)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" id="search-input" placeholder="Search reports..." class="recruit-search" oninput="filterCards()">
        </div>
    </div>
</div>

{{-- Top Stats --}}
<div class="stats-grid stats-grid-4" style="margin-bottom:24px">
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Total Employees</p>
            <div class="stat-icon-wrap" style="background:rgba(11,4,77,0.1)">
                <svg width="18" height="18" fill="none" stroke="#0b044d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $totalEmployees }}</p>
        <div class="stat-footer"><span class="stat-dot" style="background:#0b044d"></span><p class="stat-sub">{{ $activeEmployees }} active</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Payroll Processed</p>
            <div class="stat-icon-wrap" style="background:rgba(21,128,61,0.1)">
                <svg width="18" height="18" fill="none" stroke="#15803d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $processedPayroll }}</p>
        <div class="stat-footer"><span class="stat-dot" style="background:#15803d"></span><p class="stat-sub">{{ $now->format('F Y') }}</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Leave Requests</p>
            <div class="stat-icon-wrap" style="background:rgba(217,187,0,0.1)">
                <svg width="18" height="18" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $pendingLeaves + $approvedLeaves + $declinedLeaves }}</p>
        <div class="stat-footer"><span class="stat-dot" style="background:#d9bb00"></span><p class="stat-sub">{{ $pendingLeaves }} pending</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Trainings</p>
            <div class="stat-icon-wrap" style="background:rgba(142,30,24,0.1)">
                <svg width="18" height="18" fill="none" stroke="#8e1e18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $trainings->count() }}</p>
        <div class="stat-footer"><span class="stat-dot" style="background:#8e1e18"></span><p class="stat-sub">{{ $ongoingTrainings }} ongoing</p></div>
    </div>
</div>

{{-- Section Header --}}
<div class="table-section recruitment-header" style="margin-bottom:16px">
    <div class="table-header">
        <div>
            <p class="table-title">Report Modules</p>
            <p class="table-sub">Municipality of Pagsanjan &nbsp;·&nbsp; <span id="showing-count">4</span> of 4 modules</p>
        </div>
        <div class="table-actions">
            <select class="filter-select" id="category-filter" onchange="filterCards()">
                <option value="All">All Modules</option>
                <option value="Payroll">Payroll</option>
                <option value="Attendance">Attendance</option>
                <option value="Leave">Leave</option>
                <option value="Training">Training</option>
            </select>
        </div>
    </div>
</div>

{{-- Report Cards Grid --}}
<div class="job-grid" id="report-grid">

    {{-- Payroll Report --}}
    <div class="job-card report-card" data-category="Payroll" data-title="payroll report">
        <div class="job-card-header">
            <span class="badge-status processed">Payroll</span>
        </div>
        <div class="job-card-body">
            <div class="job-card-icon">
                <div class="job-slot-badge" style="background:linear-gradient(135deg,#15803d,#22c55e)">
                    <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
            </div>
            <div class="job-card-info">
                <p class="job-id">{{ $now->format('F Y') }}</p>
                <h4 class="job-title">Payroll Summary Report</h4>
            </div>
        </div>
        <p class="job-dept">Monthly payroll overview for all employees</p>
        <div class="report-metrics">
            <div class="report-metric">
                <span class="metric-label">Gross Pay</span>
                <span class="metric-value">₱{{ number_format($totalGross, 2) }}</span>
            </div>
            <div class="report-metric">
                <span class="metric-label">Deductions</span>
                <span class="metric-value">₱0</span>
            </div>
            <div class="report-metric">
                <span class="metric-label">Net Pay</span>
                <span class="metric-value" style="color:#15803d;font-weight:700">₱{{ number_format($totalNetPay, 2) }}</span>
            </div>
            <div class="report-metric">
                <span class="metric-label">Processed</span>
                <span class="metric-value">{{ $processedPayroll }} records</span>
            </div>
        </div>
        <div class="job-card-footer">
            <div>
                <p class="job-deadline-label">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Period
                </p>
                <p class="job-deadline-date">{{ $now->format('F Y') }}</p>
            </div>
            <div class="job-actions">
                <a href="{{ route('payroll.index') }}" class="btn-view">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    View
                </a>
                <a href="{{ route('payroll.exportPayroll') }}" class="btn-edit">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Export
                </a>
            </div>
        </div>
    </div>

    {{-- Attendance Report --}}
    <div class="job-card report-card" data-category="Attendance" data-title="attendance report">
        <div class="job-card-header">
            <span class="badge-status on-hold">Attendance</span>
        </div>
        <div class="job-card-body">
            <div class="job-card-icon">
                <div class="job-slot-badge" style="background:linear-gradient(135deg,#0b044d,#3b3a8a)">
                    <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
            </div>
            <div class="job-card-info">
                <p class="job-id">{{ $now->format('F Y') }}</p>
                <h4 class="job-title">Attendance Summary Report</h4>
            </div>
        </div>
        <p class="job-dept">Monthly attendance breakdown for all employees</p>
        <div class="report-metrics">
            <div class="report-metric">
                <span class="metric-label">Present</span>
                <span class="metric-value" style="color:#15803d;font-weight:700">{{ $totalPresent }}</span>
            </div>
            <div class="report-metric">
                <span class="metric-label">Late</span>
                <span class="metric-value" style="color:#d9bb00;font-weight:700">{{ $totalLate }}</span>
            </div>
            <div class="report-metric">
                <span class="metric-label">Absent</span>
                <span class="metric-value" style="color:#8e1e18;font-weight:700">{{ $totalAbsent }}</span>
            </div>
            <div class="report-metric">
                <span class="metric-label">Overtime Hrs</span>
                <span class="metric-value">{{ $totalOvertimeHrs }} hrs</span>
            </div>
        </div>
        <div class="job-card-footer">
            <div>
                <p class="job-deadline-label">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Period
                </p>
                <p class="job-deadline-date">{{ $now->format('F Y') }}</p>
            </div>
            <div class="job-actions">
                <a href="{{ route('attendances.index') }}" class="btn-view">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    View
                </a>
            </div>
        </div>
    </div>

    {{-- Leave Report --}}
    <div class="job-card report-card" data-category="Leave" data-title="leave report">
        <div class="job-card-header">
            <span class="badge-status" style="background:rgba(217,187,0,0.12);color:#a08800;border:1px solid rgba(217,187,0,0.3)">Leave</span>
        </div>
        <div class="job-card-body">
            <div class="job-card-icon">
                <div class="job-slot-badge" style="background:linear-gradient(135deg,#d9bb00,#f5d800)">
                    <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
            </div>
            <div class="job-card-info">
                <p class="job-id">All Time</p>
                <h4 class="job-title">Leave Requests Report</h4>
            </div>
        </div>
        <p class="job-dept">Leave request status overview across all employees</p>
        <div class="report-metrics">
            <div class="report-metric">
                <span class="metric-label">Pending</span>
                <span class="metric-value" style="color:#d9bb00;font-weight:700">{{ $pendingLeaves }}</span>
            </div>
            <div class="report-metric">
                <span class="metric-label">Approved</span>
                <span class="metric-value" style="color:#15803d;font-weight:700">{{ $approvedLeaves }}</span>
            </div>
            <div class="report-metric">
                <span class="metric-label">Declined</span>
                <span class="metric-value" style="color:#8e1e18;font-weight:700">{{ $declinedLeaves }}</span>
            </div>
            <div class="report-metric">
                <span class="metric-label">Total</span>
                <span class="metric-value">{{ $pendingLeaves + $approvedLeaves + $declinedLeaves }}</span>
            </div>
        </div>
        <div class="job-card-footer">
            <div>
                <p class="job-deadline-label">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Period
                </p>
                <p class="job-deadline-date">All Time</p>
            </div>
            <div class="job-actions">
                <a href="{{ route('leave_requests.index') }}" class="btn-view">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    View
                </a>
            </div>
        </div>
    </div>

    {{-- Training Report --}}
    <div class="job-card report-card" data-category="Training" data-title="training report">
        <div class="job-card-header">
            <span class="badge-status" style="background:rgba(142,30,24,0.1);color:#8e1e18;border:1px solid rgba(142,30,24,0.25)">Training</span>
        </div>
        <div class="job-card-body">
            <div class="job-card-icon">
                <div class="job-slot-badge" style="background:linear-gradient(135deg,#8e1e18,#c0392b)">
                    <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                </div>
            </div>
            <div class="job-card-info">
                <p class="job-id">All Time</p>
                <h4 class="job-title">Training Programs Report</h4>
            </div>
        </div>
        <p class="job-dept">Training programs and participant enrollment overview</p>
        <div class="report-metrics">
            <div class="report-metric">
                <span class="metric-label">Total Programs</span>
                <span class="metric-value">{{ $trainings->count() }}</span>
            </div>
            <div class="report-metric">
                <span class="metric-label">Ongoing</span>
                <span class="metric-value" style="color:#d9bb00;font-weight:700">{{ $ongoingTrainings }}</span>
            </div>
            <div class="report-metric">
                <span class="metric-label">Completed</span>
                <span class="metric-value" style="color:#15803d;font-weight:700">{{ $completedTrainings }}</span>
            </div>
            <div class="report-metric">
                <span class="metric-label">Participants</span>
                <span class="metric-value">{{ $totalParticipants }}</span>
            </div>
        </div>
        <div class="job-card-footer">
            <div>
                <p class="job-deadline-label">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Period
                </p>
                <p class="job-deadline-date">All Time</p>
            </div>
            <div class="job-actions">
                <a href="{{ route('trainings.index') }}" class="btn-view">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    View
                </a>
            </div>
        </div>
    </div>

</div>

{{-- Empty state --}}
<div id="grid-empty-state" style="display:none;text-align:center;padding:60px 20px">
    <div style="width:80px;height:80px;margin:0 auto 20px;background:linear-gradient(135deg,#f7f6ff,#eceaf8);border-radius:50%;display:flex;align-items:center;justify-content:center">
        <svg width="32" height="32" fill="none" stroke="#9999bb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
    </div>
    <h3 style="font-size:16px;font-weight:700;color:#0b044d;margin:0 0 8px">No Reports Found</h3>
    <p style="font-size:13px;color:#9999bb;margin:0">Try adjusting your filters or search criteria</p>
</div>

{{-- Department Breakdown --}}
<div class="table-section" style="margin-top:8px">
    <div class="table-header" style="padding:18px 20px 14px">
        <div>
            <p class="table-title">Workforce by Department</p>
            <p class="table-sub">Employee headcount per department</p>
        </div>
    </div>
    <div class="table-wrapper">
        <table class="payroll-table">
            <thead>
                <tr>
                    <th>Department</th>
                    <th>Code</th>
                    <th>Head</th>
                    <th style="text-align:center">Employees</th>
                    <th style="text-align:center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($departments as $dept)
                <tr>
                    <td><span class="position-cell">{{ $dept->name }}</span></td>
                    <td style="font-size:12.5px;color:#6b6a8a;font-weight:500">{{ $dept->department_code ?? '—' }}</td>
                    <td style="font-size:13px;color:#6b6a8a">{{ $dept->department_head ?? '—' }}</td>
                    <td style="font-size:13px;color:#0b044d;font-weight:600;text-align:center">{{ $dept->employees_count }}</td>
                    <td style="text-align:center">
                        <span class="badge-status {{ $dept->is_active ? 'processed' : 'on-hold' }}">
                            {{ $dept->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;color:#9999bb;padding:30px">No departments found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="table-footer">
        <p>Showing <strong>{{ $departments->count() }}</strong> departments &nbsp;·&nbsp; <strong>{{ $totalEmployees }}</strong> total employees</p>
    </div>
</div>

@endsection

@push('scripts')
<script>
function filterCards() {
    const cat = document.getElementById('category-filter').value;
    const q   = document.getElementById('search-input').value.toLowerCase();
    let count = 0;

    document.querySelectorAll('.report-card').forEach(card => {
        const match = (cat === 'All' || card.dataset.category === cat)
                   && (!q || card.dataset.title.includes(q) || card.dataset.category.toLowerCase().includes(q));
        card.style.display = match ? 'block' : 'none';
        if (match) count++;
    });

    document.getElementById('showing-count').textContent = count;
    document.getElementById('grid-empty-state').style.display = count === 0 ? 'block' : 'none';
}
</script>
@endpush
