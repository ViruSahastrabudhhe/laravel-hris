@extends('layouts.admin')

@php
    $totalPresent = $attendances->where('attendance_status', \App\Enums\AttendanceStatus::Present->value)->count();
    $totalLate = $attendances->where('attendance_status', \App\Enums\AttendanceStatus::Late->value)->count();
    $totalAbsences = $attendances->where('attendance_status', \App\Enums\AttendanceStatus::Absent->value)->count();
    $totalOT = $attendances->sum('overtime_minutes') / 60;

    $totalOT = round($totalOT, 2);
@endphp

@section('page-content')
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
            <p class="stat-sub stat-month">For {{ config('app.carbon_month') }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Total Present</p>
            <div class="stat-icon-wrap" style="background:#e8f9ef">
                <svg width="17" height="17" fill="none" stroke="#15803d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
        </div>
        <p class="stat-value" id="stat-total-present">{{ $totalPresent }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#22c55e"></span>
            <p class="stat-sub stat-month">For {{ config('app.carbon_month') }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Total Absences</p>
            <div class="stat-icon-wrap" style="background:#fdf0ef">
                <svg width="17" height="17" fill="none" stroke="#8e1e18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            </div>
        </div>
        <p class="stat-value" id="stat-total-absent">{{ $totalAbsences }}</p>
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
        <p class="stat-value" id="stat-total-overtime">{{ $totalOT }} hrs</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#f59e0b"></span>
            <p class="stat-sub" id="stat-total-late">{{ $totalLate }} {{ $totalLate == 1 ? 'late arrival' : 'late arrivals' }}</p>
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

<div class="view-tabs" hidden>
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

<div style="display: flex; gap: 4px; margin-bottom: 20px; border-bottom: 1.5px solid #eceaf8; padding-bottom: 0;">
    <button class="tab-btn active" onclick="switchView('attendances', this)">Attendances</button>
    <button class="tab-btn" onclick="switchView('scan-history', this)">Scan History</button>
    <button class="tab-btn" onclick="switchView('qr-codes', this)">QR Codes</button>
</div>

<div id="tab-attendances" class="tab-pane active">
    <div class="table-section">
        <div class="table-header">
            <div>
                <p class="table-title">Attendance Summary</p>
                <p class="table-sub">Overview of employee attendance</p>
            </div>
            <div class="table-actions">
                <div class="search-wrap" style="position:relative;display:flex;align-items:center">
                    <svg width="13" height="13" fill="none" stroke="#9999bb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" style="position:absolute;left:10px;pointer-events:none"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" id="attendance-search" placeholder="Search attendance..." style="height:34px;padding:0 10px 0 30px;border:1.5px solid #e4e3f0;border-radius:8px;font-size:12.5px;font-family:'Poppins',sans-serif;color:#0b044d;background:#fafafe;outline:none;width:180px">
                </div>
                <select class="filter-select" id="ea-month-filter" style="padding:7px 12px;border:1.5px solid #e4e3f0;border-radius:8px;font-size:12.5px;color:#0b044d;outline:none;background:#fff">
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}" {{ now()->month == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                        </option>
                    @endforeach
                </select>
                <select class="filter-select" id="ea-year-filter" style="padding:7px 12px;border:1.5px solid #e4e3f0;border-radius:8px;font-size:12.5px;color:#0b044d;outline:none;background:#fff">
                    @foreach(range(now()->year - 2, now()->year) as $y)
                        <option value="{{ $y }}" {{ now()->year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
                <select class="filter-select" id="dept-filter" style="padding: 7px 12px; border: 1.5px solid #e4e3f0; border-radius: 8px; font-size: 12.5px; color: #0b044d; outline: none; background: #fff;" hidden>
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
                        <th>Present</th>
                        <th>Late</th>
                        <th>Absent</th>
                        <th>OT Hours</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($employeeAttendances as $attendance)
                    <tr>
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
                        <td><span style="color: #15803d; font-weight: 600;">{{ $attendance->total_present }}</span></td>
                        <td><span style="color: #a16207; font-weight: 600;">{{ $attendance->total_late }}</span></td>
                        <td><span style="color: #8e1e18; font-weight: 600;">{{ $attendance->total_absent }}</span></td>
                        <td><span style="color: #0b044d; font-weight: 600;">{{ number_format(($attendance->total_overtime / 60), 2) }} hrs</span></td>
                        <td>
                            @if ($attendance->is_complete === true)
                                <span class="badge-status processed" data-status="complete">Complete</span>
                            @else
                                <span class="badge-status pending" data-status="incomplete">Incomplete</span>
                            @endif
                        </td>
                        <td>
                            <div class="row-actions">
                                <button class="btn-view" onclick='openViewDTRModal({{json_encode([
                                    "id" => $attendance->employee->id,
                                    "first_name" => $attendance->employee->first_name,
                                    "last_name" => $attendance->employee->last_name,
                                    "department" => $attendance->employee->department->name ?? null,
                                    "position" => $attendance->employee->position->title ?? null,
                                    "present" => $attendance->total_present,
                                    "late" => $attendance->total_late,
                                    "absent" => $attendance->total_absent,
                                    "overtime" => $attendance->total_overtime,
                                    "is_complete" => (bool) $attendance->is_complete
                                ])}})'>
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

