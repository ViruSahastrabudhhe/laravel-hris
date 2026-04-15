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
@endphp

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

<div class="stats-grid stats-grid-4">

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
            <p class="stat-sub">For {{ now()->format('F, Y') }}</p>
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
            <p class="stat-sub">{{ now()->format('M Y') }}</p>
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

<div class="table-section">
    <div class="table-header">
        <div>
            <p class="table-title">Attendance Records</p>
            <p class="table-sub">Track employee time and attendance</p>
        </div>
        <div class="table-actions">
            <form id="bulk-archive-form" action="{{ route('attendances.bulkDestroy') }}" method="POST" style="display:inline">
                @csrf
                @method('DELETE')
                <div id="bulk-ids"></div>
                <button type="submit" id="bulk-btn" class="btn-export" style="display:none;color:#8e1e18;border-color:#f5d0ce" onclick="return confirm('Archive selected records?')">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    Archive Selected (<span id="bulk-count">0</span>)
                </button>
            </form>
            <a href="{{ route('attendances.archive') }}" class="btn-export">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="m9 11 3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                View Archive
            </a>
            <a href="{{ route('attendances.create') }}" class="modal-btn-primary">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Attendance
            </a>
        </div>
    </div>

    <div class="table-wrapper" style="padding: 20px 20px 16px">
        <table class="payroll-table" id="attendance-table">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all" title="Select all"></th>
                    <th>#</th>
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
                    <td><span style="font-size:12px;color:#9999bb">{{ $loop->iteration }}</span></td>
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
                    <td><span style="font-size:12.5px;color:#5a5888">{{ \Carbon\Carbon::parse($attendance->date)->format('M d, Y') }}</span></td>
                    <td><span class="dept-tag" style="background:#e8f9ef;color:#15803d;border-color:#bbf7d0">{{ $attendance->time_in ?? '--:--' }}</span></td>
                    <td><span class="dept-tag" style="background:#fdf0ef;color:#8e1e18;border-color:#f5d0ce">{{ $attendance->time_out ?? '--:--' }}</span></td>
                    <td><span style="font-size:12px;color:#9999bb">{{ $attendance->break_start && $attendance->break_end ? $attendance->break_start . ' - ' . $attendance->break_end : 'N/A' }}</span></td>
                    <td><span style="font-size:12px;color:#9999bb">{{ $attendance->overtime_in && $attendance->overtime_out ? $attendance->overtime_in . ' - ' . $attendance->overtime_out : 'N/A' }}</span></td>
                    <td><span class="pay-cell">{{ number_format((($attendance->total_minutes / 60) + ($attendance->overtime_minutes / 60)), 2) }} h</span></td>
                    <td>
                        @if($attendance->attendance_status === 'Present')
                            <span class="badge-status processed">Present</span>
                        @elseif($attendance->attendance_status === 'Late')
                            <span class="badge-status pending">Late</span>
                        @else
                            <span class="badge-status on-hold">{{ $attendance->attendance_status }}</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('attendances.destroy', $attendance) }}" method="post" style="display:inline" onsubmit="return confirm('Archive this attendance record?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-view" style="color:#8e1e18;border-color:#f5d0ce">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                Archive
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(function () {
    $('#attendance-table').DataTable({
        columnDefs: [{ orderable: false, targets: [0, 10] }],
        pageLength: 25,
        language: { search: 'Search:', lengthMenu: 'Show _MENU_ entries', emptyTable: 'No attendance records found', },
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
});
</script>
@endpush