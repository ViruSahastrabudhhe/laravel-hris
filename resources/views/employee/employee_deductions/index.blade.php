@extends('layouts.admin')

@php
$grossDeductions = 0;
$optionalDeductions = 0;
$optionalDeductionsCount = 0;
$highestOptionalDeductions = 0;

$grossSalaries = 0;
$highestSalary = 0;
$minimumSalary = 0;

foreach ($employees as $employee) {
    $grossDeductions += $employee->employeeDeduction->sum('amount');
    $grossSalaries += $employee->salary ? $employee->salary->amount : 0;
    foreach ($employee->employeeDeduction as $deduction) {
        if ($deduction->deduction->type === \App\Enums\DeductionType::Optional->value) {
            $optionalDeductionsCount++;
            $optionalDeductions += $deduction->amount;
        }
    }
}

$highestSalary = $salaries->getCollection()->max('amount') ?? 0;
$minimumSalary = $salaries->getCollection()->min('amount') ?? 0;
$highestGrade  = $salaries->getCollection()->max('salary_grade') ?? 'N/A';
$deductionsList = \App\Models\Deduction::where('user_id', auth()->id())->get();
$deductionTypesCount = $deductionsList->count();
$mandatoryDeductionTypes = $deductionsList->where('type', \App\Enums\DeductionType::Mandatory->value)->count();
$optionalDeductionTypes = $deductionsList->where('type', \App\Enums\DeductionType::Optional->value)->count();
$deductionEntriesCount = $employees->sum(fn($employee) => $employee->employeeDeduction->count());
$mandatoryDeductions = 0;
foreach ($employees as $employee) {
    foreach ($employee->employeeDeduction as $d) {
        if ($d->deduction->type === \App\Enums\DeductionType::Mandatory->value) {
            $mandatoryDeductions += $d->amount;
        }
    }
}
@endphp

@section('page-content')
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

<div class="view-tabs">
    <button class="view-tab active" onclick="switchView('salaries',this)">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        Salary Management
    </button>
    <button class="view-tab" onclick="switchView('deductions',this)">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
        Deduction Management
    </button>
    <button class="view-tab" onclick="switchView('deduction-types',this)">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="3"/><path d="M8 12h8M12 8v8"/></svg>
        Deduction Types
    </button>
</div>

