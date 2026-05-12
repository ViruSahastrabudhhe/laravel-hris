@extends('layouts.admin')
@php $hideChat = true; @endphp

@php
    use App\Enums\LeaveStatus;

    $totalLeaves = $leaveRequests->count();
    $totalApproved = $leaveRequests->where('leave_status', \App\Enums\LeaveStatus::Approved->value)->count();
    $totalPending = $leaveRequests->where('leave_status', \App\Enums\LeaveStatus::Pending->value)->count();
    $totalLeaveDays = 0;

    foreach ($leaveRequests as $leave) {
        $totalLeaveDays += $leave->leave_duration;
    }

    $leaveTypes = \App\Models\LeaveType::all();
    $totalLeaveTypes = $leaveTypes->count();
    $activeLeaveTypes = $leaveTypes->where('is_active', true)->count();
    $inactiveLeaveTypes = $totalLeaveTypes - $activeLeaveTypes;
    $totalLeaveTypeDays = $leaveTypes->sum('days_of_leave');

    $holidays = \App\Models\Holiday::all();
    $totalHolidays = $holidays->count();
    $totalHolidayDays = $holidays->sum('holiday_duration');
    $averageHolidayDuration = $totalHolidays ? round($totalHolidayDays / $totalHolidays, 1) : 0;

    $allCompensations = \App\Models\Compensation::all();
@endphp

@section('page-content')

<div class="welcome-banner">
    <div class="banner-left">
        <div class="banner-icon">
            <svg width="22" height="22" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
        </div>
        <div>
            <h2>Leave & Benefits</h2>
            <p>{{ config('app.carbon_date') }} &nbsp;·&nbsp; Employee Leave & Benefits</p>
        </div>
    </div>
    <div class="banner-right">
        <div class="recruit-search-wrap">
            <svg width="15" height="15" fill="none" stroke="rgba(255,255,255,0.5)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" id="banner-search" placeholder="Search..." class="recruit-search" oninput="$('.tab-pane.active table').DataTable().search(this.value).draw()">
        </div>
    </div>
</div>
<div id="stats-leaves" class="stats-grid stats-grid-4">
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Total Leave Requests</p>
            <div class="stat-icon-wrap" style="background:#f0effe">
                <svg width="17" height="17" fill="none" stroke="#0b044d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $totalLeaves }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#22c55e"></span>
            <p class="stat-sub">All time</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Approved</p>
            <div class="stat-icon-wrap" style="background:#e8f9ef">
                <svg width="17" height="17" fill="none" stroke="#15803d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $totalApproved }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#22c55e"></span>
            <p class="stat-sub">This period</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Pending Approval</p>
            <div class="stat-icon-wrap" style="background:#fefce8">
                <svg width="17" height="17" fill="none" stroke="#a16207" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $totalPending }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#f59e0b"></span>
            <p class="stat-sub">Needs action</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Total Leave Days</p>
            <div class="stat-icon-wrap" style="background:#fdf0ef">
                <svg width="17" height="17" fill="none" stroke="#8e1e18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $totalLeaveDays }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#0b044d"></span>
            <p class="stat-sub">Across all employees</p>
        </div>
    </div>
</div>

<div id="stats-benefits" class="stats-grid stats-grid-4" style="display:none">
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Total Leave Types</p>
            <div class="stat-icon-wrap" style="background:#f0effe">
                <svg width="17" height="17" fill="none" stroke="#0b044d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $totalLeaveTypes }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#22c55e"></span>
            <p class="stat-sub">Configured leave categories</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Active Types</p>
            <div class="stat-icon-wrap" style="background:#e8f9ef">
                <svg width="17" height="17" fill="none" stroke="#15803d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $activeLeaveTypes }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#22c55e"></span>
            <p class="stat-sub">Currently active</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Inactive Types</p>
            <div class="stat-icon-wrap" style="background:#fefce8">
                <svg width="17" height="17" fill="none" stroke="#a16207" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $inactiveLeaveTypes }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#f59e0b"></span>
            <p class="stat-sub">Disabled categories</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Total Allowance Days</p>
            <div class="stat-icon-wrap" style="background:#fdf0ef">
                <svg width="17" height="17" fill="none" stroke="#8e1e18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $totalLeaveTypeDays }} days</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#0b044d"></span>
            <p class="stat-sub">Total leave allowance</p>
        </div>
    </div>
</div>

