@extends('layouts.admin')

@section('page-content')

<div style="margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
    <a href="{{ route('employees.index') }}" class="auth-nav-back">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Back to Employees
    </a>
    <a href="{{ route('employees.edit', $employee) }}" class="modal-btn-primary">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        Edit Employee
    </a>
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
                    <p style="font-size:18px;font-weight:800;color:#0b044d;margin:0 0 4px">{{ $employee->first_name }} {{ $employee->last_name }}</p>
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
                    <p class="table-sub">Applied deductions for this employee</p>
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
                    @forelse($employee->employeeDeduction as $deduction)
                        <tr>
                            <td><span style="font-size:13px;font-weight:600;color:#0b044d">{{ $deduction->deduction->name }}</span></td>
                            <td>
                                <span class="dept-tag" style="{{ $deduction->deduction->type === 'Mandatory' ? 'background:#fdf0ef;color:#8e1e18;border-color:#f5d0ce' : 'background:#f0effe;color:#0b044d' }}">
                                    {{ $deduction->deduction->type }}
                                </span>
                            </td>
                            <td><span class="deduction">₱{{ number_format($deduction->amount, 2) }}</span></td>
                            <td>
                                @if($deduction->deduction->type === 'Optional')
                                <form id="delete-form-{{ $deduction->id }}" action="{{ route('employee_deductions.destroy', $deduction) }}" method="post" style="display:inline" onsubmit="return confirm('Remove this deduction?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-view" style="color:#8e1e18;border-color:#f5d0ce">
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
                                <p style="font-size:13px;color:#9999bb">No deductions applied</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            @if($employee->employeeDeduction->count())
            <div class="table-footer">
                <span>Total Deductions</span>
                <span class="deduction" style="font-size:14px;font-weight:700">₱{{ number_format($employee->employeeDeduction->sum('amount'), 2) }}</span>
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
                ['Salary Grade', 'SG ' . $employee->position->salary_grade],
                ['Monthly Salary', '₱' . number_format($employee->position->monthly_salary ?? $employee->position->salary_amount, 2)],
                ['Department', $employee->department->name],
                ['Employment Type', $employee->employment_type],
                ['Work Schedule', $employee->employeeWorkSchedule->workSchedule->name],
                ['Leave Balance', $employee->leaveBalance->leave_balance . ' days'],
            ] as [$label, $value])
            <div style="display:flex;justify-content:space-between;align-items:center;padding:9px 0;border-bottom:1px solid #f7f6ff;font-size:13px">
                <span style="color:#9999bb">{{ $label }}</span>
                <span style="color:#0b044d;font-weight:600;text-align:right;max-width:180px">{{ $value }}</span>
            </div>
            @endforeach
        </div>

    </div>
</div>

@endsection
