@extends('layouts.admin')

@section('page-content')

<div style="margin-bottom:20px">
    <a href="{{ route('leave_types.index') }}" class="auth-nav-back">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Back to Leave Types
    </a>
</div>

<div class="auth-card" style="max-width:600px">
    <div style="margin-bottom:24px">
        <h2 style="font-size:22px;font-weight:800;color:#0b044d;margin:0 0 6px">Add Leave Type</h2>
        <p style="font-size:13px;color:#6b6a8a;margin:0">Create a new leave category for employees</p>
    </div>

    <form action="{{ route('leave_types.store') }}" method="post" class="auth-form">
        @csrf

        <div class="auth-field">
            <label>Leave Type <span style="color:#dc2626">*</span></label>
            <input type="text" name="leave_type" value="{{ old('leave_type') }}" placeholder="e.g. Vacation Leave" required>
        </div>

        <div class="auth-field">
            <label>Days Allowed <span style="color:#dc2626">*</span></label>
            <input type="number" name="days_of_leave" value="{{ old('days_of_leave') }}" placeholder="e.g. 15" min="1" required>
        </div>

        <div class="auth-field">
            <label>Status <span style="color:#dc2626">*</span></label>
            <select name="is_active" required>
                <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <input type="hidden" value="{{ auth()->user()->id }}" name="user_id">

        <div style="display:flex;gap:12px;margin-top:24px">
            <a href="{{ route('leave_types.index') }}" class="pub-btn-ghost" style="flex:1;justify-content:center;text-decoration:none">Cancel</a>
            <button type="submit" class="pub-btn-primary" style="flex:1;justify-content:center">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Create Leave Type
            </button>
        </div>
    </form>
</div>

@endsection