<div class="view-tabs" hidden>
    <button class="view-tab active" onclick="switchView('leaves', this)">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
        Leaves
    </button>
    <button class="view-tab" onclick="switchView('leave-types', this)">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        Benefits
    </button>
</div>

{{-- Tabs --}}
<div style="display: flex; gap: 4px; margin-bottom: 20px; border-bottom: 1.5px solid #eceaf8; padding-bottom: 0;">
    <button class="tab-btn active" onclick="switchView('leaves', this)">Leave Requests</button>
    <button class="tab-btn" onclick="switchView('benefits', this)">Benefits Summary</button>
</div>

<div id="tab-leaves" class="tab-pane active">
    <div class="table-section">
        <div class="table-header">
            <div>
                <p class="table-title">Leave Management</p>
                <p class="table-sub">Track and manage Leave Request requests</p>
            </div>
            <div class="table-actions" style="gap: 10px;">
                <select class="filter-select" id="dept-filter">
                    <option value="">All Departments</option>
                    @foreach(\App\Models\Department::all() as $dept)
                        <option value="{{ $dept->name }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
                <select class="filter-select" id="status-filter">
                    <option value="">All Status</option>
                    <option value="Approved">Approved</option>
                    <option value="Pending">Pending</option>
                </select>
                <a href="{{ route('leave_requests.archive') }}" class="btn-export">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="m9 11 3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                    View Archive
                </a>
                <button onclick="openLeaveCreateModal()" class="modal-btn-primary">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Apply on Behalf
                </button>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="payroll-table" id="leaves-table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Leave Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($leaveRequests as $leave)
                    <tr>
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
                        <td><span class="dept-tag">{{ $leave->employee->department->name }}</span></td>
                        <td>{{ $leave->leaveType->leave_type }}</td>
                        <td><span style="font-size:12.5px;color:#5a5888">{{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}</span></td>
                        <td><span style="font-size:12.5px;color:#5a5888">{{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}</span></td>
                        <td><span class="dept-tag" style="background:#fefce8;color:#a16207;border-color:#fde68a">{{ $leave->leave_duration }} {{ $leave->leave_duration == 1 ? ' day' : ' days' }}</span></td>
                        <td>
                            @if($leave->leave_status === LeaveStatus::Approved->value)
                                <span class="badge-status processed">Approved</span>
                            @elseif($leave->leave_status === LeaveStatus::Pending->value)
                                <span class="badge-status pending">Pending</span>
                            @else
                                <span class="badge-status on-hold">{{ $leave->leave_status }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="row-actions">
                                <button type="button" class="btn-edit" onclick="openLeaveEditModal(
                                    '{{ $leave->id }}',
                                    '{{ $leave->employee_id }}',
                                    '{{ $leave->leave_type_id }}',
                                    '{{ $leave->start_date }}',
                                    '{{ $leave->end_date }}',
                                    '{{ addslashes($leave->leave_reason) }}',
                                    '{{ $leave->leave_status }}',
                                    '{{ addslashes($leave->decline_reason) }}'
                                )">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </button>
                                @if ($leave->leave_status == \App\Enums\LeaveStatus::Approved->value || $leave->leave_status == \App\Enums\LeaveStatus::Declined->value)
                                <form action="{{ route('leave_requests.destroy', $leave) }}" method="POST" style="display:inline" onsubmit="return confirm('Archive this leave request?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger" style="display:inline-flex;align-items:center;gap:4px">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('leave_requests.approve', $leave) }}" method="post" style="display:inline" onsubmit="return confirm('Approve this leave request?')">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn-success" style="display: inline-flex; align-items: center; gap: 4px;">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                    </button>
                                </form>
                                <button type="button" class="btn-danger" style="display:inline-flex;align-items:center;gap:4px" onclick="openDenyModal('{{ route('leave_requests.deny', $leave) }}', '{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}')">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="tab-benefits" class="tab-pane">
    <div class="table-section">
        <div class="table-header">
            <div>
                <p class="table-title">Benefits Summary — {{ config('app.carbon_day_month') }}</p>
                <p class="table-sub">Earnings · Deductions · Leave Credits</p>
            </div>
            <div class="table-actions">
                <button class="btn-export">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Export
                </button>
                <button onclick="openBenefitCreateModal()" class="modal-btn-primary">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Request Benefit
                </button>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="payroll-table" id="benefits-table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Earnings</th>
                        <th>Deductions</th>
                        <th>Leave Credits</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($employees as $employee)
                    @php
                        $earnings = $employee->employeeCompensation->filter(function($c) {
                            return $c->compensation && $c->compensation->type === \App\Enums\CompensationType::Earning->value;
                        })->sum('amount');

                        $deductions = $employee->employeeCompensation->filter(function($c) {
                            return $c->compensation && $c->compensation->type === \App\Enums\CompensationType::Deduction->value;
                        })->sum('amount');

                        $sickLeave = $employee->employeeLeaveBalance->where('type', 'Sick')->first()->amount ?? 0;
                        $vacationLeave = $employee->employeeLeaveBalance->where('type', 'Vacation')->first()->amount ?? 0;
                    @endphp
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
                        <td class="earnings" style="color: #15803d;">₱{{ number_format($earnings, 2) }}</td>
                        <td class="deduction" style="color: #8e1e18;">₱{{ number_format($deductions, 2) }}</td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <span class="dept-tag" style="background:#e8f9ef;color:#15803d;border-color:#bcf0da" title="Sick Leave">SL: {{ $sickLeave }}</span>
                                <span class="dept-tag" style="background:#f0effe;color:#0b044d;border-color:#e0dff5" title="Vacation Leave">VL: {{ $vacationLeave }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="row-actions">
                                <button type="button" class="btn-view"
                                    data-id="{{ $employee->id }}"
                                    data-first_name="{{ $employee->first_name }}"
                                    data-last_name="{{ $employee->last_name }}"
                                    data-position="{{ $employee->position->title }}"
                                    data-department="{{ $employee->department->name }}"
                                    data-earnings="{{ $earnings }}"
                                    data-deductions="{{ $deductions }}"
                                    data-sick-leave="{{ $sickLeave }}"
                                    data-vacation-leave="{{ $vacationLeave }}"
                                    data-compensations='@json($employee->employeeCompensation)'
                                    data-earnings_list='@json(
                                        $employee->employeeCompensation
                                            ->where("type", "Earning")
                                            ->values()
                                    )'
                                    data-deductions_list='@json(
                                        $employee->employeeCompensation
                                            ->where("type", "Deduction")
                                            ->values()
                                    )'
                                    onclick="openBenefitViewModal(this)"
                                >
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Deny Modal --}}
<div class="modal-overlay" id="deny-modal" style="display:none">
    <div class="modal-box" style="max-width:460px">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">LEAVE REQUEST</span>
                <h3 class="modal-title">Deny Leave Request</h3>
                <p class="modal-sub" id="deny-modal-sub">Provide a reason for declining</p>
            </div>
            <button class="modal-close" onclick="closeDenyModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form id="deny-form" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="auth-field">
                    <label style="font-size:12px;font-weight:600;color:#0b044d">Decline Reason <span style="color:#dc2626">*</span></label>
                    <textarea name="decline_reason" rows="3" required placeholder="Enter reason for declining this leave request..." style="padding:10px 13px;border:1.5px solid #e0dff5;border-radius:9px;font-size:13px;color:#1a1a3a;background:#fafafe;outline:none;width:100%;box-sizing:border-box;font-family:'Poppins',sans-serif;resize:vertical;margin-top:6px"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn-ghost" onclick="closeDenyModal()">Cancel</button>
                <button type="submit" class="modal-btn-primary" style="background:#8e1e18">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                    Confirm Deny
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Leave Create Modal --}}
<div class="modal-overlay" id="leave-create-modal" style="display:none">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">LEAVE REQUESTS</span>
                <h3 class="modal-title">File New Leave</h3>
            </div>
            <button class="modal-close" onclick="closeLeaveCreateModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form action="{{ route('leave_requests.store') }}" method="POST">
            @csrf
            <div class="modal-body" style="max-height:60vh;overflow-y:auto;">
                <div class="form-field">
                    <label>Employee <span style="color:#dc2626">*</span></label>
                    <select name="employee_id" id="employee" required>
                        <option value="" selected>Select an employee</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->department->name }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label>Leave Type <span style="color:#dc2626">*</span></label>
                    <select name="leave_type_id" required>
                        <option value="">Select leave type</option>
                        @forelse($leaveTypes as $leaveType)
                        <option value="{{ $leaveType->id }}" {{ old('leave_type_id') == $leaveType->id ? 'selected' : '' }}>{{ $leaveType->leave_type }}</option>
                        @empty
                        @endforelse
                    </select>
                    @if($leaveTypes->isEmpty())
                    <span style="font-size:11.5px;color:#8e1e18">No leave types available. <a href="{{ route('leave_types.create') }}" style="color:#8e1e18;font-weight:600">Add one here.</a></span>
                    @endif
                </div>
                <div class="form-field">
                    <div class="auth-field">
                        <label>Start Date <span style="color:#dc2626">*</span></label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}" required>
                    </div>
                    <div class="auth-field">
                        <label>End Date <span style="color:#dc2626">*</span></label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}" required>
                    </div>
                </div>
                <div class="form-field">
                    <label>Reason <span style="color:#dc2626">*</span></label>
                    <textarea name="leave_reason" rows="3" style="padding:10px 13px;border:1.5px solid #e0dff5;border-radius:9px;font-size:13.5px;color:#1a1a3a;background:#fafafe;outline:none;width:100%;box-sizing:border-box;font-family:'Poppins',sans-serif;resize:vertical" placeholder="Reason for leave" required>{{ old('leave_reason') }}</textarea>
                </div>
                <div class="form-field">
                    <label>Status <span style="color:#dc2626">*</span></label>
                    <select name="leave_status" id="leave_status" required>
                        <option value="">Select status</option>
                        @foreach($leaveStatuses as $leaveStatus)
                        <option value="{{ $leaveStatus->name }}" {{ old('leave_status') == $leaveStatus->name ? 'selected' : '' }}>{{ $leaveStatus->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <div id="decline_reason_wrap" style="display:none">
                        <div class="auth-field">
                            <label>Decline Reason</label>
                            <textarea name="decline_reason" id="decline_reason_input" rows="2" style="padding:10px 13px;border:1.5px solid #e0dff5;border-radius:9px;font-size:13.5px;color:#1a1a3a;background:#fafafe;outline:none;width:100%;box-sizing:border-box;font-family:'Poppins',sans-serif;resize:vertical" placeholder="Reason for declining">{{ old('decline_reason') }}</textarea>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn-ghost" onclick="closeLeaveCreateModal()">Cancel</button>
                <button type="submit" class="modal-btn-primary">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    File Leave Request
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Leave Edit Modal --}}
<div class="modal-overlay" id="leave-edit-modal" style="display:none">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">LEAVE REQUESTS</span>
                <h3 class="modal-title">Edit Leave Request</h3>
            </div>
            <button class="modal-close" onclick="closeLeaveEditModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form id="leave-edit-form" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body" style="max-height:60vh;overflow-y:auto;">
                <div class="form-field">
                    <label>Employee <span style="color:#dc2626">*</span></label>
                    <select name="employee_id" id="edit-employee" required style="color:#9999bb" disabled>
                        <option value="">Select an employee</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->department->name }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label>Leave Type <span style="color:#dc2626">*</span></label>
                    <select name="leave_type_id" id="edit-leave-type" required>
                        <option value="">Select leave type</option>
                        @forelse($leaveTypes as $leaveType)
                            <option value="{{ $leaveType->id }}">{{ $leaveType->leave_type }}</option>
                        @empty
                        @endforelse
                    </select>
                </div>
                <div class="form-field">
                    <div class="auth-field">
                        <label>Start Date <span style="color:#dc2626">*</span></label>
                        <input type="date" name="start_date" id="edit-start-date" required>
                    </div>
                    <div class="auth-field">
                        <label>End Date <span style="color:#dc2626">*</span></label>
                        <input type="date" name="end_date" id="edit-end-date" required>
                    </div>
                </div>
                <div class="form-field">
                    <label>Reason <span style="color:#dc2626">*</span></label>
                    <textarea name="leave_reason" id="edit-leave-reason" rows="3" style="padding:10px 13px;border:1.5px solid #e0dff5;border-radius:9px;font-size:13.5px;color:#1a1a3a;background:#fafafe;outline:none;width:100%;box-sizing:border-box;font-family:'Poppins',sans-serif;resize:vertical" required></textarea>
                </div>
                <div class="form-field">
                    <div id="edit-decline-reason-wrap" style="display:none">
                        <div class="auth-field">
                            <label>Decline Reason</label>
                            <textarea name="decline_reason" id="edit-decline-reason" rows="2" style="padding:10px 13px;border:1.5px solid #e0dff5;border-radius:9px;font-size:13.5px;color:#1a1a3a;background:#fafafe;outline:none;width:100%;box-sizing:border-box;font-family:'Poppins',sans-serif;resize:vertical"></textarea>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn-ghost" onclick="closeLeaveEditModal()">Cancel</button>
                <button type="submit" class="modal-btn-primary">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Update Leave Request
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Benefit Request Modal --}}
<div class="modal-overlay" id="benefit-create-modal" style="display:none">
    <div class="modal-box" style="max-width:460px" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">BENEFITS & COMPENSATIONS</span>
                <h3 class="modal-title">Request New Benefit</h3>
                <p class="modal-sub">Assign a new benefit or compensation to an employee</p>
            </div>
            <button class="modal-close" onclick="closeBenefitCreateModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form action="{{ route('employee_compensations.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-field">
                    <label>Employee <span style="color:#dc2626">*</span></label>
                    <select name="employee_id" required>
                        <option value="">Select an employee</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label>Category <span style="color:#dc2626">*</span></label>
                    <select name="category" id="benefit-category" required>
                        <option value="">Select category</option>
                        <option value="Earning">Earning</option>
                        <option value="Deduction">Deduction</option>
                    </select>
                </div>
                <div class="form-field">
                    <label>Benefit Type <span style="color:#dc2626">*</span></label>
                    <select name="compensation_id" id="benefit-type" required disabled>
                        <option value="">Select benefit type</option>
                    </select>
                </div>
                <div class="form-field">
                    <label>Amount (₱) <span style="color:#dc2626">*</span></label>
                    <input type="number" name="amount" step="0.01" placeholder="0.00" required style="padding:10px 13px;border:1.5px solid #e0dff5;border-radius:9px;font-size:13.5px;color:#1a1a3a;background:#fafafe;outline:none;width:100%;box-sizing:border-box;font-family:'Poppins',sans-serif;">
                </div>
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn-ghost" onclick="closeBenefitCreateModal()">Cancel</button>
                <button type="submit" class="modal-btn-primary">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Confirm Request
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Benefit View Modal --}}
<div class="modal-overlay" id="benefit-view-modal" style="display:none">
    <div class="modal-box" style="max-width:460px" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">BENEFIT RECORD</span>
                <h3 class="modal-title" id="benefit-view-modal-name">Employee Name</h3>
                <p class="modal-sub" id="benefit-view-modal-position">Position · Department</p>
            </div>
            <button class="modal-close" onclick="closeBenefitViewModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="modal-body">
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px; padding: 16px; background: #f7f6ff; border-radius: 12px;">
                <div class="emp-avatar" id="benefit-view-modal-avatar" style="width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 700; color: #fff; background: #0b044d;">
                    MS
                </div>
                <div>
                    <p id="benefit-view-modal-emp-id" style="font-size: 11px; color: #9999bb; margin: 0 0 4px;">PGS-0000</p>
                </div>
            </div>

            <p style="font-size: 10.5px; font-weight: 700; color: #9999bb; letter-spacing: 1px; margin-bottom: 12px; margin-top: 12px;">EARNINGS</p>
            <ol id="earnings-list"></ol>

            <p style="font-size: 10.5px; font-weight: 700; color: #9999bb; letter-spacing: 1px; margin-bottom: 12px; margin-top: 12px;">DEDUCTIONS</p>
            <ol id="deductions-list"></ol>

            <p style="font-size: 10.5px; font-weight: 700; color: #9999bb; letter-spacing: 1px; margin: 16px 0 12px;">LEAVE CREDITS</p>
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f7f6ff"><span style="font-size:12.5px;color:#5a5888">Sick Leave</span><strong style="font-size:13px;color:#0b044d" id="benefit-view-modal-sick-leave">0 days</strong></div>
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f7f6ff"><span style="font-size:12.5px;color:#5a5888">Vacation Leave</span><strong style="font-size:13px;color:#0b044d" id="benefit-view-modal-vacation-leave">0 days</strong></div>

            <div style="margin-top: 1rem; padding: 12px; background: #f7f6ff; border-radius: 10px;">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <span style="font-size: 11px; font-weight: 700; color: #9999bb; letter-spacing: 1px;">
                        TOTAL EARNINGS
                    </span>
                    <strong style="font-size: 18px; color: #15803d;" id="benefit-view-modal-earnings">
                        ₱0.00
                    </strong>
                </div>

                <div style="display:flex; justify-content:space-between; align-items:center; margin-top: 1rem;">
                    <span style="font-size: 11px; font-weight: 700; color: #9999bb; letter-spacing: 1px;">
                        TOTAL DEDUCTIONS
                    </span>
                    <strong style="font-size: 18px; color: #8e1e18;" id="benefit-view-modal-deductions">
                        ₱0.00
                    </strong>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="modal-btn-ghost" onclick="closeBenefitViewModal()">Close</button>
        </div>
    </div>
</div>
{{-- Edit Compensation Modal --}}
<div class="modal-overlay" id="compensation-edit-modal" style="display:none">
    <div class="modal-box" style="max-width:420px" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">EMPLOYEE COMPENSATION</span>
                <h3 class="modal-title">Edit Compensation</h3>
                <p class="modal-sub" id="comp-edit-modal-sub">Update the amount</p>
            </div>
            <button class="modal-close" onclick="closeCompensationEditModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form id="compensation-edit-form" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-field">
                    <label style="font-size:12px;font-weight:600;color:#0b044d">Compensation</label>
                    <input type="text" id="comp-edit-name" readonly
                        style="padding:10px 13px;border:1.5px solid #e0dff5;border-radius:9px;font-size:13.5px;color:#9999bb;background:#f7f6ff;outline:none;width:100%;box-sizing:border-box;font-family:'Poppins',sans-serif;">
                </div>
                <div class="form-field">
                    <label style="font-size:12px;font-weight:600;color:#0b044d">Amount (₱) <span style="color:#dc2626">*</span></label>
                    <input type="number" name="amount" id="comp-edit-amount" step="0.01" placeholder="0.00" required
                        style="padding:10px 13px;border:1.5px solid #e0dff5;border-radius:9px;font-size:13.5px;color:#1a1a3a;background:#fafafe;outline:none;width:100%;box-sizing:border-box;font-family:'Poppins',sans-serif;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn-ghost" onclick="closeCompensationEditModal()">Cancel</button>
                <button type="submit" class="modal-btn-primary">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Delete Compensation Confirmation Modal --}}
