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

<div class="quick-actions-row">
    <a class="qa-btn modal-btn-primary" id="scan-qr-btn" href="#scan">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 7V5a2 2 0 012-2h2m10 0h2a2 2 0 012 2v2m0 10v2a2 2 0 01-2 2h-2M7 21H5a2 2 0 01-2-2v-2M7 7h10v10H7z"/></svg>
        Scan QR Code
    </a>
    <a class="qa-btn" id="add-attendance-btn" href="#attendance">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Import Attendance CSV
    </a>    
    <a class="qa-btn" href="{{ route('attendances.archive') }}">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="m9 11 3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
        View Archive
    </a>
    <a class="qa-btn"></a>
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
            <p class="stat-sub">For {{ config('app.month') }}</p>
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
            <p class="stat-sub">For {{ config('app.month') }}</p>
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
    <div class="table-header" style="margin-bottom: 20px;">
        <div>
            <p class="table-title">Attendance Records</p>
            <p class="table-sub">Track employee time and attendance</p>
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
                <input type="text" id="attendance-search" placeholder="Search attendance..." style="height:34px;padding:0 10px 0 30px;border:1.5px solid #e4e3f0;border-radius:8px;font-size:12.5px;font-family:'Poppins',sans-serif;color:#0b044d;background:#fafafe;outline:none;width:180px">
            </div>
            <select id="dept-filter" style="padding:7px 12px;border:1.5px solid #e4e3f0;border-radius:8px;font-size:12.5px;color:#0b044d;outline:none;background:#fff">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->name }}">{{ $dept->name }}</option>
                @endforeach
            </select>
            <select id="status-filter" style="padding:7px 12px;border:1.5px solid #e4e3f0;border-radius:8px;font-size:12.5px;color:#0b044d;outline:none;background:#fff">
                <option value="">All Status</option>
                <option value="Present">Present</option>
                <option value="Late">Late</option>
                <option value="Absent">Absent</option>
            </select>
            <!-- <a href="{{ route('attendances.archive') }}" class="btn-export">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="m9 11 3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                View Archive
            </a> -->
        </div>
    </div>

    <div class="table-wrapper">
        <table class="payroll-table" id="attendance-table">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all" title="Select all"></th>
                    <th>Employee</th>
                    <th style="display:none">Department</th>
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
                    <td style="display:none">{{ $attendance->employee->department->name }}</td>
                    <td>{{ $attendance->date }}</td>
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

{{-- Attendance Modal --}}
<div class="modal-overlay" id="attendance-modal" style="display:none" onclick="document.getElementById('attendance-modal').style.display='none';document.body.style.overflow=''">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">IMPORT ATTENDANCE</span>
                <h3 class="modal-title">Upload CSV File</h3>
            </div>
            <button class="modal-close" onclick="document.getElementById('attendance-modal').style.display='none';document.body.style.overflow=''">
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
                <button type="button" class="modal-btn-ghost" onclick="document.getElementById('attendance-modal').style.display='none';document.body.style.overflow=''">Cancel</button>
                <button type="submit" class="modal-btn-primary">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    Import CSV
                </button>
            </div>
        </form>
    </div>
</div>

