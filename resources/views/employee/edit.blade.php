@extends('layouts.admin')

@section('page-content')

<div style="margin-bottom:20px">
    <a href="{{ route('employees.index') }}" class="auth-nav-back">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Back to Employees
    </a>
</div>

<div class="auth-card auth-card-wide" style="max-width:800px">
    <div style="margin-bottom:24px">
        <h2 style="font-size:22px;font-weight:800;color:#0b044d;margin:0 0 6px">Edit Employee</h2>
        <p style="font-size:13px;color:#6b6a8a;margin:0">Update employee information</p>
    </div>

    <form action="{{ route('employees.update', $employee) }}" method="post" class="auth-form">
        @csrf
        @method('put')

        <div style="background:#f7f6ff;padding:16px;border-radius:10px;margin-bottom:18px">
            <p style="font-size:11px;font-weight:700;color:#9999bb;letter-spacing:1px;margin:0 0 12px">BIOGRAPHICAL INFORMATION</p>

            <div class="auth-row-2">
                <div class="auth-field">
                    <label>First Name <span style="color:#dc2626">*</span></label>
                    <input type="text" name="first_name" value="{{ old('first_name', $employee->first_name) }}" required>
                </div>
                <div class="auth-field">
                    <label>Last Name <span style="color:#dc2626">*</span></label>
                    <input type="text" name="last_name" value="{{ old('last_name', $employee->last_name) }}" required>
                </div>
            </div>

            <div class="auth-row-2">
                <div class="auth-field">
                    <label>Gender <span style="color:#dc2626">*</span></label>
                    <select name="gender" required>
                        <option value="">Select gender</option>
                        @foreach(['Male','Female','Other','Prefer not to say'] as $g)
                        <option value="{{ $g }}" {{ old('gender', $employee->gender) == $g ? 'selected' : '' }}>{{ $g }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="auth-field">
                    <label>Date of Birth <span style="color:#dc2626">*</span></label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $employee->date_of_birth) }}" required>
                </div>
            </div>

            <div class="auth-row-2">
                <div class="auth-field">
                    <label>Email Address <span style="color:#dc2626">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $employee->email) }}" required>
                </div>
                <div class="auth-field">
                    <label>Contact Number <span style="color:#dc2626">*</span></label>
                    <input type="text" name="phone_number" value="{{ old('phone_number', $employee->phone_number) }}" required>
                </div>
            </div>
        </div>

        <div style="background:#f7f6ff;padding:16px;border-radius:10px;margin-bottom:18px">
            <p style="font-size:11px;font-weight:700;color:#9999bb;letter-spacing:1px;margin:0 0 12px">ADDRESS INFORMATION</p>

            <div class="auth-field">
                <label>Street Address <span style="color:#dc2626">*</span></label>
                <input type="text" name="address[address]" value="{{ old('address.address', $employee->address->address) }}" required>
            </div>

            <div class="auth-row-2">
                <div class="auth-field">
                    <label>City <span style="color:#dc2626">*</span></label>
                    <input type="text" name="address[city]" value="{{ old('address.city', $employee->address->city) }}" required>
                </div>
                <div class="auth-field">
                    <label>Province <span style="color:#dc2626">*</span></label>
                    <input type="text" name="address[province]" value="{{ old('address.province', $employee->address->province) }}" required>
                </div>
            </div>

            <div class="auth-row-2">
                <div class="auth-field">
                    <label>Country <span style="color:#dc2626">*</span></label>
                    <input type="text" name="address[country]" value="{{ old('address.country', $employee->address->country) }}" required>
                </div>
                <div class="auth-field">
                    <label>Zip Code <span style="color:#dc2626">*</span></label>
                    <input type="number" name="address[zip_code]" value="{{ old('address.zip_code', $employee->address->zip_code) }}" required>
                </div>
            </div>
        </div>

        <div style="background:#f7f6ff;padding:16px;border-radius:10px;margin-bottom:18px">
            <p style="font-size:11px;font-weight:700;color:#9999bb;letter-spacing:1px;margin:0 0 12px">EMPLOYMENT INFORMATION</p>

            <div class="auth-row-2">
                <div class="auth-field">
                    <label>Position <span style="color:#dc2626">*</span></label>
                    <select name="position_id" required>
                        <option value="">Select position</option>
                        @foreach($positions as $position)
                        <option value="{{ $position->id }}" {{ old('position_id', $employee->position_id) == $position->id ? 'selected' : '' }}>{{ $position->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="auth-field">
                    <label>Department <span style="color:#dc2626">*</span></label>
                    <select name="department_id" required>
                        <option value="">Select department</option>
                        @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ old('department_id', $employee->department_id) == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="auth-row-2">
                <div class="auth-field">
                    <label>Employment Type <span style="color:#dc2626">*</span></label>
                    <select name="employment_type" required>
                        <option value="">Select employment type</option>
                        @foreach($employmentTypes as $type)
                        <option value="{{ $type->value }}" {{ old('employment_type', $employee->employment_type) == $type->value ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="auth-field">
                    <label>Work Schedule <span style="color:#dc2626">*</span></label>
                    <select name="work_schedule_id" required>
                        <option value="">Select work schedule</option>
                        @foreach($workSchedules as $schedule)
                        <option value="{{ $schedule->id }}" {{ old('work_schedule_id', $employee->employeeWorkSchedule->workSchedule->id ?? '') == $schedule->id ? 'selected' : '' }}>{{ $schedule->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="auth-field">
                <label>Status <span style="color:#dc2626">*</span></label>
                <select name="is_active" required>
                    <option value="1" {{ old('is_active', $employee->is_active) ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !old('is_active', $employee->is_active) ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>

        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
        <input type="hidden" name="address[user_id]" value="{{ auth()->user()->id }}">

        <div style="display:flex;gap:12px;margin-top:24px">
            <a href="{{ route('employees.index') }}" class="pub-btn-ghost" style="flex:1;justify-content:center;text-decoration:none">Cancel</a>
            <button type="submit" class="pub-btn-primary" style="flex:1;justify-content:center">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Update Employee
            </button>
        </div>
    </form>
</div>

@endsection