<div class="modal-overlay" id="compensation-delete-modal" style="display:none">
    <div class="modal-box" style="max-width:420px" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">EMPLOYEE COMPENSATION</span>
                <h3 class="modal-title" style="color:#8e1e18">Delete Compensation</h3>
                <p class="modal-sub" id="comp-delete-modal-sub">This action cannot be undone.</p>
            </div>
            <button class="modal-close" onclick="closeCompensationDeleteModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="modal-body">
            <div style="display:flex;gap:14px;align-items:flex-start;padding:16px;background:#fdf0ef;border-radius:10px;border:1px solid #fca5a5">
                <svg width="20" height="20" fill="none" stroke="#8e1e18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <p style="font-size:13px;color:#5a1a16;line-height:1.6;margin:0">Are you sure you want to delete <strong id="comp-delete-name">this compensation</strong>? This record will be permanently removed.</p>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="modal-btn-ghost" onclick="closeCompensationDeleteModal()">Cancel</button>
            <form id="compensation-delete-form" method="POST" style="display:inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="modal-btn-primary" style="background:#8e1e18">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    Confirm Delete
                </button>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const q = $('#banner-search').val();
    if (q) {
        $('#tab-' + viewId + ' table').DataTable().search(q).draw();
    }

    let leaveTable;
    let benefitsTable;

    $(function () {
        if ($('#leaves-table').length) {
            $('#leaves-table').DataTable({
                columnDefs: [{ orderable: false, targets: [5] }],
                pageLength: 25,
                language: { search: 'Search:', lengthMenu: 'Show _MENU_ entries', emptyTable: 'No leave requests found' },
                dom: 'rtip',
            });
        }

        if ($('#benefits-table').length) {
            $('#benefits-table').DataTable({
                columnDefs: [{ orderable: false, targets: [4] }],
                pageLength: 25,
                language: { search: 'Search:', lengthMenu: 'Show _MENU_ entries', emptyTable: 'No benefits found' },
                dom: 'rtip',
            });
        }

        $('#leave-search').on('keyup', function() {
            const q = this.value;
            const activeTab = document.querySelector('.tab-pane.active');
            if (activeTab && activeTab.id === 'tab-benefits') {
                benefitsTable.search(q).draw();
            } else {
                leaveTable.search(q).draw();
            }
        });

        $('#dept-filter').on('change', function() {
            leaveTable.column(1).search(this.value).draw();
        });

        $('#status-filter').on('change', function() {
            leaveTable.column(6).search(this.value).draw();
        });
    });

    function switchView(viewId, btn) {
        if (btn.classList.contains('active')) return;

        document.querySelectorAll('.tab-pane').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));

        document.getElementById('tab-' + viewId).classList.add('active');
        btn.classList.add('active');

        $('#leave-search').val('');

        if (leaveTable) leaveTable.search('').draw(false);
        if (benefitsTable) benefitsTable.search('').draw(false);

        document.getElementById('stats-leaves').style.display = viewId === 'leaves' ? 'grid' : 'none';
        document.getElementById('stats-benefits').style.display = viewId === 'benefits' ? 'grid' : 'none';

        setTimeout(() => {
            if (viewId === 'leaves' && leaveTable) {
                leaveTable.columns.adjust();
            }

            if (viewId === 'benefits' && benefitsTable) {
                benefitsTable.columns.adjust();
            }
        }, 50);
    }

    function openDenyModal(action, employeeName) {
        document.getElementById('deny-form').action = action;
        document.getElementById('deny-modal-sub').textContent = 'Provide a reason for declining ' + employeeName + '\'s request';
        document.getElementById('deny-modal').style.display = 'flex';
    }

    function closeDenyModal() {
        document.getElementById('deny-modal').style.display = 'none';
        document.getElementById('deny-form').reset();
    }

    function openLeaveCreateModal() { document.getElementById('leave-create-modal').style.display = 'flex'; }
    function closeLeaveCreateModal() { document.getElementById('leave-create-modal').style.display = 'none'; }

    function openLeaveEditModal(id, employeeId, leaveTypeId, startDate, endDate, reason, status, declineReason) {
        var url = "{{ route('leave_requests.update', ':id') }}".replace(':id', id);
        document.getElementById('leave-edit-form').action = url;
        document.getElementById('edit-employee').value = employeeId;
        document.getElementById('edit-leave-type').value = leaveTypeId;
        document.getElementById('edit-start-date').value = startDate;
        document.getElementById('edit-end-date').value = endDate;
        document.getElementById('edit-leave-reason').value = reason;
        document.getElementById('edit-decline-reason').value = declineReason || '';
        document.getElementById('edit-decline-reason-wrap').style.display = status === 'Declined' ? 'block' : 'none';
        document.getElementById('leave-edit-modal').style.display = 'flex';
    }
    function closeLeaveEditModal() { document.getElementById('leave-edit-modal').style.display = 'none'; }

    function openBenefitCreateModal() {
        $('#benefit-category').val('');
        $('#benefit-type')
            .html('<option value="">Select benefit type</option>')
            .prop('disabled', true);

        const allCompensations = @json($allCompensations);
        console.log(@json($allCompensations));

        $(document).on('change', '#benefit-category', function () {
            const category = $(this).val();
            const $typeSelect = $('#benefit-type');

            console.log('Selected category:', category); // DEBUG

            $typeSelect.html('<option value="">Select benefit type</option>');

            if (!category) {
                $typeSelect.prop('disabled', true);
                return;
            }

            const filtered = allCompensations.filter(comp => comp.type === category);

            filtered.forEach(comp => {
                $typeSelect.append(
                    `<option value="${comp.id}">${comp.name}</option>`
                );
            });

            $typeSelect.prop('disabled', false);
        });
        document.getElementById('benefit-create-modal').style.display = 'flex';
    }
    function closeBenefitCreateModal() {
        document.getElementById('benefit-create-modal').style.display = 'none';
    }

    function openBenefitViewModal(button) {
        const initials = (button.dataset.first_name[0] + button.dataset.last_name[0]).toUpperCase();
        const colors = ['#0b044d', '#8e1e18', '#15803d', '#a16207', '#7c3aed'];
        const color = colors[button.dataset.id % 5];

        const earnings = Number(button.dataset.earnings) || 0;
        const deductions = Number(button.dataset.deductions) || 0;
        const sickLeave = button.dataset.sickLeave;
        const vacationLeave = button.dataset.vacationLeave;
        const compensations = JSON.parse(button.dataset.compensations || '[]');

        const earningsList = compensations.filter(item =>
            item.compensation?.type === 'Earning'
        );

        const deductionsList = compensations.filter(item =>
            item.compensation?.type === 'Deduction'
        );

        document.getElementById('benefit-view-modal-avatar').innerText = initials;
        document.getElementById('benefit-view-modal-avatar').style.background = color;

        document.getElementById('benefit-view-modal-emp-id').innerText = 'EMP-' + String(button.dataset.id).padStart(3, '0');

        document.getElementById('benefit-view-modal-name').textContent = button.dataset.first_name + ' ' + button.dataset.last_name;
        document.getElementById('benefit-view-modal-position').textContent = button.dataset.position + ' · ' + button.dataset.department;
        document.getElementById('benefit-view-modal-earnings').textContent = '₱' + earnings.toLocaleString(undefined, {minimumFractionDigits: 2});
        document.getElementById('benefit-view-modal-deductions').textContent = '₱' + deductions.toLocaleString(undefined, {minimumFractionDigits: 2});
        document.getElementById('benefit-view-modal-sick-leave').textContent = sickLeave + ' days';
        document.getElementById('benefit-view-modal-vacation-leave').textContent = vacationLeave + ' days';

        const earningsOl = document.getElementById('earnings-list');
        earningsOl.innerHTML = '';

        earningsList.forEach(item => {
            const name = item.compensation?.name ?? 'Unknown';
            const amount = item.amount ?? 0;

            earningsOl.innerHTML += `
                <li style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #f7f6ff">
                    <span style="font-size:12.5px;color:#5a5888">${name}</span>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <strong style="font-size:13px;color:#0b044d">
                            ₱${Number(amount).toLocaleString(undefined, {minimumFractionDigits:2})}
                        </strong>
                        <button type="button" class="btn-edit" title="Edit" onclick="openCompensationEditModal(${item.id}, '${(name).replace(/'/g, '\\\'')}', ${amount})" style="width:24px;height:24px;padding:0;display:inline-flex;align-items:center;justify-content:center;">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </button>
                        <button type="button" class="btn-danger" title="Delete" onclick="openCompensationDeleteModal(${item.id}, '${(name).replace(/'/g, '\\\'')}')" style="width:24px;height:24px;padding:0;display:inline-flex;align-items:center;justify-content:center;">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        </button>
                    </div>
                </li>
            `;
        });

        const deductionsOl = document.getElementById('deductions-list');
        deductionsOl.innerHTML = '';

        deductionsList.forEach(item => {
            const name = item.compensation?.name ?? 'Unknown';
            const amount = item.amount ?? 0;

            deductionsOl.innerHTML += `
                <li style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #f7f6ff">
                    <span style="font-size:12.5px;color:#5a5888">${name}</span>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <strong style="font-size:13px;color:#8e1e18">
                            ₱${Number(amount).toLocaleString(undefined, {minimumFractionDigits:2})}
                        </strong>
                        <button type="button" class="btn-edit" title="Edit" onclick="openCompensationEditModal(${item.id}, '${(name).replace(/'/g, '\\\'')}', ${amount})" style="width:24px;height:24px;padding:0;display:inline-flex;align-items:center;justify-content:center;">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </button>
                        <button type="button" class="btn-danger" title="Delete" onclick="openCompensationDeleteModal(${item.id}, '${(name).replace(/'/g, '\\\'')}')" style="width:24px;height:24px;padding:0;display:inline-flex;align-items:center;justify-content:center;">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        </button>
                    </div>
                </li>
            `;
        });

        document.getElementById('benefit-view-modal').style.display = 'flex';
    }
    function closeBenefitViewModal() {
        document.getElementById('benefit-view-modal').style.display = 'none';
    }

    function openCompensationEditModal(id, name, amount) {
        const url = "{{ route('employee_compensations.update', ':id') }}".replace(':id', id);
        document.getElementById('compensation-edit-form').action = url;
        document.getElementById('comp-edit-name').value = name;
        document.getElementById('comp-edit-amount').value = amount;
        document.getElementById('comp-edit-modal-sub').textContent = 'Editing: ' + name;
        document.getElementById('compensation-edit-modal').style.display = 'flex';
    }
    function closeCompensationEditModal() {
        document.getElementById('compensation-edit-modal').style.display = 'none';
        document.getElementById('compensation-edit-form').reset();
    }

    function openCompensationDeleteModal(id, name) {
        const url = "{{ route('employee_compensations.destroy', ':id') }}".replace(':id', id);
        document.getElementById('compensation-delete-form').action = url;
        document.getElementById('comp-delete-name').textContent = name;
        document.getElementById('compensation-delete-modal').style.display = 'flex';
    }
    function closeCompensationDeleteModal() {
        document.getElementById('compensation-delete-modal').style.display = 'none';
    }

    $(document).on('change', '#edit-leave-status', function() {
        document.getElementById('edit-decline-reason-wrap').style.display = this.value === 'Declined' ? 'block' : 'none';
    });
</script>
@endpush