{{-- Salary Stats --}}
<div id="stats-salaries" class="stats-grid stats-grid-4">
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Gross Salaries</p>
            <div class="stat-icon-wrap" style="background:#f0effe">
                <svg width="17" height="17" fill="none" stroke="#0b044d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
        </div>
        <p class="stat-value">₱{{ number_format($grossSalaries, 2) }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#22c55e"></span>
            <p class="stat-sub">{{ $employees->count() }} {{ $employees->count() == 1 ? 'employee' : 'employees' }}</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Highest Salary</p>
            <div class="stat-icon-wrap" style="background:#fefce8">
                <svg width="17" height="17" fill="none" stroke="#a16207" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            </div>
        </div>
        <p class="stat-value">₱{{ number_format($highestSalary, 2) }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#a16207"></span>
            <p class="stat-sub">Top earner this period</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Minimum Salary</p>
            <div class="stat-icon-wrap" style="background:#fdf0ef">
                <svg width="17" height="17" fill="none" stroke="#8e1e18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/><polyline points="17 18 23 18 23 12"/></svg>
            </div>
        </div>
        <p class="stat-value">₱{{ number_format($minimumSalary, 2) }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#8e1e18"></span>
            <p class="stat-sub">Lowest this period</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Highest Grade</p>
            <div class="stat-icon-wrap" style="background:#e8f9ef">
                <svg width="17" height="17" fill="none" stroke="#15803d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>
            </div>
        </div>
        <p class="stat-value">SG {{ $highestGrade }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#15803d"></span>
            <p class="stat-sub">{{ $salaries->total() }} salary {{ $salaries->total() == 1 ? 'record' : 'records' }}</p>
        </div>
    </div>
</div>

{{-- Deduction Stats --}}
<div id="stats-deductions" class="stats-grid stats-grid-4" style="display:none">
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Gross Deductions</p>
            <div class="stat-icon-wrap" style="background:#fdf0ef">
                <svg width="17" height="17" fill="none" stroke="#8e1e18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
            </div>
        </div>
        <p class="stat-value">₱{{ number_format($grossDeductions, 2) }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#8e1e18"></span>
            <p class="stat-sub">Mandatory &amp; optional</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Mandatory Deductions</p>
            <div class="stat-icon-wrap" style="background:#f0effe">
                <svg width="17" height="17" fill="none" stroke="#0b044d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 12h6M12 9v6"/></svg>
            </div>
        </div>
        <p class="stat-value">₱{{ number_format($mandatoryDeductions, 2) }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#0b044d"></span>
            <p class="stat-sub">GSIS, PhilHealth, Pag-Ibig</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Optional Deductions</p>
            <div class="stat-icon-wrap" style="background:#e8f9ef">
                <svg width="17" height="17" fill="none" stroke="#15803d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
            </div>
        </div>
        <p class="stat-value">₱{{ number_format($optionalDeductions, 2) }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#15803d"></span>
            <p class="stat-sub">{{ $optionalDeductionsCount }} {{ $optionalDeductionsCount == 1 ? 'entry' : 'entries' }}</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Deduction Types</p>
            <div class="stat-icon-wrap" style="background:#fefce8">
                <svg width="17" height="17" fill="none" stroke="#a16207" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $deductionTypesCount }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#a16207"></span>
            <p class="stat-sub">Configured deductions</p>
        </div>
    </div>
</div>

<div id="stats-deduction-types" class="stats-grid stats-grid-4" style="display:none">
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Deduction Types</p>
            <div class="stat-icon-wrap" style="background:#f0effe">
                <svg width="17" height="17" fill="none" stroke="#0b044d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $deductionTypesCount }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#22c55e"></span>
            <p class="stat-sub">Configured deduction types</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Mandatory Types</p>
            <div class="stat-icon-wrap" style="background:#fefce8">
                <svg width="17" height="17" fill="none" stroke="#a16207" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M7 9h10M7 15h10"/><path d="M7 5h10"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $mandatoryDeductionTypes }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#a16207"></span>
            <p class="stat-sub">Required deduction types</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Optional Types</p>
            <div class="stat-icon-wrap" style="background:#e8f9ef">
                <svg width="17" height="17" fill="none" stroke="#15803d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M7 7h10M7 17h10"/><path d="M11 3v18"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $optionalDeductionTypes }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#15803d"></span>
            <p class="stat-sub">Optional deduction types</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <p class="stat-label">Deduction Entries</p>
            <div class="stat-icon-wrap" style="background:#fdf0ef">
                <svg width="17" height="17" fill="none" stroke="#8e1e18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M6 5h12M6 19h12M6 12h12"/></svg>
            </div>
        </div>
        <p class="stat-value">{{ $deductionEntriesCount }}</p>
        <div class="stat-footer">
            <span class="stat-dot" style="background:#8e1e18"></span>
            <p class="stat-sub">Employee deduction lines</p>
        </div>
    </div>
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
                    <th>Position</th>
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
                                <p class="emp-id">EMP-{{ str_pad($salary->employee->id, 3, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>
                    </td>
                    <td><span>{{ ucfirst($salary->employee->position->title) }}</span></td>
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

<div id="view-deduction-types" class="tab-pane">
    <div class="table-section">
        <div class="table-header">
            <div>
                <p class="table-title">Deduction Types</p>
                <p class="table-sub">Configured deduction types and rates</p>
            </div>
            <div class="table-actions">
                <a href="{{ route('deductions.create') }}" class="modal-btn-primary">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Add Deduction Type
                </a>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="payroll-table" id="deduction-types-table">
                <thead>
                    <tr>
                        <th>Deduction Name</th>
                        <th>Rate</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($deductionsList as $deduction)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                <div style="width:36px;height:36px;background:{{ ['#0b044d','#8e1e18','#15803d','#a16207','#7c3aed'][($deduction->id % 5)] }};border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><text x="3" y="19" font-size="17" font-weight="bold" font-family="Arial, sans-serif">₱</text></svg>
                                </div>
                                <span style="font-size:13px;font-weight:600;color:#0b044d">{{ $deduction->name }}</span>
                            </div>
                        </td>
                        <td>
                            @if($deduction->rate == 0)
                                <span style="font-size:12.5px;color:#9999bb">—</span>
                            @else
                                <span class="dept-tag" style="background:#fefce8;color:#a16207;border-color:#fde68a">{{ $deduction->rate * 100 }}%</span>
                            @endif
                        </td>
                        <td>
                            <span class="dept-tag" style="{{ $deduction->type === 'Mandatory' ? 'background:#fdf0ef;color:#8e1e18;border-color:#f5d0ce' : 'background:#f0effe;color:#0b044d' }}">
                                {{ $deduction->type }}
                            </span>
                        </td>
                        <td><span style="font-size:12.5px;color:#5a5888">{{ $deduction->description ?? '—' }}</span></td>
                        <td>
                            <div class="row-actions">
                                <a href="{{ route('deductions.edit', $deduction) }}" class="btn-edit">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                                <form action="{{ route('deductions.destroy', $deduction) }}" method="post" style="display:inline" onsubmit="return confirm('Delete this deduction type?')">
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
                        <td colspan="5" class="empty-state">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="#d9d9ee" stroke="none"><text x="3" y="19" font-size="17" font-weight="bold" font-family="Arial, sans-serif">₱</text></svg>
                            <p style="font-size:14px;color:#9999bb;margin-top:12px">No deduction types found</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="table-footer">
            <span>Showing <strong>{{ $deductionsList->count() }}</strong> deduction types</span>
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
    if (btn.classList.contains('active')) {
        return;
    }

    document.querySelectorAll('.tab-pane').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.view-tab').forEach(el => el.classList.remove('active'));
    document.getElementById('view-' + viewId).classList.add('active');
    btn.classList.add('active');
    document.getElementById('stats-salaries').style.display  = viewId === 'salaries'   ? 'grid' : 'none';
    document.getElementById('stats-deductions').style.display = viewId === 'deductions' ? 'grid' : 'none';
    document.getElementById('stats-deduction-types').style.display = viewId === 'deduction-types' ? 'grid' : 'none';
}
</script>
@endpush

@endsection