<div id="tab-scan-history" class="tab-pane">
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
                <select class="filter-select" id="scan-month-filter" style="padding:7px 12px;border:1.5px solid #e4e3f0;border-radius:8px;font-size:12.5px;color:#0b044d;outline:none;background:#fff">
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}" {{ now()->month == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                        </option>
                    @endforeach
                </select>
                <select class="filter-select" id="scan-year-filter" style="padding:7px 12px;border:1.5px solid #e4e3f0;border-radius:8px;font-size:12.5px;color:#0b044d;outline:none;background:#fff">
                    @foreach(range(now()->year - 2, now()->year) as $y)
                        <option value="{{ $y }}" {{ now()->year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
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
                            @elseif($attendance->attendance_status === 'Absent')
                                <span class="badge-status on-hold">{{ App\Enums\AttendanceStatus::Absent->value }}</span>
                            @else
                                <span class="badge-status on-hold">{{ App\Enums\AttendanceStatus::Missing->value }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="row-actions">
                                <button class="btn-edit" onclick="openEditDTRModal({{json_encode([
                                    "employee_id" => $attendance->employee->id,
                                    "first_name" => $attendance->employee->first_name,
                                    "last_name" => $attendance->employee->last_name,
                                    "attendance_id" => $attendance->id,
                                    "date" => $attendance->date,
                                    "time_in" => $attendance->time_in,
                                    "time_out" => $attendance->time_out,
                                    "break_start" => $attendance->break_start,
                                    "break_end" => $attendance->break_end,
                                    "overtime_in" => $attendance->overtime_in,
                                    "overtime_out" => $attendance->overtime_out,
                                ])}})">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </button>
                                <form action="{{ route('attendances.destroy', $attendance) }}" method="post" style="display:inline" onsubmit="return confirm('Archive this attendance record?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger" style="display:inline-flex;align-items:center;gap:4px">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="tab-qr-codes" class="tab-pane">
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
                        <td>
                            @if($ws)
                                <span class="dept-tag" style="background:#fefce8;color:#a16207;border-color:#fde68a">
                                    {{ $ws->name }},
                                    {{ \Carbon\Carbon::parse($ws->start_time)->format('H:i') }}-{{ \Carbon\Carbon::parse($ws->end_time)->format('H:i') }}
                                </span>
                            @else
                                <span class="dept-tag" style="background:#fefce8;color:#a16207;border-color:#fde68a">No schedule</span>
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
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
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

