@extends('layouts.admin')

@section('page-content')

<div class="welcome-banner">
    <div class="banner-left">
        <div class="banner-icon">
            <svg width="22" height="22" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div>
            <h2>Work Schedules</h2>
            <p>Manage employee work schedules and shifts</p>
        </div>
    </div>
    <div class="banner-right">
        <span class="banner-badge outline">{{ $schedules->count() }} Schedules</span>
    </div>
</div>

<div class="table-section">
    <div class="table-header">
        <div>
            <p class="table-title">Work Schedules</p>
            <p class="table-sub">Manage employee work schedules and shifts</p>
        </div>
        <div class="table-actions">
            <a href="{{ route('work_schedules.create') }}" class="modal-btn-primary">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Schedule
            </a>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="payroll-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Schedule Name</th>
                    <th>Work Hours</th>
                    <th>Break</th>
                    <th>Work Days</th>
                    <th>Grace Period</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($schedules as $schedule)
                <tr>
                    <td><span style="font-size:12px;color:#9999bb">{{ $loop->iteration }}</span></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div style="width:36px;height:36px;background:{{ ['#0b044d','#8e1e18','#15803d','#a16207','#7c3aed'][($schedule->id % 5)] }};border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </div>
                            <span style="font-size:13px;font-weight:600;color:#0b044d">{{ $schedule->name }}</span>
                        </div>
                    </td>
                    <td>
                        <span style="font-size:12.5px;color:#5a5888">
                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}
                        </span>
                        @if($schedule->pm_start_time)
                            <br><span style="font-size:11px;color:#9999bb">PM: {{ \Carbon\Carbon::parse($schedule->pm_start_time)->format('g:i A') }}</span>
                        @endif
                    </td>
                    <td><span class="dept-tag" style="background:#fefce8;color:#a16207;border-color:#fde68a">{{ $schedule->break_minutes }} min</span></td>
                    <td>
                        <span style="font-size:12px;color:#5a5888">
                            @foreach ($schedule->work_days as $day)
                                {{ $day }}@if (!$loop->last), @endif
                            @endforeach
                        </span>
                    </td>
                    <td><span class="dept-tag" style="background:#f0effe;color:#0b044d">{{ $schedule->grace_period_minutes }} min</span></td>
                    <td>
                        <div class="row-actions">
                            <a href="{{ route('work_schedules.edit', $schedule) }}" class="btn-edit">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                Edit
                            </a>
                            <form action="{{ route('work_schedules.destroy', $schedule) }}" method="post" style="display:inline" onsubmit="return confirm('Delete this schedule?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-view" style="color:#8e1e18;border-color:#f5d0ce">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="empty-state">
                        <svg width="48" height="48" fill="none" stroke="#d9d9ee" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <p style="font-size:14px;color:#9999bb;margin-top:12px">No schedules found</p>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection