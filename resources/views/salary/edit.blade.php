@extends('layouts.admin')

@section('page-content')

<div style="margin-bottom:20px">
    <a href="{{ route('salaries.index') }}" class="auth-nav-back">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Back to Salaries
    </a>
</div>

<div class="auth-card" style="max-width:600px">
    <div style="margin-bottom:24px">
        <h2 style="font-size:22px;font-weight:800;color:#0b044d;margin:0 0 6px">Edit Salary</h2>
        <p style="font-size:13px;color:#6b6a8a;margin:0">Update salary record for {{ $salary->employee->first_name }} {{ $salary->employee->last_name }}</p>
    </div>

    <form action="{{ route('salaries.update', $salary) }}" method="post" class="auth-form">
        @csrf
        @method('PUT')

        <div class="auth-field">
            <label>Employee <span style="color:#dc2626">*</span></label>
            <select name="employee_id" required>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ $salary->employee_id == $employee->id ? 'selected' : '' }}>
                        {{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->position->title ?? 'No Position' }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="auth-field">
            <label>Salary Type <span style="color:#dc2626">*</span></label>
            <select name="salary_type" required>
                <option value="monthly" {{ $salary->salary_type->value == 'monthly' ? 'selected' : '' }}>Monthly</option>
                <option value="hourly" {{ $salary->salary_type->value == 'hourly' ? 'selected' : '' }}>Hourly</option>
                <option value="contractual" {{ $salary->salary_type->value == 'contractual' ? 'selected' : '' }}>Contractual</option>
            </select>
        </div>

        <div class="auth-row-2">
            <div class="auth-field">
                <label>Salary Grade</label>
                <input type="number" name="salary_grade" value="{{ old('salary_grade', $salary->salary_grade) }}" placeholder="e.g. 11">
            </div>
            <div class="auth-field">
                <label>Step</label>
                <input type="number" name="step" value="{{ old('step', $salary->step) }}" placeholder="e.g. 1" min="1">
            </div>
        </div>

        <div class="auth-field">
            <label>Amount <span style="color:#dc2626">*</span></label>
            <input type="number" name="amount" value="{{ old('amount', $salary->amount) }}" placeholder="e.g. 25000" step="0.01" required>
        </div>

        <div style="display:flex;gap:12px;margin-top:24px">
            <a href="{{ route('salaries.index') }}" class="pub-btn-ghost" style="flex:1;justify-content:center;text-decoration:none">Cancel</a>
            <button type="submit" class="pub-btn-primary" style="flex:1;justify-content:center">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Update Salary
            </button>
        </div>
    </form>
</div>

@endsection