{{-- Submit DTR Modal --}}
<div class="modal-overlay" id="attendance-modal" style="display:none">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">IMPORT ATTENDANCE</span>
                <h3 class="modal-title">Upload CSV File</h3>
            </div>
            <button class="modal-close" onclick="closeModal('attendance-modal')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form action="{{ route('attendances.bulkStore') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body" style="max-height:60vh;overflow-y:auto;">
                <div class="form-field">
                    <label>CSV File <span style="color:#dc2626">*</span></label>
                    <input type="file" name="csv_file" accept=".csv" required>
                </div>
                <div style="background:#f7f6ff;border-radius:10px;padding:14px 16px;font-size:12px;color:#6b6a8a;line-height:1.7;margin-top:14px;">
                    <strong style="color:#0b044d;display:block;margin-bottom:4px;">CSV Format</strong>
                    date, time_in, time_out, break_start, break_end, overtime_in, overtime_out, employee_id
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn-ghost" onclick="closeModal('attendance-modal')">Cancel</button>
                <button type="submit" class="modal-btn-primary">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    Import CSV
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Generate QR Modal --}}
<div class="modal-overlay" id="qr-modal" style="display:none">
    <div class="modal-box" style="max-width:460px" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">GENERATE QR CODE</span>
                <h3 class="modal-title">Generate Employee QR Code</h3>
            </div>
            <button class="modal-close" onclick="closeModal('qr-modal')">
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
                <button type="button" class="modal-btn-ghost" onclick="closeModal('qr-modal')">Cancel</button>
                <button type="submit" class="modal-btn-primary">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><path d="M14 14h.01M14 17h.01M17 14h.01M17 17h.01M20 14h.01M20 17h.01M20 20h.01M17 20h.01M14 20h.01"/></svg>
                    Generate QR Code
                </button>
            </div>
        </form>
    </div>
</div>

{{-- View QR Modal --}}
<div class="modal-overlay" id="view-qr-modal" style="display:none">
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

{{-- View DTR Modal --}}
<div class="modal-overlay" id="view-attendance-modal" style="display: none;">
    <div class="modal-box">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow" id="modal-period">DTR · </span>
                <h3 class="modal-title" id="modal-name">Employee Name</h3>
                <p class="modal-sub" id="modal-position">Position · Department</p>
            </div>
            <button class="modal-close" onclick="closeModal('view-attendance-modal')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="modal-body">
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px; padding: 16px; background: #f7f6ff; border-radius: 12px;">
                <div class="emp-avatar" id="modal-avatar" style="width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 700; color: #fff; background: #0b044d;">
                    MS
                </div>
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
            <button class="modal-btn-ghost" onclick="closeModal('view-attendance-modal')">Close</button>
            <button class="modal-btn-primary">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Download DTR
            </button>
        </div>
    </div>
</div>

