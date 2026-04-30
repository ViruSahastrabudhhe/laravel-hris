@extends('layouts.admin')

@php
    $totalPresent = $attendances->where('attendance_status', 'Present')->count();
    $totalAbsences = $attendances->where('attendance_status', 'Absent')->count();
    $totalOT = 0;
    $totalLate = $attendances->where('attendance_status', 'Late')->count();

    foreach ($attendances as $attendance) {
        $totalOT += $attendance->overtime_minutes;
    }

    $totalOT = round($totalOT / 60, 2);
    $departments = \App\Models\Department::findAllWithUserID()->get();
    $positions = \App\Models\Position::findAllWithUserID()->get();
@endphp

@push('styles')
    <style>
        .modal-overlay { position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(11,4,77,0.6); backdrop-filter:blur(4px); display:flex; align-items:flex-start; justify-content:center; z-index:1000; padding:clamp(8px,3vw,20px); overflow-y:auto; }
        .modal-box { background:#fff; border-radius:16px; width:min(480px,100%); box-shadow:0 25px 50px -12px rgba(0,0,0,0.25); animation:slideUp 0.3s ease; margin:auto; }
        @keyframes slideUp { from { transform:translateY(20px); opacity:0; } to { transform:translateY(0); opacity:1; } }
        .modal-header { display:flex; justify-content:space-between; align-items:flex-start; padding:24px 24px 0; }
        .modal-eyebrow { font-size:10.5px; color:#9999bb; font-weight:700; letter-spacing:1px; }
        .modal-title { font-size:18px; font-weight:700; color:#0b044d; margin:4px 0 2px; }
        .modal-close { background:none; border:none; cursor:pointer; padding:4px; color:#9999bb; }
        .modal-close:hover { color:#0b044d; }
        .modal-body { padding:20px 24px; }
        .modal-footer { display:flex; justify-content:flex-end; gap:10px; padding:16px 24px 24px; }
        .modal-btn-ghost { padding:9px 18px; border-radius:9px; border:1.5px solid #dddcf0; background:#fff; font-size:13px; font-weight:600; color:#6b6a8a; cursor:pointer; }
        .modal-btn-ghost:hover { border-color:#0b044d; color:#0b044d; }
        .modal-btn-primary { border:none; background:linear-gradient(135deg,#0b044d,#1a0f6e); color:#fff; font-weight:700; }
        @media (max-width:768px) { .modal-box { border-radius:12px; } .modal-header { padding:16px 16px 0; } .modal-body { padding:14px 16px; } .modal-footer { padding:12px 16px 16px; } }
        @media (max-width:400px) { .modal-overlay { padding:0; align-items:flex-end; } .modal-box { border-radius:16px 16px 0 0; width:100%; margin:0; } }
    </style>
@endpush

@section('page-content')
<div class="welcome-banner">
    <div class="banner-left">
        <div class="banner-icon">
            <svg width="22" height="22" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="m9 16 2 2 4-4"/></svg>
        </div>
        <div>
            <h2>Attendance Records</h2>
            <p>Track employee time and attendance</p>
        </div>
    </div>
    <div class="banner-right">
        <span class="banner-badge outline">{{ $attendances->count() }} Records</span>
    </div>
</div>

<div class="view-tabs">
    <button class="view-tab active" onclick="switchView('attendances',this)">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="m9 16 2 2 4-4"/></svg>
        Attendances
    </button>
    <button class="view-tab" onclick="switchView('scan-history',this)">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <rect x="3" y="3" width="7" height="7" rx="1"/>
            <rect x="14" y="3" width="7" height="7" rx="1"/>
            <rect x="3" y="14" width="7" height="7" rx="1"/>
            <rect x="5" y="5" width="3" height="3" fill="currentColor" stroke="none"/>
            <rect x="16" y="5" width="3" height="3" fill="currentColor" stroke="none"/>
            <rect x="5" y="16" width="3" height="3" fill="currentColor" stroke="none"/>
            <line x1="14" y1="14" x2="14" y2="14.01"/>
            <line x1="17" y1="14" x2="17" y2="14.01"/>
            <line x1="21" y1="14" x2="21" y2="14.01"/>
            <line x1="14" y1="17" x2="14" y2="17.01"/>
            <line x1="21" y1="17" x2="21" y2="17.01"/>
            <line x1="14" y1="21" x2="14" y2="21.01"/>
            <line x1="17" y1="21" x2="21" y2="21"/>
            <line x1="2" y1="12" x2="22" y2="12" stroke-dasharray="3 1"/>
        </svg>        
        Scan History
    </button>
    <button class="view-tab" onclick="switchView('qr-codes',this)">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 7V5a2 2 0 012-2h2m10 0h2a2 2 0 012 2v2m0 10v2a2 2 0 01-2 2h-2M7 21H5a2 2 0 01-2-2v-2M7 7h10v10H7z"/></svg>
        QR Codes
    </button>
</div>

<div id="stats-attendances" class="stats-grid stats-grid-4">

    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Total Work Days</p>
            <div class="stat-icon-wrap" style="background:#f0effe">
                <svg width="17" height="17" fill="none" stroke="#0b044d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ now()->startOfMonth()->diffInWeekdays(now()->endOfMonth()) + 1 }} days</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#22c55e"></span>
            <p class="stat-sub">For {{ config('app.carbon_month') }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Total Present</p>
            <div class="stat-icon-wrap" style="background:#e8f9ef">
                <svg width="17" height="17" fill="none" stroke="#15803d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $totalPresent }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#22c55e"></span>
            <p class="stat-sub">For {{ config('app.carbon_month') }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Total Absences</p>
            <div class="stat-icon-wrap" style="background:#fdf0ef">
                <svg width="17" height="17" fill="none" stroke="#8e1e18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $totalAbsences }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#8e1e18"></span>
            <p class="stat-sub">Across all personnel</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Overtime Hours</p>
            <div class="stat-icon-wrap" style="background:#fefce8">
                <svg width="17" height="17" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $totalOT }} hrs</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#f59e0b"></span>
            <p class="stat-sub">{{ $totalLate }} late arrival(s)</p>
        </div>
    </div>

</div>

<div id="stats-qr-codes" class="stats-grid stats-grid-4" style="display: none;">
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Total Employees</p>
            <div class="stat-icon-wrap" style="background:#f0effe">
                <svg width="17" height="17" fill="none" stroke="#0b044d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
        </div>
        <p class="stat-value">0</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#22c55e"></span>
            <p class="stat-sub">Active employees</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">QR Codes Generated</p>
            <div class="stat-icon-wrap" style="background:#e8f9ef">
                <svg width="17" height="17" fill="none" stroke="#15803d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
            </div>
        </div>
        <p class="stat-value">0</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#22c55e"></span>
            <p class="stat-sub">This month</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Active QR Codes</p>
            <div class="stat-icon-wrap" style="background:#fef3c7">
                <svg width="17" height="17" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
        </div>
        <p class="stat-value">0</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#f59e0b"></span>
            <p class="stat-sub">Valid codes</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Expired QR Codes</p>
            <div class="stat-icon-wrap" style="background:#fdf0ef">
                <svg width="17" height="17" fill="none" stroke="#8e1e18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            </div>
        </div>
        <p class="stat-value">0</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#ef4444"></span>
            <p class="stat-sub">This month</p>
        </div>
    </div>
</div>

<div id="view-attendances" class="tab-pane active">
    <div class="table-section">
        <div class="table-header">
            <div>
                <p class="table-title">Attendance Summary</p>
                <p class="table-sub">Overview of employee attendance for {{ config('app.carbon_month') }}</p>
            </div>
            <div class="table-actions">
                <div class="search-wrap" style="position:relative;display:flex;align-items:center">
                    <svg width="13" height="13" fill="none" stroke="#9999bb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" style="position:absolute;left:10px;pointer-events:none"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" id="attendance-search" placeholder="Search attendance..." style="height:34px;padding:0 10px 0 30px;border:1.5px solid #e4e3f0;border-radius:8px;font-size:12.5px;font-family:'Poppins',sans-serif;color:#0b044d;background:#fafafe;outline:none;width:180px">
                </div>
                <select class="filter-select" id="month-filter" style="padding:7px 12px;border:1.5px solid #e4e3f0;border-radius:8px;font-size:12.5px;color:#0b044d;outline:none;background:#fff">
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}" {{ now()->month == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                        </option>
                    @endforeach
                </select>
                <select class="filter-select" id="year-filter" style="padding:7px 12px;border:1.5px solid #e4e3f0;border-radius:8px;font-size:12.5px;color:#0b044d;outline:none;background:#fff">
                    @foreach(range(now()->year - 2, now()->year) as $y)
                        <option value="{{ $y }}" {{ now()->year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
                <select class="filter-select" id="dept-filter" style="padding: 7px 12px; border: 1.5px solid #e4e3f0; border-radius: 8px; font-size: 12.5px; color: #0b044d; outline: none; background: #fff;">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->name }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
                <select class="filter-select" id="status-filter" style="padding: 7px 12px; border: 1.5px solid #e4e3f0; border-radius: 8px; font-size: 12.5px; color: #0b044d; outline: none; background: #fff;">
                    <option value="">All Status</option>
                    <option value="Complete">Complete</option>
                    <option value="Incomplete">Incomplete</option>
                </select>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="payroll-table" id="attendance-table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Position</th>
                        <th>Department</th>
                        <th>Present</th>
                        <th>Absent</th>
                        <th>Late</th>
                        <th>OT Hours</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($employees as $employee)
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
                        <td><span>{{ $employee->position->title }}</span></td>
                        <td><span class="dept-tag">{{ $employee->department->name }}</span></td>
                        <td><span style="color: #15803d; font-weight: 600;">{{ $employee->attendance->where('attendance_status', \App\Enums\AttendanceStatus::Present->value)->count(); }}</span></td>
                        <td><span style="color: #8e1e18; font-weight: 600;">{{ $employee->attendance->where('attendance_status', \App\Enums\AttendanceStatus::Absent->value)->count(); }}</span></td>
                        <td><span style="color: #a16207; font-weight: 600;">{{ $employee->attendance->where('attendance_status', \App\Enums\AttendanceStatus::Late->value)->count(); }}</span></td>
                        @php
                            $employeeOTHours = $employee->attendance()->sum('overtime_minutes') / 60;
                            $employeeAttendanceIsComplete = $employee->attendance->count();
                        @endphp
                        <td><span style="color: #0b044d; font-weight: 600;">{{ number_format($employeeOTHours, 2) }} hrs</span></td>
                        <td>
                            @if ($employeeAttendanceIsComplete >= now()->startOfMonth()->diffInWeekdays(now()->endOfMonth()) + 1)
                                <span class="badge-status processed">Complete</span>
                            @else
                                <span class="badge-status pending">Incomplete</span>
                            @endif
                        </td>
                        <td></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="view-scan-history" class="tab-pane">
    <div class="table-section">
        <div class="table-header">
            <div>
                <p class="table-title">Scan History</p>
                <p class="table-sub">View employee attendances thru QR Code scans</p>
            </div>
            <div class="table-actions">
                <form id="bulk-archive-form" action="{{ route('attendances.bulkDestroy') }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <div id="bulk-ids"></div>
                    <button type="submit" id="bulk-btn" class="btn-danger" style="display:none;" onclick="return confirm('Archive selected records?')">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        (<span id="bulk-count">0</span>)
                    </button>
                </form>
                <div class="search-wrap" style="position:relative;display:flex;align-items:center">
                    <svg width="13" height="13" fill="none" stroke="#9999bb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" style="position:absolute;left:10px;pointer-events:none"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" id="scan-search" placeholder="Search attendance..." style="height:34px;padding:0 10px 0 30px;border:1.5px solid #e4e3f0;border-radius:8px;font-size:12.5px;font-family:'Poppins',sans-serif;color:#0b044d;background:#fafafe;outline:none;width:180px">
                </div>
                <select class="filter-select" id="scan-status-filter" style="padding: 7px 12px; border: 1.5px solid #e4e3f0; border-radius: 8px; font-size: 12.5px; color: #0b044d; outline: none; background: #fff;">
                    <option value="">All Status</option>
                    <option value="Present">Present</option>
                    <option value="Late">Late</option>
                    <option value="Absent">Absent</option>
                </select>
                <a href="{{ route('attendances.archive') }}" class="btn-export">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="m9 11 3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                    View Archive
                </a>
                <a class="modal-btn-primary" id="add-attendance-btn" href="#attendance">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Submit DTR
                </a>  
                <a class="modal-btn-primary" href="{{ route('qr-code.scan') }}" target="_blank">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 7V5a2 2 0 012-2h2m10 0h2a2 2 0 012 2v2m0 10v2a2 2 0 01-2 2h-2M7 21H5a2 2 0 01-2-2v-2M7 7h10v10H7z"/></svg>
                    Scan QR Code
                </a>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="payroll-table" id="scan-table">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all" title="Select all"></th>
                        <th>Employee</th>
                        <th>Date</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                        <th>Break</th>
                        <th>Overtime</th>
                        <th>Total Hours</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($attendances as $attendance)
                    <tr>
                        <td><input type="checkbox" class="row-check" value="{{ $attendance->id }}"></td>
                        <td>
                            <div class="emp-cell">
                                <div class="emp-avatar" style="background:{{ ['#0b044d','#8e1e18','#15803d','#a16207','#7c3aed'][($attendance->employee->id % 5)] }}">
                                    {{ strtoupper(substr($attendance->employee->first_name, 0, 1) . substr($attendance->employee->last_name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="emp-name">{{ $attendance->employee->first_name }} {{ $attendance->employee->last_name }}</p>
                                    <p class="emp-id">EMP-{{ str_pad($attendance->employee->id, 3, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($attendance->date)->format(config('app.day_month')) }}</td>
                        <td><span class="dept-tag" style="background:#e8f9ef;color:#15803d;border-color:#bbf7d0">{{ $attendance->time_in ?? '--:--' }}</span></td>
                        <td><span class="dept-tag" style="background:#fdf0ef;color:#8e1e18;border-color:#f5d0ce">{{ $attendance->time_out ?? '--:--' }}</span></td>
                        <td><span style="font-size:12px;color:#9999bb">{{ $attendance->break_start && $attendance->break_end ? $attendance->break_start . ' - ' . $attendance->break_end : 'N/A' }}</span></td>
                        <td><span style="font-size:12px;color:#9999bb">{{ $attendance->overtime_minutes > 0 ? number_format(($attendance->overtime_minutes / 60), 1) . ' hrs' : 'N/A' }}</span></td>
                        <td><span class="pay-cell">{{ number_format((($attendance->total_minutes / 60) + ($attendance->overtime_minutes / 60)), 2) }} h</span></td>
                        <td>
                            @if($attendance->attendance_status === 'Present')
                                <span class="badge-status processed">{{ App\Enums\AttendanceStatus::Present->value }}</span>
                            @elseif($attendance->attendance_status === 'Late')
                                <span class="badge-status pending">{{ App\Enums\AttendanceStatus::Late->value }}</span>
                            @else
                                <span class="badge-status on-hold">{{ App\Enums\AttendanceStatus::Absent->value }}</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('attendances.destroy', $attendance) }}" method="post" style="display:inline" onsubmit="return confirm('Archive this attendance record?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger" style="display:inline-flex;align-items:center;gap:4px">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="view-qr-codes" class="tab-pane">
    <div class="table-section">
        <div class="table-header">
            <div>
                <p class="table-title">Employee QR Status</p>
                <p class="table-sub">Overview of QR code generation for employees</p>
            </div>
            <div class="table-actions">
                <div class="search-wrap" style="position:relative;display:flex;align-items:center">
                    <svg width="13" height="13" fill="none" stroke="#9999bb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" style="position:absolute;left:10px;pointer-events:none"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" id="qr-search" placeholder="Search employees..." style="height:34px;padding:0 10px 0 30px;border:1.5px solid #e4e3f0;border-radius:8px;font-size:12.5px;font-family:'Poppins',sans-serif;color:#0b044d;background:#fafafe;outline:none;width:180px">
                </div>
                <select class="filter-select" id="qr-position-filter" style="padding: 7px 12px; border: 1.5px solid #e4e3f0; border-radius: 8px; font-size: 12.5px; color: #0b044d; outline: none; background: #fff;">
                    <option value="">All Positions</option>
                    @foreach($positions as $position)
                        <option value="{{ $position->title }}">{{ $position->title }}</option>
                    @endforeach
                </select>
                <select class="filter-select" id="qr-status-filter" style="padding: 7px 12px; border: 1.5px solid #e4e3f0; border-radius: 8px; font-size: 12.5px; color: #0b044d; outline: none; background: #fff;">
                    <option value="">All Status</option>
                    <option value="Generated">Generated</option>
                    <option value="Not Generated">Not Generated</option>
                </select>
            </div>
        </div>
    
        <div class="table-wrapper">
            <table class="payroll-table" id="qr-table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Position</th>
                        <th>Work Schedule</th>
                        <th>Expiry Date</th>
                        <th>QR Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $employee)
                    @php $ws = $employee->employeeWorkSchedule?->workSchedule; @endphp
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
                        <td><span class="dept-tag" style="background:#f0effe;color:#0b044d;border-color:#dddcf0">{{ $employee->position->title }}</span></td>
                        <td>
                            @if($ws)
                                <span class="dept-tag" style="background:#fefce8;color:#a16207;border-color:#fde68a">
                                    {{ $ws->name }}, 
                                    {{ \Carbon\Carbon::parse($ws->start_time)->format('H:i') }}-{{ \Carbon\Carbon::parse($ws->end_time)->format('H:i') }}
                                </span>
                            @else
                                <spanclass="dept-tag" style="background:#fefce8;color:#a16207;border-color:#fde68a">No schedule</span>
                            @endif
                        </td>
                        <td>
                            @if(isset($qrScans[$employee->id]) && $qrScans[$employee->id]->expires_at)
                                @php $expired = $qrScans[$employee->id]->isExpired(); @endphp
                                <span style="color:{{ $expired ? '#dc2626' : '#15803d' }}">
                                    {{ $qrScans[$employee->id]->expires_at->format(config('app.day_month')) }}
                                </span>
                            @else
                                <span style="color:#9ca3af">—</span>
                            @endif
                        </td>
                        <td>
                            @if(isset($qrScans[$employee->id]))
                                <span class="badge-status processed">Generated</span>
                            @else
                                <span class="badge-status on-hold">Not Generated</span>
                            @endif
                        </td>
                        <td>
                            <div class="row-actions">
                                @if(isset($qrScans[$employee->id]))
                                    <button type="button" class="btn-view view-qr-btn"
                                        data-qr-id="{{ $qrScans[$employee->id]->id }}"
                                        data-qr-hash="{{ $qrScans[$employee->id]->qr_code_hash }}"
                                        data-employee-id="{{ $employee->id }}"
                                        data-employee-name="{{ $employee->first_name }} {{ $employee->last_name }}"
                                        data-expires="{{ $qrScans[$employee->id]->expires_at->format('F d, Y') }}">
                                        <svg width="12" height="10" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </button>
                                @else
                                <!-- <a href="{{ route('qr-code.create', $employee) }}" class="btn-success" style="display: inline-flex; align-items: center; gap: 4px;">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><path d="M14 14h.01M14 17h.01M17 14h.01M17 17h.01M20 14h.01M20 17h.01M20 20h.01M17 20h.01M14 20h.01"/></svg>
                                </a> -->
                                <button type="button" class="generate-qr-btn btn-edit" data-employee-id="{{ $employee->id }}" style="display:inline-flex;align-items:center;gap:4px">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><path d="M14 14h.01M14 17h.01M17 14h.01M17 17h.01M20 14h.01M20 17h.01M20 20h.01M17 20h.01M14 20h.01"/></svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal-overlay" id="attendance-modal" style="display:none" onclick="closeAttendanceModal()">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">IMPORT ATTENDANCE</span>
                <h3 class="modal-title">Upload CSV File</h3>
            </div>
            <button class="modal-close" onclick="closeAttendanceModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form action="{{ route('attendances.csvStore') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body" style="max-height:60vh;overflow-y:auto;">
                <div class="form-field">
                    <label>CSV File <span style="color:#dc2626">*</span></label>
                    <input type="file" name="csv_file" accept=".csv" required>
                </div>
                <div style="background:#f7f6ff;border-radius:10px;padding:14px 16px;font-size:12px;color:#6b6a8a;line-height:1.7;margin-top:14px;">
                    <strong style="color:#0b044d;display:block;margin-bottom:4px;">CSV Format</strong>
                    date, time_in, time_out, break_start, break_end, overtime_in, overtime_out, employee_id, user_id
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn-ghost" onclick="closeAttendanceModal()">Cancel</button>
                <button type="submit" class="modal-btn-primary">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    Import CSV
                </button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="qr-modal" style="display:none" onclick="closeQRCodeModal()">
    <div class="modal-box" style="max-width:460px" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">GENERATE QR CODE</span>
                <h3 class="modal-title">Generate Employee QR Code</h3>
            </div>
            <button class="modal-close" onclick="closeQRCodeModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form action="{{ route('qr-code.generate') }}" method="POST">
            @csrf
            <input type="hidden" name="employee_id" id="qr-modal-employee-id">
            <div class="modal-body">
                <h1 id="qr-modal-sub" style="font-size:25px;color:#0b044d;font-weight:600;margin:0"></h1>
                <p style="font-size:12px;color:#6b6a8a;margin:6px 0 0">A monthly QR code will be generated for this employee.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn-ghost" onclick="closeQRCodeModal()">Cancel</button>
                <button type="submit" class="modal-btn-primary">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><path d="M14 14h.01M14 17h.01M17 14h.01M17 17h.01M20 14h.01M20 17h.01M20 20h.01M17 20h.01M14 20h.01"/></svg>
                    Generate QR Code
                </button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="view-qr-modal" style="display:none" onclick="closeViewQRModal()">
    <div class="modal-box" style="max-width:460px" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">EMPLOYEE QR CODE</span>
                <h3 class="modal-title" id="view-qr-name"></h3>
            </div>
            <button class="modal-close" onclick="closeViewQRModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="modal-body" style="text-align:center">
            <div style="background:#f7f6ff;border-radius:12px;padding:24px;display:inline-block;margin-bottom:16px">
                <div style="background:#fff;padding:16px;border-radius:10px">
                    <div id="view-qrcode"></div>
                </div>
            </div>
            <div style="text-align:left;background:#f7f6ff;border-radius:10px;padding:16px">
                <div style="display:grid;gap:8px">
                    <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid #e4e3f0">
                        <span style="color:#9999bb;font-size:12px;font-weight:600">Employee ID</span>
                        <strong style="color:#0b044d;font-size:12px" id="view-qr-empid"></strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:6px 0">
                        <span style="color:#9999bb;font-size:12px;font-weight:600">Valid Until</span>
                        <strong style="color:#0b044d;font-size:12px" id="view-qr-expires"></strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="modal-btn-ghost" onclick="closeViewQRModal()">Close</button>
            <button type="button" class="modal-btn-primary" onclick="printViewQR()">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><path d="M6 14h12v8H6z"/></svg>
                Print QR Code
            </button>
        </div>
        <div id="view-qrcode-print" style="display:none"></div>
    </div>
</div>

<div class="modal-overlay" id="view-attendance-modal" style="display: none;">
    <div class="modal-box">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow" id="modal-period">DTR · </span>
                <h3 class="modal-title" id="modal-name">Employee Name</h3>
                <p class="modal-sub" id="modal-position">Position · Department</p>
            </div>
            <button class="modal-close" onclick="closeModal('view-modal')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="modal-body">
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px; padding: 16px; background: #f7f6ff; border-radius: 12px;">
                <div class="emp-avatar" id="modal-avatar" style="width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 700; color: #fff; background: #0b044d;">MS</div>
                <div>
                    <p id="modal-emp-id" style="font-size: 11px; color: #9999bb; margin: 0 0 4px;">PGS-0000</p>
                    <span class="badge-status" id="modal-status-badge">Complete</span>
                </div>
            </div>

            <p style="font-size: 10.5px; font-weight: 700; color: #9999bb; letter-spacing: 1px; margin-bottom: 12px;">ATTENDANCE SUMMARY</p>
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f7f6ff"><span style="font-size:12.5px;color:#5a5888">Working Days</span><strong style="font-size:13px;color:#0b044d" id="modal-working-days">22 days</strong></div>
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f7f6ff"><span style="font-size:12.5px;color:#5a5888">Days Present</span><strong style="font-size:13px;color:#15803d" id="modal-present">22 days</strong></div>
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f7f6ff"><span style="font-size:12.5px;color:#5a5888">Days Absent</span><strong style="font-size:13px;color:#8e1e18" id="modal-absent">0 days</strong></div>
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f7f6ff"><span style="font-size:12.5px;color:#5a5888">Late Arrivals</span><strong style="font-size:13px;color:#a16207" id="modal-late">1 times</strong></div>
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f7f6ff"><span style="font-size:12.5px;color:#5a5888">Half Days</span><strong style="font-size:13px;color:#a16207" id="modal-halfday">0 days</strong></div>

            <p style="font-size: 10.5px; font-weight: 700; color: #9999bb; letter-spacing: 1px; margin: 16px 0 12px;">OVERTIME</p>
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f7f6ff"><span style="font-size:12.5px;color:#5a5888">Total OT Hours</span><strong style="font-size:13px;color:#0b044d" id="modal-overtime">3.5 hrs</strong></div>

            <div style="margin-top: 16px; padding: 12px; background: #f7f6ff; border-radius: 10px; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 11px; font-weight: 700; color: #9999bb; letter-spacing: 1px;">ATTENDANCE RATE</span>
                <strong style="font-size: 18px; color: #15803d;" id="modal-rate">100%</strong>
            </div>
        </div>
        <div class="modal-footer">
            <button class="modal-btn-ghost" onclick="closeModal('view-modal')">Close</button>
            <button class="btn-export">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Download DTR
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    let _viewQRInstance = null, _viewQRPrintInstance = null;
    let _viewQREmployeeName = '', _viewQREmployeeId = '', _viewQRExpires = '';

    function closeViewQRModal() {
        document.getElementById('view-qr-modal').style.display = 'none';
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }

    function printViewQR() {
        setTimeout(function() {
            var img = document.querySelector('#view-qrcode-print img') || document.querySelector('#view-qrcode-print canvas');
            var src = img.tagName === 'CANVAS' ? img.toDataURL() : img.src;
            var win = window.open('', '_blank');
            win.document.write('<html><head><title>QR Code - ' + _viewQREmployeeName + '</title><style>*{margin:0;padding:0;box-sizing:border-box}body{display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:100vh;font-family:sans-serif;background:#fff}img{width:90vmin;height:90vmin;image-rendering:pixelated}p{margin-top:16px;font-size:18px;font-weight:700;color:#0b044d}small{color:#666;font-size:13px}@media print{@page{margin:0}}</style></head><body><img src="' + src + '"><p>' + _viewQREmployeeName + '</p><small>' + _viewQREmployeeId + ' &mdash; Valid until ' + _viewQRExpires + '</small></body></html>');
            win.document.close();
            win.focus();
            win.onload = function() { win.print(); };
        }, 300);
    }

    $(document).on('click', '.view-qr-btn', function() {
        const btn = $(this);
        const qrId = btn.data('qr-id');
        const hash = btn.data('qr-hash');
        const empId = btn.data('employee-id');
        const empName = btn.data('employee-name');
        const expires = btn.data('expires');

        _viewQREmployeeName = empName;
        _viewQREmployeeId = 'EMP-' + String(empId).padStart(3, '0');
        _viewQRExpires = expires;

        document.getElementById('view-qr-name').textContent = empName;
        document.getElementById('view-qr-empid').textContent = _viewQREmployeeId;
        document.getElementById('view-qr-expires').textContent = expires;

        // Clear previous QR instances
        document.getElementById('view-qrcode').innerHTML = '';
        document.getElementById('view-qrcode-print').innerHTML = '';

        const qrData = JSON.stringify({ id: qrId, hash: hash, employee_id: empId });
        _viewQRInstance = new QRCode(document.getElementById('view-qrcode'), {
            text: qrData, width: 200, height: 200,
            colorDark: '#0b044d', colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });
        _viewQRPrintInstance = new QRCode(document.getElementById('view-qrcode-print'), {
            text: qrData, width: 1024, height: 1024,
            colorDark: '#0b044d', colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });

        const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
        document.body.style.paddingRight = scrollbarWidth + 'px';
        document.getElementById('view-qr-modal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    });
</script>

<script>
    function switchView(viewId, btn) {
        if (btn.classList.contains('active')) {
            return;
        }

        document.querySelectorAll('.tab-pane').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.view-tab').forEach(el => el.classList.remove('active'));
        document.getElementById('view-' + viewId).classList.add('active');
        btn.classList.add('active');
        document.getElementById('stats-attendances').style.display  = viewId === 'attendances' || viewId === 'scan-history'   ? 'grid' : 'none';
        document.getElementById('stats-qr-codes').style.display = viewId === 'qr-codes' ? 'grid' : 'none';
        document.getElementById('quick-actions-attendances').style.display  = viewId === 'attendances'   ? 'flex' : 'none';
        document.getElementById('quick-actions-qr-codes').style.display = viewId === 'qr-codes' ? 'flex' : 'none';
    }

    function closeAttendanceModal() {
        document.getElementById('attendance-modal').style.display = 'none';
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }

    function closeQRCodeModal() {
        document.getElementById('qr-modal').style.display = 'none';
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }

    $('#add-attendance-btn').on('click', function (e) {
        e.preventDefault();
        const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
        document.body.style.paddingRight = scrollbarWidth + 'px';
        document.getElementById('attendance-modal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    });

    $(document).on('click', '.generate-qr-btn', function (e) {
        e.preventDefault();
        const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
        const employeeId = $(this).data('employee-id');
        const employeeName = $(this).closest('tr').find('.emp-name').text();
        document.getElementById('qr-modal-employee-id').value = employeeId;
        document.getElementById('qr-modal-sub').textContent = 'For ' + employeeName;
        document.body.style.paddingRight = scrollbarWidth + 'px';
        document.getElementById('qr-modal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    });

    $(document).on('keydown', function (e) {
        if (e.key === 'Escape') {
            closeAttendanceModal();
            closeQRCodeModal();
            closeViewQRModal();
        };
    });

    $(function () {
        const attendance_table = $('#attendance-table').DataTable({
            columnDefs: [{ orderable: false, targets: [7] }],
            pageLength: 25,
            language: { search: 'Search:', lengthMenu: 'Show _MENU_ entries', emptyTable: 'No attendance records found', },
            dom: 'rtip',
        });    
    
        const scan_table = $('#scan-table').DataTable({
            columnDefs: [{ orderable: false, targets: [0, 9] }],
            pageLength: 25,
            language: { search: 'Search:', lengthMenu: 'Show _MENU_ entries', emptyTable: 'No scan records found', },
            dom: 'rtip',
        });

        const qr_table = $('#qr-table').DataTable({
            columnDefs: [{ orderable: false, targets: [0, 4] }],
            pageLength: 25,
            language: { search: 'Search:', lengthMenu: 'Show _MENU_ entries', emptyTable: 'No QR codes found', },
            dom: 'rtip',
        });

        $('#attendance-search').on('keyup', function() {
            attendance_table.search(this.value).draw();
        });

        $('#dept-filter').on('change', function() {
            attendance_table.column(2).search(this.value).draw();
        });

        $('#status-filter').on('change', function() {
            const val = this.value ? '^' + this.value + '$' : '';
            attendance_table.column(7).search(val, true, false).draw();
        });

        $('#scan-search').on('keyup', function() {
            scan_table.search(this.value).draw();
        });

        $('#scan-dept-filter').on('change', function() {
            scan_table.column(2).search(this.value).draw();
        });

        $('#scan-status-filter').on('change', function() {
            scan_table.column(8).search(this.value).draw();
        });

        $('#qr-search').on('keyup', function() {
            qr_table.search(this.value).draw();
        });

        $('#qr-position-filter').on('change', function() {
            qr_table.column(1).search(this.value).draw();
        });

        $('#qr-status-filter').on('change', function() {
            const val = this.value ? '^' + this.value + '$' : '';
            qr_table.column(4).search(val, true, false).draw();
        });

        $('#select-all').on('change', function () {
            $('.row-check').prop('checked', this.checked);
            updateBulkBar();
        });

        $(document).on('change', '.row-check', function () {
            if (!this.checked) $('#select-all').prop('checked', false);
            updateBulkBar();
        });

        function updateBulkBar() {
            const checked = $('.row-check:checked');
            if (checked.length) {
                $('#bulk-btn').show();
                $('#bulk-count').text(checked.length);
                $('#bulk-ids').html(checked.map((_, el) =>
                    `<input type="hidden" name="ids[]" value="${el.value}">`
                ).get().join(''));
            } else {
                $('#bulk-btn').hide();
            }
        }

        const colors = ['#0b044d','#8e1e18','#15803d','#a16207','#7c3aed'];

        function fetchAttendance() {
            const month = $('#month-filter').val();
            const year  = $('#year-filter').val();

            $.get('{{ route('attendances.filter') }}', { month, year }, function(res) {
                attendance_table.clear();

                res.employees.forEach(function(e) {
                    const initials = e.name.split(' ').map(w => w[0]).join('').substring(0,2).toUpperCase();
                    const color    = colors[e.id % 5];
                    const empId    = 'EMP-' + String(e.id).padStart(3, '0');
                    const status   = e.complete
                        ? '<span class="badge-status processed">Complete</span>'
                        : '<span class="badge-status pending">Incomplete</span>';

                    attendance_table.row.add([
                        `<div class="emp-cell"><div class="emp-avatar" style="background:${color}">${initials}</div><div><p class="emp-name">${e.name}</p><p class="emp-id">${empId}</p></div></div>`,
                        e.position,
                        `<span class="dept-tag">${e.department}</span>`,
                        `<span style="color:#15803d;font-weight:600">${e.present}</span>`,
                        `<span style="color:#8e1e18;font-weight:600">${e.absent}</span>`,
                        `<span style="color:#a16207;font-weight:600">${e.late}</span>`,
                        `<span style="color:#0b044d;font-weight:600">${e.ot_hours} hrs</span>`,
                        status,
                        '',
                    ]);
                });

                attendance_table.draw();
            });
        }

        $('#month-filter, #year-filter').on('change', fetchAttendance);
    });
</script>
@endpush