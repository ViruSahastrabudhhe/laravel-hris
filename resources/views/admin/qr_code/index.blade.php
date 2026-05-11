@extends('layouts.admin')
@php $hideChat = true; @endphp

@php
$departments   = $employees->pluck('department')->filter()->unique('id');
$totalEmp      = $employees->count();
$totalGenerated = count($qrScans);
$totalActive   = collect($qrScans)->filter(fn($q) => !$q->isExpired())->count();
$totalExpired  = collect($qrScans)->filter(fn($q) => $q->isExpired())->count();
@endphp

@push('styles')
    @vite('resources/css/admin/adminAttendance.css')
@endpush

@section('page-content')

<div class="welcome-banner">
    <div class="banner-left">
        <div class="banner-icon">
            <svg width="22" height="22" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><path d="M14 14h.01M14 17h.01M17 14h.01M17 17h.01M20 14h.01M20 17h.01M20 20h.01M17 20h.01M14 20h.01"/></svg>
        </div>
        <div>
            <h2>QR Code Management</h2>
            <p>{{ now()->format('l, F j, Y') }} &nbsp;·&nbsp; Employee Attendance QR Codes</p>
        </div>
    </div>
    <div class="banner-right">
        <div class="recruit-search-wrap">
            <svg width="13" height="13" fill="none" stroke="#9999bb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" id="banner-search" placeholder="Search employees..." class="recruit-search" oninput="filterQrTable(this.value)">
        </div>
    </div>
</div>

<div class="stats-grid stats-grid-4" style="margin-bottom:24px">
    <div class="stat-card" style="--accent-color:#0b044d">
        <div class="stat-top">
            <p class="stat-label">Total Employees</p>
            <div class="stat-icon-wrap" style="background:#f0effe">
                <svg width="17" height="17" fill="none" stroke="#0b044d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $totalEmp }}</p>
        <div class="stat-footer"><span class="stat-dot" style="background:#0b044d"></span><p class="stat-sub">Active employees</p></div>
    </div>

    <div class="stat-card" style="--accent-color:#15803d">
        <div class="stat-top">
            <p class="stat-label">QR Codes Generated</p>
            <div class="stat-icon-wrap" style="background:#e8f9ef">
                <svg width="17" height="17" fill="none" stroke="#15803d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $totalGenerated }}</p>
        <div class="stat-footer"><span class="stat-dot" style="background:#15803d"></span><p class="stat-sub">This month</p></div>
    </div>

    <div class="stat-card" style="--accent-color:#d97706">
        <div class="stat-top">
            <p class="stat-label">Active QR Codes</p>
            <div class="stat-icon-wrap" style="background:#fef3c7">
                <svg width="17" height="17" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $totalActive }}</p>
        <div class="stat-footer"><span class="stat-dot" style="background:#f59e0b"></span><p class="stat-sub">Valid codes</p></div>
    </div>

    <div class="stat-card" style="--accent-color:#8e1e18">
        <div class="stat-top">
            <p class="stat-label">Expired QR Codes</p>
            <div class="stat-icon-wrap" style="background:#fdf0ef">
                <svg width="17" height="17" fill="none" stroke="#8e1e18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $totalExpired }}</p>
        <div class="stat-footer"><span class="stat-dot" style="background:#ef4444"></span><p class="stat-sub">This month</p></div>
    </div>
</div>

