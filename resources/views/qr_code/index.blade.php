@extends('layouts.admin')

@php
$scheduleMap = $employees->mapWithKeys(function($e) {
    $ws = $e->employeeWorkSchedule?->workSchedule;
    return [$e->id => $ws ? [
        'time_in'  => $ws->start_time,
        'time_out' => $ws->end_time,
        'pm_in'    => $ws->pm_start_time,
    ] : null];
})->toJson();
$departments = $employees->pluck('department')->unique('id');
@endphp

@section('page-content')
<div class="welcome-banner">
    <div class="banner-left">
        <div class="banner-icon">
            <svg width="22" height="22" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><path d="M14 14h.01M14 17h.01M17 14h.01M17 17h.01M20 14h.01M20 17h.01M20 20h.01M17 20h.01M14 20h.01"/></svg>
        </div>
        <div>
            <h2>QR Code Management</h2>
            <p>Generate and manage employee attendance QR codes</p>
        </div>
    </div>
    <div class="banner-right">
        <span class="banner-badge outline">0 Generated</span>
    </div>
</div>

<div class="quick-actions-row">
    <a class="qa-btn modal-btn-primary" href="{{ route('qr-code.create') }}">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Generate QR Code
    </a>
    <a class="qa-btn" href="{{ route('qr-code.scan') }}" target="_blank">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 7V5a2 2 0 012-2h2m10 0h2a2 2 0 012 2v2m0 10v2a2 2 0 01-2 2h-2M7 21H5a2 2 0 01-2-2v-2M7 7h10v10H7z"/></svg>
        Scan QR Code
    </a>
    <a class="qa-btn" href="{{ route('qr-code.history') }}">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="m9 11 3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
        View History
    </a>
</div>

<div class="stats-grid stats-grid-4">
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
            <select id="qr-dept-filter" style="padding:7px 12px;border:1.5px solid #e4e3f0;border-radius:8px;font-size:12.5px;color:#0b044d;outline:none;background:#fff">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->name }}">{{ $dept->name }}</option>
                @endforeach
            </select>
            <select id="qr-status-filter" style="padding:7px 12px;border:1.5px solid #e4e3f0;border-radius:8px;font-size:12.5px;color:#0b044d;outline:none;background:#fff">
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
                    <th style="display:none">Department</th>
                    <th>Position</th>
                    <th>Work Schedule</th>
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
                    <td style="display:none">{{ $employee->department->name }}</td>
                    <td><span class="dept-tag" style="background:#f0effe;color:#0b044d;border-color:#dddcf0">{{ $employee->position->title }}</span></td>
                    <td>
                        @if($ws)
                            <span style="font-size:12px;color:#6b6a8a">{{ $ws->name }}<br>
                            <span style="color:#9999bb">{{ $ws->start_time }} – {{ $ws->end_time }}</span></span>
                        @else
                            <span style="font-size:12px;color:#9999bb">No schedule</span>
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
                        @if(isset($qrScans[$employee->id]))
                            <a href="{{ route('qr-code.show', $qrScans[$employee->id]->id) }}" class="action-btn view-btn">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                View QR
                            </a>
                        @else
                            <a href="{{ route('qr-code.create', ['employee' => $employee->id]) }}" class="action-btn edit-btn">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                Generate
                            </a>
                        @endif
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
    const table = $('#qr-table').DataTable({
        columnDefs: [{ orderable: false, targets: [5] }, { visible: false, targets: [1] }],
        pageLength: 25,
        language: { search: 'Search:', lengthMenu: 'Show _MENU_ entries', emptyTable: 'No employees found' },
        dom: 'rtip',
    });
    $('#qr-search').on('keyup', function () { table.search(this.value).draw(); });
    $('#qr-dept-filter').on('change', function () { table.column(1).search(this.value).draw(); });
    $('#qr-status-filter').on('change', function () { table.column(4).search(this.value).draw(); });
});
</script>
@endpush
