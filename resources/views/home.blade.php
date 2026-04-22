@extends('layouts.admin')

@php
$monthlyPayroll = 0;
$employeesOnLeave = 0;
$employeesPendingLeave = 0;
$totalEmployees = isset($employees) ? $employees->count() : 0;

if (isset($employees)) {
    foreach ($employees as $employee) {
        $monthlyPayroll += method_exists($employee, 'netPay') ? $employee->netPay() : 0;
        $employeesOnLeave += $employee->leaves()->where('leave_status', 'Approved')->count();
        $employeesPendingLeave += $employee->leaves()->where('leave_status', \App\Enums\LeaveStatus::Pending->value)->count();
    }

    $presentToday = $employees->filter(function($employee) {
        return $employee->attendance()->whereDate('date', now()->toDateString())->where('attendance_status', 'Present')->exists();
    })->count();
} else {
    $presentToday = 0;
}
@endphp

@section('page-content')
<style>
/* Segmented Control Tabs */
.view-tabs {
    display: flex;
    gap: 6px;
    margin-bottom: 24px;
    background: #f7f6ff;
    padding: 6px;
    border-radius: 10px;
    border: 1px solid #eceaf8;
    width: fit-content;
}
.view-tab {
    padding: 8px 18px;
    border: none;
    background: transparent;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    color: #6b6a8a;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 6px;
}
.view-tab:hover {
    color: #0b044d;
}
.view-tab.active {
    background: #fff;
    color: #0b044d;
    box-shadow: 0 2px 5px rgba(11,4,77,0.06);
}

/* Compact Quick Actions */
.quick-actions-row {
    display: flex;
    gap: 12px;
    margin-bottom: 24px;
    flex-wrap: wrap;
}
.qa-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    background: #fff;
    border: 1px solid #e5e4f0;
    border-radius: 10px;
    color: #0b044d;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
    flex: 1;
    min-width: 200px;
}
.qa-btn:hover {
    border-color: #0b044d;
    box-shadow: 0 4px 12px rgba(11, 4, 77, 0.05);
    transform: translateY(-2px);
}
.qa-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 8px;
}