{{-- Edit DTR Modal --}}
<div class="modal-overlay" id="edit-attendance-modal" style="display:none">
    <div class="modal-box" onclick="event.stopPropagation()">

        <div class="modal-header">
            <div>
                <span class="modal-eyebrow" id="edit-dtr-title">EDIT DTR</span>
                <h3 class="modal-title">Update Attendance Record</h3>
                <p class="modal-sub" id="edit-dtr-employee">For Employee?</p>
            </div>

            <button class="modal-close" onclick="closeModal('edit-attendance-modal')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        <form id="edit-dtr-form" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body" style="max-height:60vh;overflow-y:auto;">
                <input type="hidden" name="employee_id" id="edit-dtr-employee_id" required>
                <input type="hidden" name="attendance_id" id="edit-dtr-attendance_id" required>
                <input type="hidden" name="date" id="edit-dtr-date" required>

                <div style="display:flex; gap:12px;">
                    <div class="form-field" style="flex:1;">
                        <label>Time In </label>
                        <input type="time" name="time_in" id="edit-dtr-time_in">
                    </div>

                    <div class="form-field" style="flex:1;">
                        <label>Time Out </label>
                        <input type="time" name="time_out" id="edit-dtr-time_out">
                    </div>
                </div>

                <div style="display:flex; gap:12px;">
                    <div class="form-field" style="flex:1;">
                        <label>Break Start </label>
                        <input type="time" name="break_start" id="edit-dtr-break_start">
                    </div>

                    <div class="form-field" style="flex:1;">
                        <label>Break End </label>
                        <input type="time" name="break_end" id="edit-dtr-break_end">
                    </div>
                </div>

                <div style="display:flex; gap:12px;">
                    <div class="form-field" style="flex:1;">
                        <label>Overtime In </label>
                        <input type="time" name="overtime_in" id="edit-dtr-overtime_in">
                    </div>

                    <div class="form-field" style="flex:1;">
                        <label>Overtime Out </label>
                        <input type="time" name="overtime_out" id="edit-dtr-overtime_out">
                    </div>
                </div>

                <div class="form-field">
                    <label>Remarks <span style="color:#dc2626">*</span></label>
                    <textarea name="correction[remarks]" rows="3" placeholder="Enter remarks..." required></textarea>
                </div>

                <div class="form-field">
                    <label>Proof (PDF or Image) <span style="color:#dc2626">*</span></label>
                    <input type="file" name="correction[proof]" accept=".pdf,image/*" required>
                </div>

                <div style="background:#f7f6ff;border-radius:10px;padding:14px 16px;font-size:12px;color:#6b6a8a;line-height:1.7;margin-top:14px;">
                    <strong style="color:#0b044d;display:block;margin-bottom:4px;">Notes</strong>
                    Uploading a new proof will replace the existing file.
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="modal-btn-ghost" onclick="closeModal('edit-attendance-modal')">
                    Cancel
                </button>

                <button type="submit" class="modal-btn-primary">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Update DTR
                </button>
            </div>

        </form>
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
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
        document.getElementById('tab-' + viewId).classList.add('active');
        btn.classList.add('active');

        if (viewId === 'attendances' || viewId === 'scan-history') {
            document.getElementById('stats-attendances').style.display = 'grid';
            document.getElementById('stats-qr-codes').style.display = 'none';
        } else {
            document.getElementById('stats-attendances').style.display = 'none';
            document.getElementById('stats-qr-codes').style.display = 'grid';
        }
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

    $(function () {
        const attendance_table = $('#attendance-table').DataTable({
            columnDefs: [{ orderable: false, targets: [6] }],
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
            columnDefs: [{ orderable: false, targets: [4] }],
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
            attendance_table.column(5).search(val, true, false).draw();
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

        function fetchEmployeeAttendance() {
            const month = parseInt($('#ea-month-filter').val());
            const year  = parseInt($('#ea-year-filter').val());

            if (month === {{ now()->month }} && year === {{ now()->year }}) {
                location.reload();
                return;
            }

            $.get('{{ route('attendances.filterEmployeeAttendance') }}', { month, year }, function(res) {
                attendance_table.clear();

                res.employees.forEach(function(e) {
                    const initials = (e.first_name[0] + e.last_name[0]).toUpperCase();
                    const color    = colors[e.id % 5];
                    const empId    = 'EMP-' + String(e.id).padStart(3, '0');
                    const status   = e.is_complete
                        ? '<span class="badge-status processed">Complete</span>'
                        : '<span class="badge-status pending">Incomplete</span>';

                    const modalData = {
                        id: e.id,
                        first_name: e.first_name,
                        last_name: e.last_name,
                        department: e.department,
                        position: e.position,
                        present: e.present,
                        late: e.late,
                        absent: e.absent,
                        overtime: e.ot_hours,
                        is_complete: e.is_complete
                    };

                    attendance_table.row.add([
                        `<div class="emp-cell"><div class="emp-avatar" style="background:${color}">${initials}</div><div><p class="emp-name">${e.first_name} ${e.last_name}</p><p class="emp-id">${empId}</p></div></div>`,
                        `<span style="color:#15803d;font-weight:600">${e.present}</span>`,
                        `<span style="color:#8e1e18;font-weight:600">${e.late}</span>`,
                        `<span style="color:#a16207;font-weight:600">${e.absent}</span>`,
                        `<span class="pay-cell" style="color:#0b044d;font-weight:600">${e.ot_hours} hrs</span>`,
                        status,
                        `<div class="row-actions">
                            <button class="btn-view" onclick='openViewDTRModal(${JSON.stringify(modalData)})'>
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>`,
                    ]);
                });

                attendance_table.draw();
            });
        }

        function fetchAttendance() {
            const month = parseInt($('#scan-month-filter').val());
            const year  = parseInt($('#scan-year-filter').val());

            if (month === {{ now()->month }} && year === {{ now()->year }}) {
                location.reload();
                return;
            }

            $.get('{{ route('attendances.filterDetailedAttendance') }}', { month, year }, function(res) {
                scan_table.clear();

                res.attendances.forEach(function(a) {
                    const initials = (a.employee.first_name[0] + a.employee.last_name[0]).toUpperCase();
                    const color    = colors[a.employee.id % 5];
                    const empId    = 'EMP-' + String(a.employee.id).padStart(3, '0');

                    let statusBadge = '';
                    if (a.attendance_status === 'Present') {
                        statusBadge = '<span class="badge-status processed">Present</span>';
                    } else if (a.attendance_status === 'Late') {
                        statusBadge = '<span class="badge-status pending">Late</span>';
                    } else if (a.attendance_status === 'Absent') {
                        statusBadge = '<span class="badge-status on-hold">Absent</span>';
                    } else {
                        statusBadge = '<span class="badge-status on-hold">Missing</span>';
                    }

                    const breakInfo = a.break_start && a.break_end ? `${a.break_start} - ${a.break_end}` : 'N/A';
                    const otInfo = a.overtime_minutes > 0 ? (a.overtime_minutes / 60).toFixed(1) + ' hrs' : 'N/A';
                    const totalHours = ((a.total_minutes / 60) + (a.overtime_minutes / 60)).toFixed(2) + ' h';

                    const editModalData = {
                        employee_id: a.employee.id,
                        first_name: a.employee.first_name,
                        last_name: a.employee.last_name,
                        attendance_id: a.id,
                        date: a.raw_date,
                        time_in: a.time_in === '--:--' ? null : a.time_in,
                        time_out: a.time_out === '--:--' ? null : a.time_out,
                        break_start: a.break_start,
                        break_end: a.break_end,
                        overtime_in: a.overtime_in,
                        overtime_out: a.overtime_out,
                    };

                    scan_table.row.add([
                        `<input type="checkbox" class="row-check" value="${a.id}">`,
                        `<div class="emp-cell"><div class="emp-avatar" style="background:${color}">${initials}</div><div><p class="emp-name">${a.employee.first_name} ${a.employee.last_name}</p><p class="emp-id">${empId}</p></div></div>`,
                        a.date,
                        `<span class="dept-tag" style="background:#e8f9ef;color:#15803d;border-color:#bbf7d0">${a.time_in}</span>`,
                        `<span class="dept-tag" style="background:#fdf0ef;color:#8e1e18;border-color:#f5d0ce">${a.time_out}</span>`,
                        `<span style="font-size:12px;color:#9999bb">${breakInfo}</span>`,
                        `<span style="font-size:12px;color:#9999bb">${otInfo}</span>`,
                        `<span class="pay-cell">${totalHours}</span>`,
                        statusBadge,
                        `<div class="row-actions">
                            <button class="btn-edit" onclick='openEditDTRModal(${JSON.stringify(editModalData)})'>
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                            <form action="/admin/attendances/${a.id}" method="post" style="display:inline" onsubmit="return confirm('Archive this attendance record?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger" style="display:inline-flex;align-items:center;gap:4px">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2 2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                </button>
                            </form>
                        </div>`,
                    ]);
                });

                scan_table.draw();
            });
        }

        $('#ea-month-filter, #ea-year-filter').on('change', fetchEmployeeAttendance);
        $('#scan-month-filter, #scan-year-filter').on('change', fetchAttendance);
    });
</script>

<script>
    function openViewDTRModal(data) {
        const month = document.getElementById('ea-month-filter').value;
        const year = document.getElementById('ea-year-filter').value;

        const initials = (data.first_name[0] + data.last_name[0]).toUpperCase();
        const colors = ['#0b044d', '#8e1e18', '#15803d', '#a16207', '#7c3aed'];
        const color = colors[data.id % 5];

        document.getElementById('modal-avatar').innerText = initials;
        document.getElementById('modal-avatar').style.background = color;

        document.getElementById('modal-emp-id').innerText = 'EMP-' + String(data.id).padStart(3, '0');

        const badge = document.getElementById('modal-status-badge');
        badge.innerText = data.is_complete ? 'Complete' : 'Incomplete';
        badge.className = data.is_complete ? 'badge-status processed' : 'badge-status pending';

        document.getElementById('modal-name').textContent = `${data.first_name} ${data.last_name}`;
        document.getElementById('modal-position').textContent = `${data.position} · ${data.department}`;
        document.getElementById('modal-period').textContent = `DTR · ${month} ${year}`;
        document.getElementById('modal-present').textContent = `${data.present} ${data.present === 1 ? 'day' : 'days'}`;
        document.getElementById('modal-absent').textContent = `${data.absent} ${data.absent === 1 ? 'day' : 'days'}`;
        document.getElementById('modal-late').textContent = `${data.late} ${data.late === 1 ? 'day' : 'days'}`;
        document.getElementById('modal-overtime').textContent = `${data.overtime / 60} ${data.overtime === 1 ? 'hr' : 'hrs'}`;

        const rate = (data.present / 22 ) * 100;
        const rateColor = rate === 100 ? '#15803d' : rate === 0 ? '#8e1e18' : '#a16207';

        document.getElementById('modal-rate').innerText = rate.toFixed(0) + '%';
        document.getElementById('modal-rate').style.color = rateColor;

        document.getElementById('view-attendance-modal').style.display = 'flex';
    }

    function openEditDTRModal(data) {
        var url = "{{ route('attendances.update', ':id') }}".replace(':id', data.attendance_id);
        document.getElementById('edit-dtr-form').action = url;
        document.getElementById('edit-dtr-employee').textContent = `For ${data.first_name} ${data.last_name}`;

        const dateString = data.date;
        const date = new Date(dateString);

        const formattedDate = date.toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        });

        document.getElementById('edit-dtr-title').textContent = `EDIT DTR · ${formattedDate.toUpperCase()}`;

        document.getElementById('edit-dtr-employee_id').value = data.employee_id;
        document.getElementById('edit-dtr-attendance_id').value = data.attendance_id;
        document.getElementById('edit-dtr-date').value = data.date;

        document.getElementById('edit-attendance-modal').style.display = 'flex';
    }

    function fetchStats() {
        const month = document.getElementById('ea-month-filter').value;
        const year = document.getElementById('ea-year-filter').value;

        fetch(`{{ route('attendances.stats') }}?month=${month}&year=${year}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('stat-total-present').textContent = `${data.total_present ?? 0}`;
                document.getElementById('stat-total-absent').textContent = `${data.total_absent ?? 0}`;
                document.getElementById('stat-total-overtime').textContent = `${data.total_overtime / 60 ?? 0}`;
                const late = data.total_late ?? 0;
                document.getElementById('stat-total-late').textContent = `${late} ${late === 1 ? 'late arrival' : 'late arrivals'}`;

                const statMonths = document.querySelectorAll('p.stat-month');
                statMonths.forEach(el => {
                    el.textContent = `For ${data.stat_month}`;
                });
            });
    }

    document.getElementById('ea-month-filter').addEventListener('change', fetchStats);
    document.getElementById('ea-year-filter').addEventListener('change', fetchStats);
</script>
@endpush
