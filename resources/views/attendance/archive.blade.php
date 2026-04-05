@extends('layouts.admin')

@section('page-content')

<div class="table-section">
    <div class="table-header">
        <div>
            <p class="table-title">Attendance Archive</p>
            <p class="table-sub">Archived attendance records</p>
        </div>
        <div class="table-actions">
            <a href="{{ route('attendances.index') }}" class="btn-export">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Back to Attendance
            </a>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="payroll-table">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Date</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>PM In/Out</th>
                    <th>Overtime</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($attendances as $attendance)
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
                    <td><span style="font-size:12.5px;color:#5a5888">{{ \Carbon\Carbon::parse($attendance->date)->format('M d, Y') }}</span></td>
                    <td><span class="dept-tag" style="background:#e8f9ef;color:#15803d;border-color:#bbf7d0">{{ $attendance->time_in ?? '--:--' }}</span></td>
                    <td><span class="dept-tag" style="background:#fdf0ef;color:#8e1e18;border-color:#f5d0ce">{{ $attendance->time_out ?? '--:--' }}</span></td>
                    <td><span style="font-size:12px;color:#9999bb">{{ $attendance->pm_in ?? '--' }} / {{ $attendance->pm_out ?? '--' }}</span></td>
                    <td><span style="font-size:12px;color:#9999bb">{{ $attendance->overtime_in ?? '--' }} / {{ $attendance->overtime_out ?? '--' }}</span></td>
                    <td>
                        <form action="{{ route('attendances.restore', $attendance->id) }}" method="post" style="display:inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn-edit">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.5"/></svg>
                                Restore
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="empty-state">
                        <svg width="48" height="48" fill="none" stroke="#d9d9ee" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        <p style="font-size:14px;color:#9999bb;margin-top:12px">No archived attendance records</p>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