{{-- QR Scanner Modal --}}
<div class="modal-overlay" id="qr-modal" style="display:none;">
    <div class="modal-box" style="max-width:500px;">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">ATTENDANCE SCANNER</span>
                <h3 class="modal-title">Scan QR Code</h3>
            </div>
            <button class="modal-close" onclick="stopQRScanner()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="modal-body">
            <div id="qr-reader-wrap" style="background:#f7f6ff;border-radius:12px;padding:10px;overflow:hidden">
                <div id="qr-reader" style="width:100%"></div>
            </div>
            <div id="scan-status" style="margin-top:16px;text-align:center">
                <p style="color:#9999bb;font-size:13px" id="status-text">Position QR code within the frame</p>
            </div>
            <div id="scan-success-card" style="display:none;margin-top:16px;background:#f0fdf4;border:1px solid #22c55e;border-radius:12px;padding:16px">
                <div style="display:flex;align-items:center;gap:10px;color:#15803d;font-weight:700;margin-bottom:8px">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    Scan Successful
                </div>
                <div id="scan-details" style="font-size:13px;color:#15803d;line-height:1.6"></div>
            </div>
            <div id="scan-error-card" style="display:none;margin-top:16px;background:#fef2f2;border:1px solid #ef4444;border-radius:12px;padding:16px">
                <p id="scan-error-text" style="color:#dc2626;font-size:13px;margin:0"></p>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="modal-btn-ghost" style="width:100%" onclick="stopQRScanner()">Close Scanner</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    let html5QrCode;

    function startQRScanner() {
        document.getElementById('qr-modal').style.display = 'flex';
        document.getElementById('scan-success-card').style.display = 'none';
        document.getElementById('scan-error-card').style.display = 'none';
        document.getElementById('status-text').textContent = 'Position QR code within the frame';
        
        html5QrCode = new Html5Qrcode("qr-reader");
        html5QrCode.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: { width: 250, height: 250 } },
            onScanSuccess,
            onScanError
        ).catch(err => {
            console.error("Camera error:", err);
            document.getElementById('status-text').innerHTML = '<span style="color:#dc2626">Camera error. Please check permissions.</span>';
        });
    }

    function stopQRScanner() {
        document.getElementById('qr-modal').style.display = 'none';
        
        if (html5QrCode) {
            try {
                html5QrCode.stop().then(() => {
                    html5QrCode.clear();
                }).catch(err => {
                    // If it wasn't running, stop() might fail, which is fine
                    console.warn("Scanner stop handled:", err);
                    html5QrCode.clear();
                });
            } catch (e) {
                console.error("Scanner exception:", e);
            }
        }
    }

    function onScanSuccess(decodedText, decodedResult) {
        if (html5QrCode) {
            html5QrCode.pause(true);
        }
        
        document.getElementById('status-text').textContent = 'Processing...';
        
        fetch('/api/qr-scanner/scan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ qr_data: decodedText })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showScanSuccess(data);
            } else {
                showScanError(data.message);
                if (html5QrCode) html5QrCode.resume();
            }
        })
        .catch(error => {
            showScanError('Network error: ' + error.message);
            if (html5QrCode) html5QrCode.resume();
        });
    }

    function onScanError(errorMessage) {
        // Standard scanning errors can be ignored
    }

    function showScanSuccess(data) {
        document.getElementById('scan-error-card').style.display = 'none';
        const card = document.getElementById('scan-success-card');
        card.style.display = 'block';
        
        document.getElementById('scan-details').innerHTML = `
            <strong>Employee:</strong> ${data.data.employee_name}<br>
            <strong>Time In:</strong> ${data.data.time_in || 'N/A'}<br>
            <strong>Time Out:</strong> ${data.data.time_out || 'N/A'}<br>
            <strong>Status:</strong> Attendance recorded.
        `;
        
        document.getElementById('status-text').innerHTML = '<span style="color:#15803d;font-weight:700">✓ RECORDED</span>';
        
        setTimeout(() => {
            location.reload();
        }, 2500);
    }

    function showScanError(message) {
        document.getElementById('scan-success-card').style.display = 'none';
        const card = document.getElementById('scan-error-card');
        card.style.display = 'block';
        document.getElementById('scan-error-text').textContent = message;
        document.getElementById('status-text').textContent = 'Try again';
    }

    $('#add-attendance-btn').on('click', function () {
        document.getElementById('attendance-modal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    });

    $('#scan-qr-btn').on('click', function () {
        startQRScanner();
    });

    $(document).on('keydown', function (e) {
        if (e.key === 'Escape') {
            document.getElementById('attendance-modal').style.display = 'none';
            document.body.style.overflow = '';
            if (document.getElementById('qr-modal').style.display === 'flex') {
                stopQRScanner();
            }
        }
    });

    $(function () {
        const table = $('#attendance-table').DataTable({
            columnDefs: [{ orderable: false, targets: [0, 10] }, { visible: false, targets: [2] }],
            pageLength: 25,
            language: { search: 'Search:', lengthMenu: 'Show _MENU_ entries', emptyTable: 'No attendance records found', },
            dom: 'rtip',
        });

        $('#attendance-search').on('keyup', function() {
            table.search(this.value).draw();
        });

        $('#dept-filter').on('change', function() {
            table.column(2).search(this.value).draw();
        });

        $('#status-filter').on('change', function() {
            table.column(9).search(this.value).draw();
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