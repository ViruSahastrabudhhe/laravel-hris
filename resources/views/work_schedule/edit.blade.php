@extends('layouts.admin')

@section('page-content')

<div style="margin-bottom:20px">
    <a href="{{ route('work_schedules.index') }}" class="auth-nav-back">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Back to Work Schedules
    </a>
</div>

<div class="auth-card" style="max-width:600px">
    <div style="margin-bottom:24px">
        <h2 style="font-size:22px;font-weight:800;color:#0b044d;margin:0 0 6px">Edit Work Schedule</h2>
        <p style="font-size:13px;color:#6b6a8a;margin:0">Update work schedule information</p>
    </div>

    <form action="{{ route('work_schedules.update', $work_schedule) }}" method="post" class="auth-form">
        @csrf
        @method('PUT')

        <div class="auth-field">
            <label>Schedule Name <span style="color:#dc2626">*</span></label>
            <input type="text" name="name" value="{{ old('name', $work_schedule->name) }}" placeholder="e.g. Regular 8-5" required>
        </div>

        <div class="auth-row-2">
            <div class="auth-field">
                <label>Start Time <span style="color:#dc2626">*</span></label>
                <input type="time" name="start_time" value="{{ old('start_time', $work_schedule->start_time) }}" required>
            </div>
            <div class="auth-field">
                <label>End Time <span style="color:#dc2626">*</span></label>
                <input type="time" name="end_time" value="{{ old('end_time', $work_schedule->end_time) }}" required>
            </div>
        </div>

        <div class="auth-row-2">
            <div class="auth-field">
                <label>PM Start Time <span style="color:#dc2626">*</span></label>
                <input type="time" name="pm_start_time" value="{{ old('pm_start_time', $work_schedule->pm_start_time) }}" required>
            </div>
            <div class="auth-field">
                <label>Break Minutes <span style="color:#dc2626">*</span></label>
                <input type="number" name="break_minutes" value="{{ old('break_minutes', $work_schedule->break_minutes) }}" placeholder="e.g. 60" min="0" required>
            </div>
        </div>

        <div class="auth-field">
            <label>Grace Period (minutes) <span style="color:#dc2626">*</span></label>
            <input type="number" name="grace_period_minutes" value="{{ old('grace_period_minutes', $work_schedule->grace_period_minutes) }}" placeholder="e.g. 15" min="0" required>
        </div>

        <div class="auth-field">
            <label>Work Days <span style="color:#dc2626">*</span></label>
            <div style="display:flex;flex-wrap:wrap;gap:10px;margin-top:6px">
                @foreach(['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'] as $day)
                <label style="display:flex;align-items:center;gap:6px;font-size:13px;font-weight:500;color:#0b044d;cursor:pointer">
                    <input type="checkbox" name="work_days[]" value="{{ $day }}"
                        {{ in_array($day, old('work_days', $work_schedule->work_days)) ? 'checked' : '' }}
                        style="width:15px;height:15px;accent-color:#0b044d">
                    {{ $day }}
                </label>
                @endforeach
            </div>
        </div>

        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

        <div style="display:flex;gap:12px;margin-top:24px">
            <a href="{{ route('work_schedules.index') }}" class="pub-btn-ghost" style="flex:1;justify-content:center;text-decoration:none">Cancel</a>
            <button type="submit" class="pub-btn-primary" style="flex:1;justify-content:center">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Update Schedule
            </button>
        </div>
    </form>
</div>

@endsection
