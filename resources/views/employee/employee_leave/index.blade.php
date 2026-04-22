@extends('layouts.admin')

@php
$totalLeaves = $employeeLeaves->count();
$totalApproved = $employeeLeaves->where('leave_status', \App\Enums\LeaveStatus::Approved->value)->count();
$totalPending = $employeeLeaves->where('leave_status', \App\Enums\LeaveStatus::Pending->value)->count();
$totalLeaveDays = 0;

foreach ($employeeLeaves as $leave) {
    $totalLeaveDays += $leave->leave_duration;
}
@endphp

@section('page-content')
<div class="welcome-banner">
    <div class="banner-left">
        <div class="banner-icon">
            <svg width="22" height="22" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
        </div>
        <div>
            <h2>Leave Management</h2>
            <p>Track and manage employee leave requests</p>
        </div>
    </div>
    <div class="banner-right">
        <span class="banner-badge outline">{{ $employeeLeaves->count() }} Records</span>
    </div>
</div>

<div class="stats-grid stats-grid-4">
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

<div class="table-section">
    <div class="table-header">
        <div>
            <p class="table-title">Leave Management</p>
            <p class="table-sub">Track and manage employee leave requests</p>
        </div>
        <div class="table-actions">
            <a href="{{ route('employee_leaves.create') }}" class="modal-btn-primary">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Leave
            </a>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="payroll-table" id="attendance-table">
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
            @forelse($employeeLeaves as $leave)
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
                    <td>{{$leave->leaveType->leave_type }}</td>
                    <td><span style="font-size:12.5px;color:#5a5888">{{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}</span></td>
                    <td><span style="font-size:12.5px;color:#5a5888">{{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}</span></td>
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
                    <td>
                        <div class="row-actions">
                            <a href="{{ route('employee_leaves.edit', $leave) }}" class="btn-edit">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                            @if ($leave->leave_status != \App\Enums\LeaveStatus::Pending->value)
                            <form action="{{ route('employee_leaves.destroy', $leave) }}" method="POST" style="display:inline" onsubmit="return confirm('Archive this leave request?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-view" style="color:#8e1e18;border-color:#f5d0ce">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                </button>
                            </form>
                            @else
                            <form action="{{ route('employee_leaves.approve', $leave) }}" method="post" style="display:inline" onsubmit="return confirm('Approve this leave request?')">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn-success" style="display: inline-flex; align-items: center; gap: 4px;">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>    
                                </button>
                            </form>
                            <button type="button" class="btn-danger" style="display:inline-flex;align-items:center;gap:4px" onclick="openDenyModal('{{ route('employee_leaves.deny', $leave) }}', '{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}')">
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

@push('scripts')
<script>
$(function () {
    $('#attendance-table').DataTable({
        columnDefs: [{ orderable: false, targets: [0, 6] }],
        pageLength: 25,
        language: { search: 'Search:', lengthMenu: 'Show _MENU_ entries', emptyTable: 'No employee leave requests found', },
    });
});

function openDenyModal(action, employeeName) {
    document.getElementById('deny-form').action = action;
    document.getElementById('deny-modal-sub').textContent = 'Provide a reason for declining ' + employeeName + '\'s request';
    document.getElementById('deny-modal').style.display = 'flex';
}

function closeDenyModal() {
    document.getElementById('deny-modal').style.display = 'none';
    document.getElementById('deny-form').reset();
}

$(document).on('keydown', function(e) {
    if (e.key === 'Escape') closeDenyModal();
});
</script>
@endpush
@endsection
