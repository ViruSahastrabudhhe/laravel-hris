@extends('layouts.admin')

@php
$monthlyPayroll = 0;
$employeesOnLeave = 0;
$employeesPendingLeave = 0;
$totalEmployees = $employees->count();

foreach ($employees as $employee) {
    $monthlyPayroll += $employee->netPay();
    $employeesOnLeave += $employee->leaves()->where('leave_status', 'Approved')->count();
    $employeesPendingLeave += $employee->leaves()->where('leave_status', \App\Enums\LeaveStatus::Pending->value)->count();
}

$presentToday = $employees->filter(function($employee) {
    return $employee->attendance()->whereDate('date', now()->toDateString())->where('attendance_status', 'Present')->exists();
})->count();

@endphp

@section('page-content')
<div class="welcome-banner">
    <div class="banner-left">
        <div class="banner-icon">
            <svg width="22" height="22" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </div>
        <div>
            <h2>Welcome back, {{ auth()->user()->name }}!</h2>
            <p>{{ now()->format('l, F j, Y') }} &nbsp;·&nbsp; PRIME HRIS Dashboard</p>
        </div>
    </div>
    <div class="banner-right">
        <span class="banner-badge">
            <span class="banner-badge-dot"></span>
            System Online
        </span>
        <span class="banner-badge outline">FY {{ now()->year }}</span>
    </div>
</div>

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
            <p class="stat-sub">For {{ now()->format('F Y') }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">System Status</p>
            <div class="stat-icon-wrap" style="background:#fdf0ef">
                <svg width="17" height="17" fill="none" stroke="#8e1e18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            </div>
        </div>
        <p class="stat-value" style="font-size:20px">Active</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#22c55e"></span>
            <p class="stat-sub">All systems operational</p>
        </div>
    </div>

</div>

<div class="table-section">
    <div class="table-header">
        <div>
            <p class="table-title">Employee Directory</p>
            <p class="table-sub">All active government personnel</p>
        </div>
        <div class="table-actions">
            <a href="{{ route('employees.index') }}" class="btn-export">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                View Employees
            </a>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="payroll-table" id="attendance-table">
            <thead>
                <tr>
                    <th>#</th>
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
            @forelse ($employees as $employee)
                <tr>
                    <td class="emp-cell"><span style="font-size:12px;color:#9999bb">{{ $loop->iteration }}</span></td>
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
                    <td><span class="position-cell">{{ $employee->position->title }}</span></td>
                    <td><span class="dept-tag">{{ $employee->department->name }}</span></td>
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
            @empty
            @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($employees, 'hasPages') && $employees->hasPages())
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

<div class="bottom-row">

    <div class="table-section mb-0">
        <div class="table-header">
            <div>
                <p class="table-title">Recent Leave Requests</p>
                <p class="table-sub">Requires your approval</p>
            </div>
            <a href="{{ route('employee_leaves.index') }}" class="btn-export">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                View All
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
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
            @forelse($employeeLeaves as $leave)
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
                    <td>{{$leave->leaveType->leave_type }}</td>
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
                            <form action="{{ route('employee_leaves.destroy', $leave) }}" method="post" style="display:inline" onsubmit="return confirm('Delete this leave record?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-view" style="color:#8e1e18;border-color:#f5d0ce">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="empty-state">
                        <svg width="48" height="48" fill="none" stroke="#d9d9ee" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        <p style="font-size:14px;color:#9999bb;margin-top:12px">No leave records found</p>
                    </td>
                </tr>
            @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="side-col">

        <div class="stat-card no-margin">
            <p class="stat-label" style="margin-bottom:12px">Monthly Payroll</p>
            <p class="stat-value" style="font-size:20px;margin-bottom:6px">₱{{ number_format($monthlyPayroll, 2) }}</p>
            <div class="stat-footer">
                <span class="stat-dot" style="background:#0b044d"></span>
                <p class="stat-sub">For {{ now()->format('F Y') }}</p>
            </div>
        </div>

        <div class="stat-card no-margin">
            <p class="stat-label" style="margin-bottom:12px">Monthly Payroll</p>
            <p class="stat-value" style="font-size:20px;margin-bottom:6px">₱{{ number_format($monthlyPayroll, 2) }}</p>
            <div class="stat-footer">
                <span class="stat-dot" style="background:#0b044d"></span>
                <p class="stat-sub">For {{ now()->format('F Y') }}</p>
            </div>
        </div>

    </div>

</div>

<div class="table-section">
    <div class="table-header">
        <div>
            <p class="table-title">Quick Actions</p>
            <p class="table-sub">Common tasks and shortcuts</p>
        </div>
    </div>
    <div style="padding:24px;display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:14px">
        <a href="{{ route('employees.index') }}" class="pub-service-card" style="text-decoration:none">
            <div class="pub-service-icon">
                <svg width="28" height="28" fill="none" stroke="#0b044d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <h4>Manage Employees</h4>
            <p>View and manage employee records</p>
        </a>
        <a href="{{ route('attendances.index') }}" class="pub-service-card" style="text-decoration:none">
            <div class="pub-service-icon">
                <svg width="28" height="28" fill="none" stroke="#15803d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <h4>Attendance</h4>
            <p>Track employee attendance records</p>
        </a>
        <a href="{{ route('payroll.index') }}" class="pub-service-card" style="text-decoration:none">
            <div class="pub-service-icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="#8e1e18" stroke="none"><text x="3" y="19" font-size="17" font-weight="bold" font-family="Arial, sans-serif">₱</text></svg>
            </div>
            <h4>Payroll</h4>
            <p>Process and manage payroll</p>
        </a>
        <a href="{{ route('employee_leaves.index') }}" class="pub-service-card" style="text-decoration:none">
            <div class="pub-service-icon">
                <svg width="28" height="28" fill="none" stroke="#a16207" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <h4>Leave Management</h4>
            <p>Manage employee leave requests</p>
        </a>
    </div>
</div>

@push('scripts')
<script>
$(function () {
    $('#attendance-table').DataTable({
        columnDefs: [{ orderable: false, targets: [0, 7] }],
        pageLength: 5,
        lengthMenu: [5, 10],
        language: { search: 'Search:', lengthMenu: 'Show _MENU_ Entries', emptyTable: 'No employees found', },
    });
});
</script>
@endpush


@endsection
