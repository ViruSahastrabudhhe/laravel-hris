@extends('layouts.admin')

@section('page-content')
<div style="margin-bottom:20px">
    <a href="{{ url()->previous() }}" class="auth-nav-back">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Back to Employees
    </a>
</div>

<div class="auth-card auth-card-wide" style="max-width:800px">
    <div style="margin-bottom:24px">
        <h2 style="font-size:22px;font-weight:800;color:#0b044d;margin:0 0 6px">Add New Employee</h2>
        <p style="font-size:13px;color:#6b6a8a;margin:0">Fill in the details to register a new employee</p>
    </div>

    <form action="{{ route('employees.store') }}" method='post' class="auth-form">
        @csrf

        <div style="background:#f7f6ff;padding:16px;border-radius:10px;margin-bottom:18px">
            <p style="font-size:11px;font-weight:700;color:#9999bb;letter-spacing:1px;margin:0 0 12px">BIOGRAPHICAL INFORMATION</p>
            
            <div class="auth-row-2">
                <div class="auth-field">
                    <label>First Name <span style="color:#dc2626">*</span></label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="e.g. Juan" required>
                </div>
                <div class="auth-field">
                    <label>Last Name <span style="color:#dc2626">*</span></label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="e.g. Dela Cruz" required>
                </div>
            </div>

            <div class="auth-row-2">
                <div class="auth-field">
                    <label>Gender <span style="color:#dc2626">*</span></label>
                    <select name="gender" required>
                        <option value="">Select gender</option>
                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                        <option value="Prefer not to say" {{ old('gender') == 'Prefer not to say' ? 'selected' : '' }}>Prefer not to say</option>
                    </select>
                </div>
                <div class="auth-field">
                    <label>Date of Birth <span style="color:#dc2626">*</span></label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                </div>
            </div>

            <div class="auth-row-2">
                <div class="auth-field">
                    <label>Email Address <span style="color:#dc2626">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="e.g. juan@lgu.gov.ph" required>
                    <span id="email-error" style="color:#dc2626;font-size:12px;display:none">This email is already in use.</span>
                </div>
                <div class="auth-field">
                    <label>Contact Number <span style="color:#dc2626">*</span></label>
                    <input type="text" name="phone_number" value="{{ old('phone_number') }}" placeholder="e.g. 09XX-XXX-XXXX" required>
                </div>
            </div>
        </div>

        <div style="background:#f7f6ff;padding:16px;border-radius:10px;margin-bottom:18px">
            <p style="font-size:11px;font-weight:700;color:#9999bb;letter-spacing:1px;margin:0 0 12px">ADDRESS INFORMATION</p>
            
            <div class="auth-field">
                <label>Street Address <span style="color:#dc2626">*</span></label>
                <input type="text" name="address[address]" value="{{ old('address.address') }}" placeholder="e.g. 123 Main Street" required>
            </div>

            <div class="auth-row-2">
                <div class="auth-field">
                    <label>City <span style="color:#dc2626">*</span></label>
                    <input type="text" name="address[city]" value="{{ old('address.city') }}" placeholder="e.g. Pagsanjan" required>
                </div>
                <div class="auth-field">
                    <label>Province <span style="color:#dc2626">*</span></label>
                    <input type="text" name="address[province]" value="{{ old('address.province') }}" placeholder="e.g. Laguna" required>
                </div>
            </div>

            <div class="auth-row-2">
                <div class="auth-field">
                    <label>Country <span style="color:#dc2626">*</span></label>
                    <input type="text" name="address[country]" value="{{ old('address.country', 'Philippines') }}" required>
                </div>
                <div class="auth-field">
                    <label>Zip Code <span style="color:#dc2626">*</span></label>
                    <input type="number" name="address[zip_code]" value="{{ old('address.zip_code') }}" placeholder="e.g. 4008" required>
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
                            <option value="{{ $position->id }}" {{ old('position_id') == $position->id ? 'selected' : '' }}>
                                {{ $position->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="auth-field">
                    <label>Department <span style="color:#dc2626">*</span></label>
                    <select name="department_id" required>
                        <option value="">Select department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
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
                            <option value="{{ $type->value }}" {{ old('employment_type') == $type->value ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="auth-field">
                    <label>Work Schedule <span style="color:#dc2626">*</span></label>
                    <select name="work_schedule_id" required>
                        <option value="">Select work schedule</option>
                        @foreach($workSchedules as $schedule)
                            <option value="{{ $schedule->id }}" {{ old('work_schedule_id') == $schedule->id ? 'selected' : '' }}>
                                {{ $schedule->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="auth-field">
                <label>Status <span style="color:#dc2626">*</span></label>
                <select name="is_active" required>
                    <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>
        
        <div style="background:#f7f6ff;padding:16px;border-radius:10px;margin-bottom:18px">
            <p style="font-size:11px;font-weight:700;color:#9999bb;letter-spacing:1px;margin:0 0 12px">SALARY INFORMATION</p>

            <div class="auth-row-2">
                <div class="auth-field">
                    <label>Salary Type <span style="color:#dc2626">*</span></label>
                    <select name="salary[salary_type]" required>
                        <option value="">Select salary type</option>
                        @foreach($salaryTypes as $type)
                            <option value="{{ $type->value }}" {{ old('salary.salary_type') == $type->value ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="auth-field">
                    <label>Amount <span style="color:#dc2626">*</span></label>
                    <input type="number" step="0.01" name="salary[amount]" value="{{ old('salary.amount') }}" placeholder="e.g. 25000.00" required>
                </div>
            </div>

            <div class="auth-row-2">
                <div class="auth-field">
                    <label>Salary Grade</label>
                    <input type="number" name="salary[salary_grade]" value="{{ old('salary.salary_grade') }}" placeholder="e.g. 10">
                </div>
                <div class="auth-field">
                    <label>Step</label>
                    <input type="number" name="salary[step]" value="{{ old('salary.step') }}" placeholder="e.g. 1">
                </div>
            </div>
        </div>

        <div style="background:#f7f6ff;padding:16px;border-radius:10px;margin-bottom:18px">
            <p style="font-size:11px;font-weight:700;color:#9999bb;letter-spacing:1px;margin:0 0 12px">ACCOUNT INFORMATION</p>
            
            <div class="auth-field">
                <label for="password">Password <span style="color:#dc2626">*</span></label>
                <div class="auth-pw-wrap">
                    <input id="password" type="password" name="password" placeholder="Create a password" required autocomplete="new-password">
                    <button type="button" class="auth-eye" onclick="togglePassword('password', 'eye-icon-1')">
                        <svg id="eye-icon-1" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
            </div>
            <div class="auth-field">
                <label for="password-confirm">Confirm Password <span style="color:#dc2626">*</span></label>
                <div class="auth-pw-wrap">
                    <input id="password-confirm" type="password" name="password_confirmation" placeholder="Repeat your password" required autocomplete="new-password">
                    <button type="button" class="auth-eye" onclick="togglePassword('password-confirm', 'eye-icon-2')">
                        <svg id="eye-icon-2" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
            </div>

        </div>

        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
        <input type="hidden" name="address[user_id]" value="{{ auth()->user()->id }}">

        <div style="display:flex;gap:12px;margin-top:24px">
            <a href="{{ route('employees.index') }}" class="pub-btn-ghost" style="flex:1;justify-content:center;text-decoration:none">
                Cancel
            </a>
            <button type="submit" class="pub-btn-primary" style="flex:1;justify-content:center">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Create Employee
            </button>
        </div>
    </form>
</div>

<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
    } else {
        input.type = 'password';
        icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
    }
}

let emailTimeout;
document.getElementById('email').addEventListener('input', function () {
    clearTimeout(emailTimeout);
    const email = this.value;
    const error = document.getElementById('email-error');
    if (!email) { error.style.display = 'none'; return; }
    emailTimeout = setTimeout(() => {
        fetch('{{ route('employees.checkEmail') }}?email=' + encodeURIComponent(email))
            .then(r => r.json())
            .then(data => { error.style.display = data.exists ? 'block' : 'none'; });
    }, 400);
});

document.querySelector('form').addEventListener('submit', function (e) {
    if (document.getElementById('email-error').style.display === 'block') {
        e.preventDefault();
    }
});
</script>
@endsection
