@extends('layouts.admin')

@section('page-content')

<div style="margin-bottom:20px">
    <a href="{{ route('employee_leaves.index') }}" class="auth-nav-back">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Back to Leave Management
    </a>
</div>

<div class="auth-card" style="max-width:600px">
    <div style="margin-bottom:24px">
        <h2 style="font-size:22px;font-weight:800;color:#0b044d;margin:0 0 6px">Add Leave Request</h2>
        <p style="font-size:13px;color:#6b6a8a;margin:0">Create a new employee leave record</p>
    </div>

    <form action="{{ route('employee_leaves.store') }}" method="post" class="auth-form">
        @csrf

        <div class="auth-field">
            <label>Employee <span style="color:#dc2626">*</span></label>
            <select name="employee_id" required>
                <option value="">Select employee</option>
                @foreach($employees as $employee)
                <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                    {{ $employee->first_name }} {{ $employee->last_name }}
                    (Balance: {{ $employee->leaveBalance->leave_balance }} days)
                </option>
                @endforeach
            </select>
        </div>

        <div class="auth-field">
            <label>Leave Type <span style="color:#dc2626">*</span></label>
            <select name="leave_type_id" required>
                <option value="">Select leave type</option>
                @forelse($leaveTypes as $leaveType)
                <option value="{{ $leaveType->id }}" {{ old('leave_type_id') == $leaveType->id ? 'selected' : '' }}>{{ $leaveType->leave_type }}</option>
                @empty
                @endforelse
            </select>
            @if($leaveTypes->isEmpty())
            <span style="font-size:11.5px;color:#8e1e18">No leave types available. <a href="{{ route('leave_types.create') }}" style="color:#8e1e18;font-weight:600">Add one here.</a></span>
            @endif
        </div>

        <div class="auth-row-2">
            <div class="auth-field">
                <label>Start Date <span style="color:#dc2626">*</span></label>
                <input type="date" name="start_date" value="{{ old('start_date') }}" required>
            </div>
            <div class="auth-field">
                <label>End Date <span style="color:#dc2626">*</span></label>
                <input type="date" name="end_date" value="{{ old('end_date') }}" required>
            </div>
        </div>

        <div class="auth-field">
            <label>Duration (days) <span style="color:#dc2626">*</span></label>
            <input type="number" name="leave_duration" value="{{ old('leave_duration') }}" min="1" required>
        </div>

        <div class="auth-field">
            <label>Reason <span style="color:#dc2626">*</span></label>
            <textarea name="leave_reason" rows="3" style="padding:10px 13px;border:1.5px solid #e0dff5;border-radius:9px;font-size:13.5px;color:#1a1a3a;background:#fafafe;outline:none;width:100%;box-sizing:border-box;font-family:'Poppins',sans-serif;resize:vertical" placeholder="Reason for leave" required>{{ old('leave_reason') }}</textarea>
        </div>

        <div class="auth-field">
            <label>Status <span style="color:#dc2626">*</span></label>
            <select name="leave_status" id="leave_status" required>
                <option value="">Select status</option>
                @foreach($leaveStatuses as $leaveStatus)
                <option value="{{ $leaveStatus->name }}" {{ old('leave_status') == $leaveStatus->name ? 'selected' : '' }}>{{ $leaveStatus->name }}</option>
                @endforeach
            </select>
        </div>

        <div id="decline_reason_wrap" style="display:none">
            <div class="auth-field">
                <label>Decline Reason</label>
                <textarea name="decline_reason" id="decline_reason_input" rows="2" style="padding:10px 13px;border:1.5px solid #e0dff5;border-radius:9px;font-size:13.5px;color:#1a1a3a;background:#fafafe;outline:none;width:100%;box-sizing:border-box;font-family:'Poppins',sans-serif;resize:vertical" placeholder="Reason for declining">{{ old('decline_reason') }}</textarea>
            </div>
        </div>

        <input type="hidden" value="{{ auth()->user()->id }}" name="user_id">

        <div style="display:flex;gap:12px;margin-top:24px">
            <a href="{{ route('employee_leaves.index') }}" class="pub-btn-ghost" style="flex:1;justify-content:center;text-decoration:none">Cancel</a>
            <button type="submit" class="pub-btn-primary" style="flex:1;justify-content:center">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Create Leave
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.getElementById('leave_status').addEventListener('change', function() {
        document.getElementById('decline_reason_wrap').style.display = this.value === 'Declined' ? 'block' : 'none';
    });
</script>
@endpush

@endsection
