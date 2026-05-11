@extends('layouts.admin')
@php $hideChat = true; @endphp

@push('styles')
    @vite('resources/css/admin/adminAttendance.css')
@endpush

@section('page-content')

<div class="welcome-banner">
    <div class="banner-left">
        <div class="banner-icon">
            <svg width="22" height="22" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="m9 16 2 2 4-4"/></svg>
        </div>
        <div>
            <h2>Attendance Archive</h2>
            <p>{{ now()->format('l, F j, Y') }} &nbsp;·&nbsp; Archived Time Records</p>
        </div>
    </div>
    <div class="banner-right">
        <div class="recruit-search-wrap">
            <svg width="13" height="13" fill="none" stroke="#9999bb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" id="archive-search" placeholder="Search archive..." class="recruit-search" oninput="filterArchive()">
        </div>
    </div>
</div>

<div class="table-section">
    <div class="table-header">
        <div>
            <p class="table-title">Attendance Archive</p>
            <p class="table-sub">{{ config('app.name') }} &nbsp;·&nbsp; <span id="visible-count">{{ $attendances->count() }}</span> of {{ $attendances->count() }} records</p>
        </div>
        <div class="table-actions">
            <form id="bulk-restore-form" action="{{ route('attendances.bulkRestore') }}" method="POST" style="display:inline">
                @csrf
                @method('PUT')
                <div id="bulk-ids"></div>
                <button type="submit" id="bulk-btn" class="btn-edit" style="display:none;" onclick="return confirm('Restore selected records?')">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.5"/></svg>
                    Restore Selected (<span id="bulk-count">0</span>)
                </button>
            </form>
            <input type="date" id="archive-date-start" class="filter-select" title="From date">
            <input type="date" id="archive-date-end" class="filter-select" title="To date">
            <select class="filter-select" id="archive-status-filter">
                <option value="">All Status</option>
                <option value="Present">Present</option>
                <option value="Late">Late</option>
                <option value="Absent">Absent</option>
            </select>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="payroll-table" id="attendance-table">
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
            @forelse($attendances as $attendance)
                <tr data-date="{{ $attendance->date }}" data-status="{{ strtolower($attendance->attendance_status) }}">
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
                    <td><span class="date-cell">{{ \Carbon\Carbon::parse($attendance->date)->format(config('app.day_month')) }}</span></td>
                    <td><span class="dept-tag" style="background:#e8f9ef;color:#15803d;border-color:#bbf7d0">{{ $attendance->time_in ?? '--:--' }}</span></td>
                    <td><span class="dept-tag" style="background:#fdf0ef;color:#8e1e18;border-color:#f5d0ce">{{ $attendance->time_out ?? '--:--' }}</span></td>
                    <td><span class="scan-break">{{ $attendance->break_start && $attendance->break_end ? $attendance->break_start . ' - ' . $attendance->break_end : 'N/A' }}</span></td>
                    <td><span class="scan-overtime">{{ $attendance->overtime_in && $attendance->overtime_out ? $attendance->overtime_in . ' - ' . $attendance->overtime_out : 'N/A' }}</span></td>
                    <td><span class="pay-cell">{{ number_format($attendance->total_minutes / 60, 2) }}h</span></td>
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
                        <form action="{{ route('attendances.restore', $attendance->id) }}" method="post" style="display:inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn-edit">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.5"/></svg>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
            @endforelse
            </tbody>
        </table>
        <div id="empty-state" class="archive-empty" style="display:none">
            <p>No records match your criteria</p>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(function () {
    $('#attendance-table').DataTable({
        columnDefs: [{ orderable: false, targets: [0, 9] }],
        pageLength: 25,
        language: { lengthMenu: 'Show _MENU_ entries', emptyTable: 'No archived attendance records found' },
        dom: 'rtip',
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

function filterArchive() {
    const query     = (document.getElementById('archive-search').value || '').toLowerCase();
    const dateStart = document.getElementById('archive-date-start').value;
    const dateEnd   = document.getElementById('archive-date-end').value;
    const status    = document.getElementById('archive-status-filter').value.toLowerCase();
    let count = 0;
    document.querySelectorAll('#attendance-table tbody tr').forEach(row => {
        const text = row.textContent.toLowerCase();
        const d = row.dataset.date || '';
        const matchSearch = !query || text.includes(query);
        const matchDate   = (!dateStart || d >= dateStart) && (!dateEnd || d <= dateEnd);
        const matchStatus = !status || (row.dataset.status || '') === status;
        const show = matchSearch && matchDate && matchStatus;
        row.style.display = show ? '' : 'none';
        if (show) count++;
    });
    document.getElementById('visible-count').textContent = count;
    document.getElementById('empty-state').style.display = count === 0 ? 'block' : 'none';
}

document.getElementById('archive-date-start').addEventListener('change', filterArchive);
document.getElementById('archive-date-end').addEventListener('change', filterArchive);
document.getElementById('archive-status-filter').addEventListener('change', filterArchive);
</script>
@endpush
