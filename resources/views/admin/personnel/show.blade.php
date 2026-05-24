@extends('layouts.admin')

@section('page-content')

<div class="welcome-banner">
    <div class="banner-left">
        <div class="banner-icon">
            <svg width="22" height="22" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
        </div>
        <div>
            <h2>Employee Details</h2>
            <p>{{ $employee->first_name }} {{ $employee->last_name }} &nbsp;·&nbsp; EMP-{{ str_pad($employee->id, 3, '0', STR_PAD_LEFT) }}</p>
        </div>
    </div>
    <div class="banner-right">
        <button class="btn-view" onclick="openEditEmpModal({{ $employee->id }},'{{ addslashes($employee->first_name) }}','{{ addslashes($employee->last_name) }}','{{ addslashes($employee->middle_name) }}','{{ $employee->gender }}','{{ $employee->date_of_birth }}','{{ addslashes($employee->email) }}','{{ addslashes($employee->phone_number) }}','{{ addslashes($employee->address->address ?? '') }}','{{ addslashes($employee->address->city ?? '') }}','{{ addslashes($employee->address->province ?? '') }}','{{ addslashes($employee->address->country ?? '') }}','{{ $employee->address->zip_code ?? '' }}','{{ $employee->position_id }}','{{ $employee->department_id }}','{{ $employee->employment_type }}','{{ $employee->employeeWorkSchedule->workSchedule->id ?? '' }}','{{ $employee->is_active }}','{{ $employee->salary->salary_grade ?? '' }}','{{ $employee->salary->step ?? '' }}','{{ $employee->salary->salary_type->value ?? '' }}','{{ $employee->salary->amount ?? '' }}')">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            Edit Employee
        </button>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:18px;align-items:start">

    {{-- Left Column --}}
    <div style="display:flex;flex-direction:column;gap:18px">

        {{-- Profile Card --}}
        <div class="table-section" style="padding:24px">
            <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px">
                <div class="emp-avatar lg" style="background:{{ ['#0b044d','#8e1e18','#15803d','#a16207','#7c3aed'][($employee->id % 5)] }}">
                    {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                </div>
                <div>
                    <p style="font-size:18px;font-weight:800;color:#0b044d;margin:0 0 4px">{{ $employee->first_name }} {{ $employee->middle_name }} {{ $employee->last_name }}</p>
                    <p style="font-size:12px;color:#9999bb;margin:0">EMP-{{ str_pad($employee->id, 3, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div style="margin-left:auto">
                    @if($employee->is_active)
                        <span class="badge-status processed">Active</span>
                    @else
                        <span class="badge-status on-hold">Inactive</span>
                    @endif
                </div>
            </div>

            <p style="font-size:10px;font-weight:700;color:#9999bb;letter-spacing:1.5px;margin:0 0 10px">BIOGRAPHICAL INFORMATION</p>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0">
                @foreach([['Gender', $employee->gender], ['Date of Birth', $employee->date_of_birth], ['Email', $employee->email], ['Contact No.', $employee->phone_number]] as [$label, $value])
                <div style="padding:10px 0;border-bottom:1px solid #f7f6ff;font-size:13px">
                    <span style="color:#9999bb;display:block;font-size:11px;margin-bottom:2px">{{ $label }}</span>
                    <span style="color:#0b044d;font-weight:500">{{ $value }}</span>
                </div>
                @endforeach
            </div>

            <p style="font-size:10px;font-weight:700;color:#9999bb;letter-spacing:1.5px;margin:18px 0 10px">ADDRESS</p>
            <div style="font-size:13px;color:#5a5888;line-height:1.7">
                {{ $employee->address->address }}, {{ $employee->address->city }}, {{ $employee->address->province }}, {{ $employee->address->country }} {{ $employee->address->zip_code }}
            </div>
        </div>

        {{-- Deductions --}}
        <div class="table-section">
            <div class="table-header">
                <div>
                    <p class="table-title">Deductions</p>
                    <p class="table-sub">Applied compensations for this employee</p>
                </div>
            </div>
            <div class="table-wrapper">
                <table class="payroll-table">
                    <thead>
                        <tr>
                            <th>Deduction</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($employee->employeeCompensation as $compensation)
                        <tr>
                            <td><span style="font-size:13px;font-weight:600;color:#0b044d">{{ $compensation->compensation->name }}</span></td>
                            <td>
                                <span class="dept-tag" style="{{ $compensation->compensation->type === 'Mandatory' ? 'background:#fdf0ef;color:#8e1e18;border-color:#f5d0ce' : 'background:#f0effe;color:#0b044d' }}">
                                    {{ $compensation->compensation->type }}
                                </span>
                            </td>
                            <td><span class="deduction">₱{{ number_format($compensation->amount, 2) }}</span></td>
                            <td>
                                @if($compensation->compensation->type === 'Optional')
                                <form id="delete-form-{{ $compensation->id }}" action="{{ route('employee_compensations.destroy', $compensation) }}" method="post" style="display:inline" onsubmit="return confirm('Remove this deduction?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger" style="display:inline-flex;align-items:center;gap:4px">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        Remove
                                    </button>
                                </form>
                                @else
                                <span style="font-size:12px;color:#9999bb">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty-state">
                                <p style="font-size:13px;color:#9999bb">No compensations applied</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            @if($employee->employeeCompensation->count())
            <div class="table-footer">
                <span>Total Deductions</span>
                <span class="deduction" style="font-size:14px;font-weight:700">₱{{ number_format($employee->employeeCompensation->sum('amount'), 2) }}</span>
            </div>
            @endif
        </div>
    </div>

    {{-- Right Column --}}
    <div style="display:flex;flex-direction:column;gap:18px">

        {{-- Employment Info --}}
        <div class="table-section" style="padding:20px">
            <p style="font-size:10px;font-weight:700;color:#9999bb;letter-spacing:1.5px;margin:0 0 14px">EMPLOYMENT INFORMATION</p>
            @foreach([
                ['Position', $employee->position->title],
                ['Salary Grade', 'SG ' . ($employee->salary->salary_grade ?? 'N/A')],
                ['Department', $employee->department->name],
                ['Employment Type', $employee->employment_type],
                ['Work Schedule', $employee->employeeWorkSchedule->workSchedule->name],
                ['Sick Leave Credits', $employee->employeeSickLeave(). ' days'],
                ['Vacation Leave Credits',  $employee->employeeVacationLeave(). ' days'],
            ] as [$label, $value])
            <div style="display:flex;justify-content:space-between;align-items:center;padding:9px 0;border-bottom:1px solid #f7f6ff;font-size:13px">
                <span style="color:#9999bb">{{ $label }}</span>
                <span style="color:#0b044d;font-weight:600;text-align:right;max-width:180px">{{ $value }}</span>
            </div>
            @endforeach
        </div>

        {{-- Salary Info --}}
        <div class="table-section" style="padding:20px">
            <p style="font-size:10px;font-weight:700;color:#9999bb;letter-spacing:1.5px;margin:0 0 14px">SALARY INFORMATION</p>
            @foreach([
                ['Hourly Rate', '₱ ' . round($employee->hourlyRate(), 2)],
                ['Daily Rate', '₱ ' . round($employee->dailyRate(), 2)],
                ['Monthly Salary', '₱' . number_format($employee->salary->amount ?? 0, 2)],
            ] as [$label, $value])
            <div style="display:flex;justify-content:space-between;align-items:center;padding:9px 0;border-bottom:1px solid #f7f6ff;font-size:13px">
                <span style="color:#9999bb">{{ $label }}</span>
                <span style="color:#0b044d;font-weight:600;text-align:right;max-width:180px">{{ $value }}</span>
            </div>
            @endforeach
        </div>

    </div>
</div>

{{-- Edit Employee Modal --}}
<div class="modal-overlay" id="edit-emp-modal" style="display:none" onclick="closeEditEmpModal()">
    <div class="modal-box modal-lg" onclick="event.stopPropagation()" style="width:min(680px,100%)">
        <div class="modal-header">
            <div>
                <span class="modal-eyebrow">EMPLOYEES</span>
                <h3 class="modal-title" id="eemp-title">Edit Employee</h3>
            </div>
            <button class="modal-close" onclick="closeEditEmpModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <div class="pmodal-tabs" id="eemp-tabs">
            <button type="button" class="pmodal-tab active" onclick="eempGoTo(0)">Biographical</button>
            <button type="button" class="pmodal-tab" onclick="eempGoTo(1)">Address</button>
            <button type="button" class="pmodal-tab" onclick="eempGoTo(2)">Employment</button>
            <button type="button" class="pmodal-tab" onclick="eempGoTo(3)">Salary</button>
        </div>

        <form id="eemp-form" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            <input type="hidden" name="address[user_id]" value="{{ auth()->user()->id }}">

            <div class="pmodal-body" style="padding:20px 24px">

                {{-- Step 0: Biographical --}}
                <div class="eemp-step" id="eemp-step-0">
                    <p class="form-section-label">BIOGRAPHICAL INFORMATION</p>
                    <div class="form-grid">
                        <div class="form-field">
                            <label>First Name <span style="color:#dc2626">*</span></label>
                            <input type="text" name="first_name" id="eemp-first_name" required>
                        </div>
                        <div class="form-field">
                            <label>Last Name <span style="color:#dc2626">*</span></label>
                            <input type="text" name="last_name" id="eemp-last_name" required>
                        </div>
                        <div class="form-field">
                            <label>Middle Name <span style="color:#dc2626">*</span></label>
                            <input type="text" name="middle_name" id="eemp-middle_name">
                        </div>
                        <div class="form-field">
                            <label>Gender <span style="color:#dc2626">*</span></label>
                            <select name="gender" id="eemp-gender" required>
                                <option value="">Select gender</option>
                                @foreach(['Male','Female','Other','Prefer not to say'] as $g)
                                    <option value="{{ $g }}">{{ $g }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Date of Birth <span style="color:#dc2626">*</span></label>
                            <input type="date" name="date_of_birth" id="eemp-dob" required>
                        </div>
                        <div class="form-field">
                            <label>Email Address <span style="color:#dc2626">*</span></label>
                            <input type="email" name="email" id="eemp-email" required>
                        </div>
                        <div class="form-field">
                            <label>Contact Number <span style="color:#dc2626">*</span></label>
                            <input type="text" name="phone_number" id="eemp-phone" required>
                        </div>
                    </div>
                </div>

                {{-- Step 1: Address --}}
                <div class="eemp-step" id="eemp-step-1" style="display:none">
                    <p class="form-section-label">ADDRESS INFORMATION</p>
                    <div class="form-grid">
                        <div class="form-field form-full">
                            <label>Street Address <span style="color:#dc2626">*</span></label>
                            <input type="text" name="address[address]" id="eemp-address" required>
                        </div>
                        <div class="form-field">
                            <label>City <span style="color:#dc2626">*</span></label>
                            <input type="text" name="address[city]" id="eemp-city" required>
                        </div>
                        <div class="form-field">
                            <label>Province <span style="color:#dc2626">*</span></label>
                            <input type="text" name="address[province]" id="eemp-province" required>
                        </div>
                        <div class="form-field">
                            <label>Country <span style="color:#dc2626">*</span></label>
                            <input type="text" name="address[country]" id="eemp-country" required>
                        </div>
                        <div class="form-field">
                            <label>Zip Code <span style="color:#dc2626">*</span></label>
                            <input type="number" name="address[zip_code]" id="eemp-zip" required>
                        </div>
                    </div>
                </div>

                {{-- Step 2: Employment --}}
                <div class="eemp-step" id="eemp-step-2" style="display:none">
                    <p class="form-section-label">EMPLOYMENT INFORMATION</p>
                    <div class="form-grid">
                        <div class="form-field">
                            <label>Position <span style="color:#dc2626">*</span></label>
                            <select name="position_id" id="eemp-position" required>
                                <option value="">Select position</option>
                                @foreach($positions as $position)
                                    @php $cnt = $employees->where('position_id',$position->id)->count(); @endphp
                                    <option value="{{ $position->id }}" {{ $cnt >= $position->total_employees ? 'style=color:#9999bb' : '' }}>{{ $position->title }}{{ $cnt >= $position->total_employees ? ' (Closed)' : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Department <span style="color:#dc2626">*</span></label>
                            <select name="department_id" id="eemp-department" required>
                                <option value="">Select department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Employment Type <span style="color:#dc2626">*</span></label>
                            <select name="employment_type" id="eemp-emptype" required>
                                <option value="">Select type</option>
                                @foreach($employmentTypes as $type)
                                    <option value="{{ $type->value }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Work Schedule <span style="color:#dc2626">*</span></label>
                            <select name="work_schedule_id" id="eemp-schedule" required>
                                <option value="">Select schedule</option>
                                @foreach($workSchedules as $schedule)
                                    <option value="{{ $schedule->id }}">{{ $schedule->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Status <span style="color:#dc2626">*</span></label>
                            <select name="is_active" id="eemp-status" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Step 3: Salary --}}
                <div class="eemp-step" id="eemp-step-3" style="display:none">
                    <p class="form-section-label">SALARY INFORMATION</p>
                    <div class="form-grid">
                        <div class="form-field">
                            <label>Salary Type <span style="color:#dc2626">*</span></label>
                            <select name="salary[salary_type]" id="eemp-saltype" required>
                                <option value="">Select type</option>
                                @foreach($salaryTypes as $type)
                                    <option value="{{ $type->value }}">{{ ucfirst($type->value) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Amount <span style="color:#dc2626">*</span></label>
                            <input type="number" step="0.01" min="0" name="salary[amount]" id="eemp-amount" required>
                        </div>
                        <div class="form-field">
                            <label>Salary Grade</label>
                            <input type="number" name="salary[salary_grade]" id="eemp-grade" min="1">
                        </div>
                        <div class="form-field">
                            <label>Step</label>
                            <input type="number" name="salary[step]" id="eemp-step-val" min="1">
                        </div>
                    </div>
                </div>

            </div>{{-- end pmodal-body --}}

            <div class="modal-footer">
                <button type="button" class="modal-btn-ghost" id="eemp-prev" onclick="eempNav(-1)" style="display:none">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                    Back
                </button>
                <button type="button" class="modal-btn-ghost" onclick="closeEditEmpModal()">Cancel</button>
                <button type="button" class="modal-btn-primary" id="eemp-next" onclick="eempNav(1)">
                    Next
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </button>
                <button type="submit" class="modal-btn-primary" id="eemp-submit" style="display:none">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Update Employee
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
    <script>
        let eempStep = 0;
        const eempTotal = 4;

        function openEditEmpModal(id, firstName, lastName, middleName, gender, dob, email, phone, address, city, province, country, zip, positionId, deptId, empType, scheduleId, isActive, salGrade, salStep, salType, salAmount) {
            eempStep = 0;
            eempRender();

            // Set form action
            var url = '{{ route("employees.update", ":id") }}'.replace(':id', id);
            document.getElementById('eemp-form').action = url;

            // Biographical
            document.getElementById('eemp-title').textContent = 'Edit: ' + firstName + ' ' + lastName;
            document.getElementById('eemp-first_name').value = firstName;
            document.getElementById('eemp-last_name').value  = lastName;
            document.getElementById('eemp-middle_name').value  = middleName;
            document.getElementById('eemp-dob').value         = dob;
            document.getElementById('eemp-email').value       = email;
            document.getElementById('eemp-phone').value       = phone;
            setSelect('eemp-gender', gender);

            // Address
            document.getElementById('eemp-address').value  = address;
            document.getElementById('eemp-city').value     = city;
            document.getElementById('eemp-province').value = province;
            document.getElementById('eemp-country').value  = country;
            document.getElementById('eemp-zip').value      = zip;

            // Employment
            setSelect('eemp-position',   positionId);
            setSelect('eemp-department', deptId);
            setSelect('eemp-emptype',    empType);
            setSelect('eemp-schedule',   scheduleId);
            setSelect('eemp-status',     isActive ? '1' : '0');

            // Salary
            setSelect('eemp-saltype', salType);
            document.getElementById('eemp-amount').value    = salAmount;
            document.getElementById('eemp-grade').value     = salGrade;
            document.getElementById('eemp-step-val').value  = salStep;

            document.getElementById('edit-emp-modal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function eempRender() {
            for (let i = 0; i < eempTotal; i++) {
                document.getElementById('eemp-step-' + i).style.display = i === eempStep ? 'block' : 'none';
            }
            document.querySelectorAll('#eemp-tabs .pmodal-tab').forEach((btn, i) => {
                btn.classList.toggle('active', i === eempStep);
            });
            const isFirst = eempStep === 0;
            const isLast  = eempStep === eempTotal - 1;
            document.getElementById('eemp-prev').style.display   = isFirst ? 'none' : 'inline-flex';
            document.getElementById('eemp-next').style.display   = isLast  ? 'none' : 'inline-flex';
            document.getElementById('eemp-submit').style.display = isLast  ? 'inline-flex' : 'none';
        }

        function setSelect(id, value) {
            const sel = document.getElementById(id);
            if (!sel) return;
            for (let opt of sel.options) {
                opt.selected = (opt.value == value);
            }
        }

        function eempGoTo(step) {
            eempStep = step;
            eempRender();
        }

        function eempNav(dir) {
            eempStep = Math.max(0, Math.min(eempTotal - 1, eempStep + dir));
            eempRender();
        }
    </script>
@endpush