/* Tab Content Visibility */
.tab-pane {
    display: none;
    animation: fadeIn 0.3s ease;
}
.tab-pane.active {
    display: block;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(4px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<div class="welcome-banner">
    <div class="banner-left">
        <div class="banner-icon">
            <svg width="22" height="22" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </div>
        <div>
            <h2>Welcome back, {{ auth()->user()->name }}!</h2>
            <p>{{ config('app.date', now()->format('l, F j, Y')) }} &nbsp;·&nbsp; PRIME HRIS Dashboard</p>
        </div>
    </div>
    <div class="banner-right">
        <span class="banner-badge">
            <span class="banner-badge-dot"></span>
            System Online
        </span>
        <span class="banner-badge outline">FY {{ config('app.year', date('Y')) }}</span>
    </div>
</div>

<!-- Compact Quick Actions -->
<div class="quick-actions-row">
    <a href="{{ route('employees.index') }}" class="qa-btn">
        <div class="qa-icon" style="background:#f0effe"><svg width="16" height="16" fill="none" stroke="#0b044d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
        Manage Employees
    </a>
    <a href="{{ route('attendances.index') }}" class="qa-btn">
        <div class="qa-icon" style="background:#e8f9ef"><svg width="16" height="16" fill="none" stroke="#15803d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
        Track Attendance
    </a>
    <a href="{{ route('payroll.index') }}" class="qa-btn">
        <div class="qa-icon" style="background:#fdf0ef"><svg width="16" height="16" viewBox="0 0 24 24" fill="#8e1e18" stroke="none"><text x="3" y="19" font-size="17" font-weight="bold" font-family="Arial, sans-serif">₱</text></svg></div>
        Process Payroll
    </a>
    <a href="{{ route('employee_leaves.index') }}" class="qa-btn">
        <div class="qa-icon" style="background:#fefce8"><svg width="16" height="16" fill="none" stroke="#a16207" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
        Manage Leaves
    </a>
</div>

<!-- Category View Tabs -->
<div class="view-tabs">
    <button class="view-tab active" onclick="switchView('overview', this)">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
        Overview
    </button>
    <button class="view-tab" onclick="switchView('directory', this)">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        Employee Directory
    </button>
    <button class="view-tab" onclick="switchView('leaves', this)">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        Leave Requests
    </button>
</div>

<!-- ================== TAB: OVERVIEW ================== -->
<div id="view-overview" class="tab-pane active">
    <div class="stats-grid stats-grid-4">
        <div class="stat-card">
            <div class="stat-top">
                <p class="stat-label">Total Employees</p>
                <div class="stat-icon-wrap" style="background:#f0effe">
                    <svg width="17" height="17" fill="none" stroke="#0b044d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
            </div>
            <p class="stat-value">{{ $totalEmployees }}</p>
            <div class="stat-footer">
                <span class="stat-dot" style="background:#22c55e"></span>
                <p class="stat-sub">Active employees</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-top">
                <p class="stat-label">Present Today</p>
                <div class="stat-icon-wrap" style="background:#e8f9ef">
                    <svg width="17" height="17" fill="none" stroke="#15803d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="m9 16 2 2 4-4"/></svg>
                </div>
            </div>
            <p class="stat-value">{{ $presentToday }}</p>
            <div class="stat-footer">
                <span class="stat-dot" style="background:#22c55e"></span>
                <p class="stat-sub">Number of employees</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-top">
                <p class="stat-label">On Leave</p>
                <div class="stat-icon-wrap" style="background:#fefce8">
                    <svg width="17" height="17" fill="none" stroke="#a16207" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                </div>
            </div>
            <p class="stat-value">{{ $employeesOnLeave }}</p>
            <div class="stat-footer">
                <span class="stat-dot" style="background:#f59e0b"></span>
                <p class="stat-sub">{{ $employeesPendingLeave }} pending approval</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-top">
                <p class="stat-label">Monthly Payroll</p>
                <div class="stat-icon-wrap" style="background:#fdf0ef">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="#8e1e18" stroke="none"><text x="3" y="19" font-size="17" font-weight="bold" font-family="Arial, sans-serif">₱</text></svg>
                </div>
            </div>
            <p class="stat-value" style="font-size:20px">₱{{ number_format($monthlyPayroll, 2) }}</p>
            <div class="stat-footer">
                <span class="stat-dot" style="background:#0b044d"></span>
                <p class="stat-sub">For {{ config('app.month', date('F')) }}</p>
            </div>
        </div>
    </div>
</div>

<!-- ================== TAB: EMPLOYEE DIRECTORY ================== -->
<div id="view-directory" class="tab-pane">
    <div class="table-section">
        <div class="table-header">
            <div>
                <p class="table-title">Employee Directory</p>
                <p class="table-sub">All active government personnel</p>
            </div>
            <div class="table-actions">
                <a href="{{ route('employees.index') }}" class="btn-export">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    Manage Employees
                </a>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="payroll-table" id="attendance-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Department</th>
                        <th>Employment Type</th>
                        <th>Date Hired</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @if(isset($employees) && count($employees) > 0)
                    @foreach ($employees as $employee)
                        <tr>
                            <td>
                                <div class="emp-cell">
                                    <div class="emp-avatar" style="background:{{ ['#0b044d','#8e1e18','#15803d','#a16207','#7c3aed'][($employee->id % 5)] }}">
                                        {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="emp-name">{{ $employee->first_name }} {{ $employee->last_name }}</p>
                                        <p class="emp-id">EMP-{{ str_pad($employee->id, 3, '0', STR_PAD_LEFT) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td><span class="position-cell">{{ $employee->position->title ?? 'N/A' }}</span></td>
                            <td><span class="dept-tag">{{ $employee->department->name ?? 'N/A' }}</span></td>
                            <td>
                                <span class="dept-tag" style="background:{{ $employee->employment_type === 'Permanent' ? '#e8f9ef' : '#fefce8' }};color:{{ $employee->employment_type === 'Permanent' ? '#15803d' : '#a16207' }};border-color:{{ $employee->employment_type === 'Permanent' ? '#bbf7d0' : '#fde68a' }}">
                                    {{ $employee->employment_type }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($employee->created_at)->format('M d, Y') }}</td>
                            <td>
                                @if($employee->is_active)
                                    <span class="badge-status processed">Active</span>
                                @else
                                    <span class="badge-status on-hold">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="row-actions">
                                    <a href="{{ route('employees.show', $employee) }}" class="btn-view">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        View
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>

        @if(isset($employees) && method_exists($employees, 'hasPages') && $employees->hasPages())
        <div class="table-footer">
            <span>Showing <strong>{{ $employees->firstItem() }}–{{ $employees->lastItem() }}</strong> of <strong>{{ $employees->total() }}</strong> employees</span>
            <div class="pagination">
                @if($employees->onFirstPage())
                    <button class="page-btn" disabled>‹</button>
                @else
                    <a href="{{ $employees->previousPageUrl() }}" class="page-btn">‹</a>
                @endif

                @foreach($employees->getUrlRange(1, $employees->lastPage()) as $page => $url)
                    <a href="{{ $url }}" class="page-btn {{ $page == $employees->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                @endforeach

                @if($employees->hasMorePages())
                    <a href="{{ $employees->nextPageUrl() }}" class="page-btn">›</a>
                @else
                    <button class="page-btn" disabled>›</button>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- ================== TAB: LEAVES ================== -->
<div id="view-leaves" class="tab-pane">
    <div class="table-section">
        <div class="table-header">
            <div>
                <p class="table-title">Recent Leave Requests</p>
                <p class="table-sub">Requires your approval</p>
            </div>
            <a href="{{ route('employee_leaves.index') }}" class="btn-export">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                Manage Leaves
            </a>
        </div>
        <div class="table-wrapper">
            <table class="payroll-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Leave Type</th>
                        <th>Duration</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                @if(isset($employeeLeaves) && count($employeeLeaves) > 0)
                    @foreach($employeeLeaves as $leave)
                        <tr>
                            <td><span style="font-size:12px;color:#9999bb">{{ $loop->iteration }}</span></td>
                            <td>
                                <div class="emp-cell">
                                    <div class="emp-avatar" style="background:{{ ['#0b044d','#8e1e18','#15803d','#a16207','#7c3aed'][($leave->employee->id % 5)] }}">
                                        {{ strtoupper(substr($leave->employee->first_name, 0, 1) . substr($leave->employee->last_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="emp-name">{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</p>
                                        <p class="emp-id">EMP-{{ str_pad($leave->employee->id, 3, '0', STR_PAD_LEFT) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $leave->leaveType->leave_type ?? 'N/A' }}</td>
                            <td><span class="dept-tag" style="background:#fefce8;color:#a16207;border-color:#fde68a">{{ $leave->leave_duration }} days</span></td>
                            <td>
                                @if($leave->leave_status === 'Approved')
                                    <span class="badge-status processed">Approved</span>
                                @elseif($leave->leave_status === 'Pending')
                                    <span class="badge-status pending">Pending</span>
                                @else
                                    <span class="badge-status on-hold">{{ $leave->leave_status }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="empty-state" style="text-align: center; padding: 40px;">
                            <div style="display: flex; justify-content: center; margin-bottom: 12px;">
                                <svg width="48" height="48" fill="none" stroke="#d9d9ee" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            </div>
                            <p style="font-size:14px;color:#9999bb;">No leave records found</p>
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
function switchView(viewId, btn) {
    // Hide all tab panes
    document.querySelectorAll('.tab-pane').forEach(el => el.classList.remove('active'));
    // Remove active class from all buttons
    document.querySelectorAll('.view-tab').forEach(el => el.classList.remove('active'));
    
    // Show active tab
    document.getElementById('view-' + viewId).classList.add('active');
    // Set active button
    btn.classList.add('active');
}

$(function () {
    if ($.fn.DataTable) {
        $('#attendance-table').DataTable({
            columnDefs: [{ orderable: false, targets: [0, 6] }],
            pageLength: 5,
            lengthMenu: [5, 10],
            language: { search: 'Search:', lengthMenu: 'Show _MENU_ Entries', emptyTable: 'No employees found' },
        });
    }
});
</script>
@endpush

@endsection
