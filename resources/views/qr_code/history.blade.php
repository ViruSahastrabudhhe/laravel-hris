@extends('layouts.admin')

@section('page-content')
<div style="margin-bottom:20px">
    <h2 style="color:#0b044d;font-size:24px;font-weight:700;margin:0">QR Scan History</h2>
    <p style="color:#9999bb;font-size:14px;margin:4px 0 0">View all scanned attendance QR codes</p>
</div>

<div class="table-section">
    <div class="table-header">
        <div>
            <p class="table-title">Scan History</p>
            <p class="table-sub">{{ $scans->total() }} total scans</p>
        </div>
        <div class="table-actions">
            <a href="{{ route('qr-code.scan') }}">
                <button class="btn-export">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
                    Scan QR Code
                </button>
            </a>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Employee</th>
                    <th>Date</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>PM In/Out</th>
                    <th>Overtime</th>
                    <th>Scanned At</th>
                    <th>Scanner IP</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($scans as $scan)
                <tr>
                    <td>{{ $scan->id }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div style="background:{{ ['#0b044d','#8e1e18','#15803d','#a16207','#7c3aed'][($scan->employee_id % 5)] }};width:32px;height:32px;border-radius:8px;font-size:12px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700">
                                {{ strtoupper(substr($scan->employee->first_name,0,1).substr($scan->employee->last_name,0,1)) }}
                            </div>
                            <div>
                                <p style="font-weight:600;color:#0b044d;font-size:13px;margin:0">{{ $scan->employee->first_name }} {{ $scan->employee->last_name }}</p>
                                <p style="font-size:11px;color:#9999bb;margin:2px 0 0">EMP-{{ str_pad($scan->employee_id, 3, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>
                    </td>
                    <td>{{ $scan->date->format('M d, Y') }}</td>
                    <td>{{ $scan->time_in ?? '-' }}</td>
                    <td>{{ $scan->time_out ?? '-' }}</td>
                    <td>
                        @if($scan->pm_in || $scan->pm_out)
                            {{ $scan->pm_in ?? '-' }} / {{ $scan->pm_out ?? '-' }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($scan->overtime_in || $scan->overtime_out)
                            {{ $scan->overtime_in ?? '-' }} / {{ $scan->overtime_out ?? '-' }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($scan->scanned_at)
                            {{ $scan->scanned_at->format('M d, Y H:i') }}
                        @else
                            <span style="color:#f59e0b;font-weight:600">Pending</span>
                        @endif
                    </td>
                    <td>{{ $scan->scanner_ip ?? '-' }}</td>
                    <td>
                        @if($scan->scanned_at)
                            <span class="badge-status processed">Scanned</span>
                        @else
                            <span class="badge-status pending">Pending</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align:center;padding:40px;color:#9999bb">
                        No scan history found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($scans->hasPages())
    <div style="padding:20px;border-top:1px solid #f0effe">
        {{ $scans->links() }}
    </div>
    @endif
</div>

@push('styles')
<style>
.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table thead {
    background: #f7f6ff;
}

.data-table th {
    padding: 14px 16px;
    text-align: left;
    font-size: 11px;
    font-weight: 700;
    color: #9999bb;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.data-table td {
    padding: 14px 16px;
    border-bottom: 1px solid #f0effe;
    font-size: 13px;
    color: #0b044d;
}

.data-table tbody tr:hover {
    background: #fafafe;
}

.badge-status {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-status.processed {
    background: #f0fdf4;
    color: #15803d;
}

.badge-status.pending {
    background: #fefce8;
    color: #a16207;
}
</style>
@endpush
@endsection
