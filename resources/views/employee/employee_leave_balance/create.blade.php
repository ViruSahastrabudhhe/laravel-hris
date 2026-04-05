@extends('layouts.admin')

@section('page-content')

<div style="margin-bottom:20px">
    <a href="{{ route('leave_balances.index') }}" class="auth-nav-back">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Back to Leave Balances
    </a>
</div>

<div class="auth-card" style="max-width:600px">
    <div style="margin-bottom:24px">
        <h2 style="font-size:22px;font-weight:800;color:#0b044d;margin:0 0 6px">Add Leave Balance</h2>
        <p style="font-size:13px;color:#6b6a8a;margin:0">Allocate leave balance for an employee</p>
    </div>

    <form action="{{ route('leave_balances.store') }}" method="post" class="auth-form">
        @csrf

        <div class="auth-field">
            <label>Employee <span style="color:#dc2626">*</span></label>
            <select name="employee_id" required>
                <option value="">Select employee</option>
                @foreach($employees as $employee)
                <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->first_name }} {{ $employee->last_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="auth-row-2">
            <div class="auth-field">
                <label>Lates (deducts 0.25/late)</label>
                <input type="number" id="lates" placeholder="0" min="0" value="0">
            </div>
            <div class="auth-field">
                <label>Absences (deducts 1/absence)</label>
                <input type="number" id="absences" placeholder="0" min="0" value="0">
            </div>
        </div>

        <div class="auth-field">
            <label>Leave Balance <span style="color:#dc2626">*</span></label>
            <input type="number" name="leave_balance" id="leave_balance" step="0.25" readonly required
                style="background:#f0effe;color:#0b044d;font-weight:600">
        </div>

        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

        <div style="display:flex;gap:12px;margin-top:24px">
            <a href="{{ route('leave_balances.index') }}" class="pub-btn-ghost" style="flex:1;justify-content:center;text-decoration:none">Cancel</a>
            <button type="submit" class="pub-btn-primary" style="flex:1;justify-content:center">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Create Leave Balance
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const leaveBalanceInput = document.getElementById('leave_balance');
    leaveBalanceInput.value = 15;

    function calculateBalance() {
        const lates = parseFloat(document.getElementById('lates').value) || 0;
        const absences = parseFloat(document.getElementById('absences').value) || 0;
        leaveBalanceInput.value = Math.max(0, 15 - (lates * 0.25) - (absences * 1));
    }

    document.getElementById('lates').addEventListener('input', calculateBalance);
    document.getElementById('absences').addEventListener('input', calculateBalance);
</script>
@endpush

@endsection
