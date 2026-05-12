@extends('layouts.employee')

@section('page-content')

{{-- Welcome Banner --}}
<div class="welcome-banner">
    <div class="banner-left">
        <div class="banner-icon">
            <svg width="22" height="22" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        </div>
        <div>
            <h2>My Attendance</h2>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>
    </div>
    <div class="banner-right">
        <span class="banner-badge">
            <span class="banner-badge-dot"></span>
            {{ $currentMonth }}
        </span>
    </div>
</div>

{{-- Stats Grid --}}
<div class="stats-grid stats-grid-4">

    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Days Present</p>
            <div class="stat-icon-wrap" style="background:#e8f9ef">
                <svg width="17" height="17" fill="none" stroke="#15803d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $daysPresent }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#22c55e"></span>
            <p class="stat-sub">{{ $daysLate }} late {{ Str::plural('arrival', $daysLate) }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Days Absent</p>
            <div class="stat-icon-wrap" style="background:#fdf0ef">
                <svg width="17" height="17" fill="none" stroke="#8e1e18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $daysAbsent }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#8e1e18"></span>
            <p class="stat-sub">This month</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Overtime Hours</p>
            <div class="stat-icon-wrap" style="background:#f0effe">
                <svg width="17" height="17" fill="none" stroke="#0b044d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $overtimeHours }}h</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#0b044d"></span>
            <p class="stat-sub">This month</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Attendance Rate</p>
            <div class="stat-icon-wrap" style="background:#fefce8">
                <svg width="17" height="17" fill="none" stroke="#a16207" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $attendanceRate }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#f59e0b"></span>
            <p class="stat-sub">{{ now()->daysInMonth }} working days</p>
        </div>
    </div>

</div>

{{-- Daily Time Record Table --}}
<div class="table-section">
    <div class="table-header">
        <div>
            <p class="table-title">Daily Time Record</p>
            <p class="table-sub">{{ $currentMonth }} attendance records</p>
        </div>
        <form method="GET" action="{{ route('my_attendances.index') }}" class="table-actions">
            <div class="att-date-wrap">
                <svg width="13" height="13" fill="none" stroke="#9999bb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                <input type="date" name="start_date" value="{{ $startDate }}" class="att-date-input" placeholder="Start date">
            </div>
            <span style="color:#9999bb;font-size:12px">to</span>
            <div class="att-date-wrap">
                <svg width="13" height="13" fill="none" stroke="#9999bb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                <input type="date" name="end_date" value="{{ $endDate }}" class="att-date-input" placeholder="End date">
            </div>
            <button type="submit" class="modal-btn-primary" style="padding:7px 16px;font-size:12.5px">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                Filter
            </button>
            @if(($startDate ?? null) || ($endDate ?? null))
            <a href="{{ route('my_attendances.index') }}" class="btn-export">Clear</a>
            @endif
        </form>
    </div>

    <div class="table-wrapper">
        <table class="payroll-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Day</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>OT Hours</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $a)
                <tr>
                    <td style="font-weight:600;color:#0b044d;font-size:13px">
                        {{ \Carbon\Carbon::parse($a->date)->format('M d') }}
                    </td>
                    <td style="font-size:12.5px;color:#6b6a8a">
                        {{ \Carbon\Carbon::parse($a->date)->format('D') }}
                    </td>
                    <td style="font-size:13px;color:{{ $a->time_in ? '#0b044d' : '#9999bb' }}">
                        {{ $a->time_in ? \Carbon\Carbon::parse($a->time_in)->format('h:i A') : '—' }}
                    </td>
                    <td style="font-size:13px;color:{{ $a->time_out ? '#0b044d' : '#9999bb' }}">
                        {{ $a->time_out ? \Carbon\Carbon::parse($a->time_out)->format('h:i A') : '—' }}
                    </td>
                    <td style="font-size:13px;color:{{ $a->overtime_minutes > 0 ? '#0b044d' : '#9999bb' }};font-weight:{{ $a->overtime_minutes > 0 ? '600' : '400' }}">
                        {{ $a->overtime_minutes > 0 ? '+' . round($a->overtime_minutes / 60, 1) . 'h' : '—' }}
                    </td>
                    <td>
                        @if($a->attendance_status === \App\Enums\AttendanceStatus::Present->value)
                            <span class="badge-status processed">Present</span>
                        @elseif($a->attendance_status === \App\Enums\AttendanceStatus::Absent->value)
                            <span class="badge-status on-hold">Absent</span>
                        @elseif($a->attendance_status === \App\Enums\AttendanceStatus::Late->value)
                            <span class="badge-status pending">Late</span>
                        @else
                            <span class="badge-status pending">{{ $a->attendance_status }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:32px;color:#9999bb;font-size:13px">No attendance records found for this month.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
