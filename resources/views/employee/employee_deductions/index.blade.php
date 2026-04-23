@extends('layouts.admin')

@php
$grossDeductions = 0;
$optionalDeductions = 0;
$optionalDeductionsCount = 0;

foreach ($employees as $employee) {
    $grossDeductions += $employee->employeeDeduction->sum('amount');
    foreach ($employee->employeeDeduction as $deduction) {
        if ($deduction->deduction->type === \App\Enums\DeductionType::Optional->value) {
            $optionalDeductionsCount++;
            $optionalDeductions += $deduction->amount;
        }
    }
}
@endphp

@section('page-content')
<style>
.view-tabs{display:flex;gap:6px;margin-bottom:24px;background:#f7f6ff;padding:6px;border-radius:10px;border:1px solid #eceaf8;width:fit-content}
.view-tab{padding:8px 18px;border:none;background:transparent;border-radius:6px;font-size:13px;font-weight:600;color:#6b6a8a;cursor:pointer;transition:all 0.2s;display:flex;align-items:center;gap:6px}
.view-tab:hover{color:#0b044d}
.view-tab.active{background:#fff;color:#0b044d;box-shadow:0 2px 5px rgba(11,4,77,0.06)}
.tab-pane{display:none;animation:fadeIn 0.3s ease}
.tab-pane.active{display:block}
@keyframes fadeIn{from{opacity:0;transform:translateY(4px)}to{opacity:1;transform:translateY(0)}}
</style>

<div class="welcome-banner">
    <div class="banner-left">
        <div class="banner-icon">
            <svg width="22" height="22" fill="none" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </div>
        <div>
            <h2>Salary Structure</h2>
            <p>Manage employee salaries, grades, and deductions</p>
        </div>
    </div>
    <div class="banner-right">
        <span class="banner-badge outline">{{ $employees->count() }} Employees</span>
    </div>
</div>

<div class="stats-grid stats-grid-4">
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Gross Deductions</p>
            <div class="stat-icon-wrap" style="background:#f0effe">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#0b044d" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v12M8 10h8M8 14h8"/></svg>
            </div>
        </div>
        <p class="stat-value">₱{{ number_format($grossDeductions, 2) }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#22c55e"></span>
            <p class="stat-sub">Mandatory, Optional</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Gross Optional Deductions</p>
            <div class="stat-icon-wrap" style="background:#e8f9ef">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#15803d" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
            </div>
        </div>
        <p class="stat-value">₱{{ number_format($optionalDeductions, 2) }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#15803d"></span>
            <p class="stat-sub">{{ $optionalDeductionsCount == 1 ? $optionalDeductionsCount.' optional deduction' : $optionalDeductionsCount.' optional deductions' }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Total Salary Records</p>
            <div class="stat-icon-wrap" style="background:#fefce8">
                <svg width="17" height="17" fill="none" stroke="#a16207" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $salaries->total() }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#a16207"></span>
            <p class="stat-sub">Across all employees</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Deduction Types</p>
            <div class="stat-icon-wrap" style="background:#fdf0ef">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#8e1e18" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ \App\Models\Deduction::where('user_id', auth()->id())->count() }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#8e1e18"></span>
            <p class="stat-sub">Configured deductions</p>
        </div>
    </div>
</div>

<div class="view-tabs">
    <button class="view-tab active" onclick="switchView('salaries',this)">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        Salary List
    </button>
    <button class="view-tab" onclick="switchView('deductions',this)">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Deduction Management
    </button>
</div>

<div id="view-salaries" class="tab-pane active">
<div class="table-section">
    <div class="table-header">
        <div>
            <p class="table-title">Salary List</p>
            <p class="table-sub">Employee salaries, grades, and steps</p>
        </div>
        <div class="table-actions">
            <a href="{{ route('salaries.create') }}" class="modal-btn-primary">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Salary
            </a>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="payroll-table" id="salary-table">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Salary Type</th>
                    <th>Salary Grade</th>
                    <th>Step</th>
                    <th>Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($salaries as $salary)
                <tr>
                    <td>
                        <div class="emp-cell">
                            <div class="emp-avatar" style="background:{{ ['#0b044d','#8e1e18','#15803d','#a16207','#7c3aed'][($salary->employee->id % 5)] }}">
                                {{ strtoupper(substr($salary->employee->first_name, 0, 1) . substr($salary->employee->last_name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="emp-name">{{ $salary->employee->first_name }} {{ $salary->employee->last_name }}</p>
                                <p class="emp-id">{{ $salary->employee->position->title ?? 'No Position' }}</p>
                            </div>
                        </div>
                    </td>
                    <td><span class="dept-tag">{{ ucfirst($salary->salary_type->value) }}</span></td>
                    <td><span class="dept-tag" style="background:#fefce8;color:#a16207;border-color:#fde68a">SG {{ $salary->salary_grade ?? 'N/A' }}</span></td>
                    <td><span class="pay-cell">Step {{ $salary->step ?? 'N/A' }}</span></td>
                    <td><span class="pay-cell" style="font-weight:700">₱{{ number_format($salary->amount, 2) }}</span></td>
                    <td>
                        <div class="row-actions">
                            <a href="{{ route('salaries.edit', $salary) }}" class="btn-edit">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                            <form action="{{ route('salaries.destroy', $salary) }}" method="post" style="display:inline" onsubmit="return confirm('Delete this salary record?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger" style="display:inline-flex;align-items:center;gap:4px">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="empty-state">
                        <svg width="48" height="48" fill="none" stroke="#d9d9ee" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        <p style="font-size:14px;color:#9999bb;margin-top:12px">No salary records found</p>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:20px">
        {{ $salaries->links() }}
    </div>
</div>
</div>

<div id="view-deductions" class="tab-pane">
<div class="table-section">
    <div class="table-header">
        <div>
            <p class="table-title">Deduction Management</p>
            <p class="table-sub">Manage deductions applied to employees</p>
        </div>
        <div class="table-actions">
            <a href="{{ route('employee_deductions.create') }}" class="modal-btn-primary">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Deduction
            </a>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="payroll-table" id="deductions-table">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Mandatory Deductions</th>
                    <th>Optional Deductions</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
            @forelse($employees as $employee)
                <tr>
                    <td>
                        <div class="emp-cell">
                            <div class="emp-avatar" style="background:{{ ['#0b044d','#8e1e18','#15803d','#a16207','#7c3aed'][($employee->id % 5)] }}">
                                {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="emp-name">{{ $employee->first_name }} {{ $employee->last_name }}</p>
                                <p class="emp-id">EMP-{{ str_pad($employee->id, 3, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="display:flex;flex-direction:column;gap:4px">
                        @forelse($employee->employeeDeduction as $deduction)
                            @if($deduction->deduction->type === 'Mandatory')
                                <span style="font-size:12.5px;color:#5a5888">{{ $deduction->deduction->name }}: <strong style="color:#8e1e18">₱{{ number_format($deduction->amount, 2) }}</strong></span>
                            @endif
                        @empty
                            <span style="font-size:12px;color:#9999bb">None</span>
                        @endforelse
                        </div>
                    </td>
                    <td>
                        <div style="display:flex;flex-direction:column;gap:4px">
                        @forelse($employee->employeeDeduction as $deduction)
                            @if($deduction->deduction->type === 'Optional')
                                <div style="display:flex;align-items:center;gap:8px">
                                    <span style="font-size:12.5px;color:#5a5888">{{ $deduction->deduction->name }}: <strong style="color:#8e1e18">₱{{ number_format($deduction->amount, 2) }}</strong></span>
                                    <form action="{{ route('employee_deductions.destroy', $deduction) }}" method="post" style="display:inline" onsubmit="return confirm('Remove this deduction?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger" style="display:inline-flex;align-items:center;gap:4px">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @empty
                            <span style="font-size:12px;color:#9999bb">None</span>
                        @endforelse
                        </div>
                    </td>
                    <td><span class="deduction" style="font-size:13px;font-weight:700">₱{{ number_format($employee->employeeDeduction->sum('amount'), 2) }}</span></td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="empty-state">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="#d9d9ee" stroke="none"><text x="3" y="19" font-size="17" font-weight="bold" font-family="Arial, sans-serif">₱</text></svg>
                        <p style="font-size:14px;color:#9999bb;margin-top:12px">No employee deductions found</p>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>

@push('scripts')
<script>
function switchView(viewId, btn) {
    document.querySelectorAll('.tab-pane').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.view-tab').forEach(el => el.classList.remove('active'));
    document.getElementById('view-' + viewId).classList.add('active');
    btn.classList.add('active');
}
</script>
@endpush

@endsection