<div class="table-section">
    <div class="table-header">
        <div>
            <p class="table-title">Employee QR Status</p>
            <p class="table-sub">{{ config('app.name') }} &nbsp;·&nbsp; <span id="visible-count">{{ $totalEmp }}</span> of {{ $totalEmp }} employees</p>
        </div>
        <div class="table-actions">
            <select class="filter-select" id="qr-dept-filter">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->name }}">{{ $dept->name }}</option>
                @endforeach
            </select>
            <select class="filter-select" id="qr-status-filter">
                <option value="">All Status</option>
                <option value="generated">Generated</option>
                <option value="not generated">Not Generated</option>
            </select>
            <a href="{{ route('qr-code.scan') }}" target="_blank" class="btn-export">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 7V5a2 2 0 012-2h2m10 0h2a2 2 0 012 2v2m0 10v2a2 2 0 01-2 2h-2M7 21H5a2 2 0 01-2-2v-2M7 7h10v10H7z"/></svg>
                Scan QR
            </a>
            <button class="modal-btn-primary" onclick="openGenerateModal()">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Generate QR
            </button>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="payroll-table" id="qr-table">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Department</th>
                    <th>Position</th>
                    <th>Work Schedule</th>
                    <th>Expiry Date</th>
                    <th>QR Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $employee)
                @php
                    $ws  = $employee->employeeWorkSchedule?->workSchedule;
                    $qr  = $qrScans[$employee->id] ?? null;
                    $expired = $qr ? $qr->isExpired() : false;
                @endphp
                <tr data-dept="{{ strtolower($employee->department->name ?? '') }}"
                    data-qrstatus="{{ $qr ? 'generated' : 'not generated' }}">
                    <td>
                        <div class="emp-cell">
                            <div class="emp-avatar" style="background:{{ ['#0b044d','#8e1e18','#15803d','#a16207','#7c3aed'][($employee->id % 5)] }}">
                                {{ strtoupper(substr($employee->first_name,0,1).substr($employee->last_name,0,1)) }}
                            </div>
                            <div>
                                <p class="emp-name">{{ $employee->first_name }} {{ $employee->last_name }}</p>
                                <p class="emp-id">EMP-{{ str_pad($employee->id, 3, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>
                    </td>
                    <td><span class="dept-tag">{{ $employee->department->name ?? '—' }}</span></td>
                    <td><span class="dept-tag" style="background:#f0effe;color:#0b044d;border-color:#dddcf0">{{ $employee->position->title ?? '—' }}</span></td>
                    <td>
                        @if($ws)
                            <span style="font-size:12px;color:#6b6a8a">{{ $ws->name }}<br>
                            <span style="color:#9999bb">{{ \Carbon\Carbon::parse($ws->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($ws->end_time)->format('H:i') }}</span></span>
                        @else
                            <span style="font-size:12px;color:#9999bb">No schedule</span>
                        @endif
                    </td>
                    <td>
                        @if($qr && $qr->expires_at)
                            <span style="font-size:12.5px;color:{{ $expired ? '#dc2626' : '#15803d' }};font-weight:600">
                                {{ $qr->expires_at->format(config('app.day_month')) }}
                            </span>
                        @else
                            <span style="color:#9ca3af">—</span>
                        @endif
                    </td>
                    <td>
                        @if($qr)
                            <span class="badge-status processed">Generated</span>
                        @else
                            <span class="badge-status on-hold">Not Generated</span>
                        @endif
                    </td>
                    <td>
                        <div class="row-actions">
                            @if($qr)
                                <button class="btn-view" onclick="openViewQRModal(
                                    {{ $qr->id }},
                                    '{{ addslashes($qr->qr_code_hash) }}',
                                    {{ $employee->id }},
                                    '{{ addslashes($employee->first_name . ' ' . $employee->last_name) }}',
                                    '{{ $qr->expires_at->format('F d, Y') }}',
                                    @json($qrData[$employee->id] ?? '')
                                )">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            @else
                                <button class="btn-edit" onclick="openGenerateModal({{ $employee->id }}, '{{ addslashes($employee->first_name . ' ' . $employee->last_name) }}')">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><path d="M14 14h.01M14 17h.01M17 14h.01M17 17h.01M20 14h.01M20 17h.01M20 20h.01M17 20h.01M14 20h.01"/></svg>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div id="empty-state" style="display:none;text-align:center;padding:40px 20px">
            <p style="font-size:13px;color:#9999bb;margin:0">No employees match your criteria</p>
        </div>
    </div>
</div>

{{-- Generate QR Modal --}}
<div class="modal-overlay" id="generate-modal" style="display:none" onclick="closeModal('generate-modal')">
    <div class="modal-box" style="max-width:460px" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">QR CODE</span>
                <h3 class="modal-title">Generate QR Code</h3>
                <p class="modal-sub" id="generate-modal-sub">Select an employee to generate a monthly QR code</p>
            </div>
            <button class="modal-close" onclick="closeModal('generate-modal')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form action="{{ route('qr-code.generate') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-field">
                    <label>Employee <span style="color:#dc2626">*</span></label>
                    <select name="employee_id" id="generate-employee-select" required>
                        <option value="">Select employee</option>
                        @foreach($employees as $emp)
                            @if(!isset($qrScans[$emp->id]))
                                <option value="{{ $emp->id }}">EMP-{{ str_pad($emp->id,3,'0',STR_PAD_LEFT) }} — {{ $emp->first_name }} {{ $emp->last_name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div style="background:#f7f6ff;border-radius:10px;padding:12px 14px;font-size:12px;color:#6b6a8a;margin-top:4px">
                    A monthly QR code will be generated and valid until end of the current month.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn-ghost" onclick="closeModal('generate-modal')">Cancel</button>
                <button type="submit" class="modal-btn-primary">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><path d="M14 14h.01M14 17h.01M17 14h.01M17 17h.01M20 14h.01M20 17h.01M20 20h.01M17 20h.01M14 20h.01"/></svg>
                    Generate QR Code
                </button>
            </div>
        </form>
    </div>
</div>

{{-- View QR Modal --}}
<div class="modal-overlay" id="view-qr-modal" style="display:none" onclick="closeViewQRModal()">
    <div class="modal-box" style="max-width:480px" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">EMPLOYEE QR CODE</span>
                <h3 class="modal-title" id="view-qr-name"></h3>
                <p class="modal-sub" id="view-qr-empid-sub"></p>
            </div>
            <button class="modal-close" onclick="closeViewQRModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="modal-body" style="text-align:center">
            <div style="background:#f7f6ff;border-radius:14px;padding:28px;display:inline-block;margin-bottom:16px">
                <div style="background:#fff;padding:16px;border-radius:10px;box-shadow:0 2px 8px rgba(11,4,77,0.08)">
                    <div id="view-qrcode"></div>
                </div>
            </div>
            <div style="text-align:left;background:#f7f6ff;border-radius:10px;padding:16px">
                <div class="modal-row"><span>Employee ID</span><strong id="view-qr-empid"></strong></div>
                <div class="modal-row"><span>Valid Until</span><strong id="view-qr-expires" style="color:#15803d"></strong></div>
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

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    /* ── Table filtering ── */
    function filterQrTable(q) {
        const query  = (q || '').toLowerCase();
        const dept   = document.getElementById('qr-dept-filter').value.toLowerCase();
        const status = document.getElementById('qr-status-filter').value.toLowerCase();
        let count = 0;
        document.querySelectorAll('#qr-table tbody tr').forEach(row => {
            const text       = row.textContent.toLowerCase();
            const matchQuery  = !query  || text.includes(query);
            const matchDept   = !dept   || (row.dataset.dept   || '') === dept;
            const matchStatus = !status || (row.dataset.qrstatus || '') === status;
            const show = matchQuery && matchDept && matchStatus;
            row.style.display = show ? '' : 'none';
            if (show) count++;
        });
        document.getElementById('visible-count').textContent = count;
        document.getElementById('empty-state').style.display = count === 0 ? 'block' : 'none';
    }

    document.getElementById('qr-dept-filter').addEventListener('change',   () => filterQrTable(document.getElementById('banner-search').value));
    document.getElementById('qr-status-filter').addEventListener('change', () => filterQrTable(document.getElementById('banner-search').value));

    /* ── DataTable (sorting only) ── */
    $(function () {
        $('#qr-table').DataTable({
            columnDefs: [{ orderable: false, targets: [6] }],
            pageLength: 25,
            language: { lengthMenu: 'Show _MENU_ entries', emptyTable: 'No employees found' },
            dom: 'rtip',
        });
    });

    /* ── Generate modal ── */
    function openGenerateModal(employeeId, employeeName) {
        const select = document.getElementById('generate-employee-select');
        const sub    = document.getElementById('generate-modal-sub');
        if (employeeId) {
            select.value = employeeId;
            sub.textContent = 'Generating QR code for ' + employeeName;
        } else {
            select.value = '';
            sub.textContent = 'Select an employee to generate a monthly QR code';
        }
        document.getElementById('generate-modal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    /* ── View QR modal ── */
    let _qrInstance = null, _qrPrintInstance = null;
    let _qrName = '', _qrEmpId = '', _qrExpires = '';

    function openViewQRModal(qrId, hash, empId, empName, expires, qrData) {
        _qrName    = empName;
        _qrEmpId   = 'EMP-' + String(empId).padStart(3, '0');
        _qrExpires = expires;

        document.getElementById('view-qr-name').textContent      = empName;
        document.getElementById('view-qr-empid-sub').textContent  = _qrEmpId;
        document.getElementById('view-qr-empid').textContent      = _qrEmpId;
        document.getElementById('view-qr-expires').textContent    = expires;

        document.getElementById('view-qrcode').innerHTML       = '';
        document.getElementById('view-qrcode-print').innerHTML = '';

        const text = qrData || JSON.stringify({ id: qrId, hash: hash, employee_id: empId });
        _qrInstance = new QRCode(document.getElementById('view-qrcode'), {
            text, width: 220, height: 220,
            colorDark: '#0b044d', colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });
        _qrPrintInstance = new QRCode(document.getElementById('view-qrcode-print'), {
            text, width: 1024, height: 1024,
            colorDark: '#0b044d', colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });

        document.body.style.paddingRight = (window.innerWidth - document.documentElement.clientWidth) + 'px';
        document.getElementById('view-qr-modal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeViewQRModal() {
        document.getElementById('view-qr-modal').style.display = 'none';
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }

    function printViewQR() {
        setTimeout(function () {
            var img = document.querySelector('#view-qrcode-print img') || document.querySelector('#view-qrcode-print canvas');
            var src = img.tagName === 'CANVAS' ? img.toDataURL() : img.src;
            var win = window.open('', '_blank');
            win.document.write('<html><head><title>QR Code - ' + _qrName + '</title><style>*{margin:0;padding:0;box-sizing:border-box}body{display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:100vh;font-family:sans-serif;background:#fff}img{width:90vmin;height:90vmin;image-rendering:pixelated}p{margin-top:16px;font-size:18px;font-weight:700;color:#0b044d}small{color:#666;font-size:13px}@media print{@page{margin:0}}</style></head><body><img src="' + src + '"><p>' + _qrName + '</p><small>' + _qrEmpId + ' &mdash; Valid until ' + _qrExpires + '</small></body></html>');
            win.document.close();
            win.focus();
            win.onload = function () { win.print(); };
        }, 300);
    }
</script>
@endpush
